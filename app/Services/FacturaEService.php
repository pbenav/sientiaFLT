<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Str;

class FacturaEService
{
    /**
     * Genera el XML base de FacturaE v3.2.1 listo para ser firmado por AutoFirma.
     */
    public function generateXML(Invoice $invoice): string
    {
        $invoiceNumber = htmlspecialchars($invoice->invoice_number);
        $issueDate = $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : date('Y-m-d');
        $totalAmount = number_format($invoice->total_amount, 2, '.', '');
        $taxAmount = number_format($invoice->tax_amount, 2, '.', '');
        $subtotal = number_format($invoice->subtotal, 2, '.', '');
        $currency = $invoice->currency_code ?? \App\Models\Setting::get('currency_code', 'EUR');
        
        $sellerName = htmlspecialchars(\App\Models\Setting::get('company_name', 'Extrarent'));
        $sellerNif = \App\Models\Setting::get('company_nif', 'B12345678');
        
        $buyerName = $invoice->customer ? htmlspecialchars($invoice->customer->first_name . ' ' . $invoice->customer->last_name) : 'Consumidor Final';
        $buyerNif = $invoice->customer && $invoice->customer->nif_cif ? $invoice->customer->nif_cif : '00000000T';

        // Esqueleto básico y simplificado de FacturaE 3.2.1
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<fe:Facturae xmlns:fe="http://www.facturae.es/Facturae/2014/v3.2.1/Facturae">
    <FileHeader>
        <SchemaVersion>3.2.1</SchemaVersion>
        <Modality>I</Modality>
        <InvoiceIssuerType>EM</InvoiceIssuerType>
        <Batch>
            <BatchIdentifier>{$invoiceNumber}</BatchIdentifier>
            <InvoicesCount>1</InvoicesCount>
            <TotalInvoicesAmount>
                <TotalAmount>{$totalAmount}</TotalAmount>
            </TotalInvoicesAmount>
            <TotalOutstandingAmount>
                <TotalAmount>{$totalAmount}</TotalAmount>
            </TotalOutstandingAmount>
            <TotalExecutableAmount>
                <TotalAmount>{$totalAmount}</TotalAmount>
            </TotalExecutableAmount>
            <InvoiceCurrencyCode>{$currency}</InvoiceCurrencyCode>
        </Batch>
    </FileHeader>
    <Parties>
        <SellerParty>
            <TaxIdentification>
                <PersonTypeCode>J</PersonTypeCode>
                <ResidenceTypeCode>R</ResidenceTypeCode>
                <TaxIdentificationNumber>{$sellerNif}</TaxIdentificationNumber>
            </TaxIdentification>
            <LegalEntity>
                <CorporateName>{$sellerName}</CorporateName>
            </LegalEntity>
        </SellerParty>
        <BuyerParty>
            <TaxIdentification>
                <PersonTypeCode>F</PersonTypeCode>
                <ResidenceTypeCode>R</ResidenceTypeCode>
                <TaxIdentificationNumber>{$buyerNif}</TaxIdentificationNumber>
            </TaxIdentification>
            <Individual>
                <Name>{$buyerName}</Name>
                <FirstSurname>{$buyerName}</FirstSurname>
            </Individual>
        </BuyerParty>
    </Parties>
    <Invoices>
        <Invoice>
            <InvoiceHeader>
                <InvoiceNumber>{$invoiceNumber}</InvoiceNumber>
                <InvoiceDocumentType>FC</InvoiceDocumentType>
                <InvoiceClass>OO</InvoiceClass>
            </InvoiceHeader>
            <InvoiceIssueData>
                <IssueDate>{$issueDate}</IssueDate>
                <InvoiceCurrencyCode>{$currency}</InvoiceCurrencyCode>
                <TaxCurrencyCode>{$currency}</TaxCurrencyCode>
                <LanguageName>es</LanguageName>
            </InvoiceIssueData>
            <TaxesOutputs>
                <Tax>
                    <TaxTypeCode>01</TaxTypeCode>
                    <TaxRate>21.00</TaxRate>
                    <TaxableBase>
                        <TotalAmount>{$subtotal}</TotalAmount>
                    </TaxableBase>
                    <TaxAmount>
                        <TotalAmount>{$taxAmount}</TotalAmount>
                    </TaxAmount>
                </Tax>
            </TaxesOutputs>
            <InvoiceTotals>
                <TotalGrossAmount>{$subtotal}</TotalGrossAmount>
                <TotalGeneralDiscounts>0.00</TotalGeneralDiscounts>
                <TotalGeneralSurcharges>0.00</TotalGeneralSurcharges>
                <TotalGrossAmountBeforeTaxes>{$subtotal}</TotalGrossAmountBeforeTaxes>
                <TotalTaxOutputs>{$taxAmount}</TotalTaxOutputs>
                <TotalTaxesWithheld>0.00</TotalTaxesWithheld>
                <InvoiceTotal>{$totalAmount}</InvoiceTotal>
                <TotalOutstandingAmount>{$totalAmount}</TotalOutstandingAmount>
                <TotalExecutableAmount>{$totalAmount}</TotalExecutableAmount>
            </InvoiceTotals>
            <Items>
                <InvoiceLine>
                    <ItemDescription>Servicios facturados</ItemDescription>
                    <Quantity>1.00</Quantity>
                    <UnitOfMeasure>01</UnitOfMeasure>
                    <UnitPriceWithoutTax>{$subtotal}</UnitPriceWithoutTax>
                    <TotalCost>{$subtotal}</TotalCost>
                    <GrossAmount>{$subtotal}</GrossAmount>
                    <TaxesOutputs>
                        <Tax>
                            <TaxTypeCode>01</TaxTypeCode>
                            <TaxRate>21.00</TaxRate>
                            <TaxableBase>
                                <TotalAmount>{$subtotal}</TotalAmount>
                            </TaxableBase>
                            <TaxAmount>
                                <TotalAmount>{$taxAmount}</TotalAmount>
                            </TaxAmount>
                        </Tax>
                    </TaxesOutputs>
                </InvoiceLine>
            </Items>
        </Invoice>
    </Invoices>
</fe:Facturae>
XML;

        return $xml;
    }

    /**
     * Firma un XML utilizando un certificado .p12 (PKCS12) en el servidor.
     */
    public function signXMLServerSide(string $xmlContent, string $p12Content, string $password): ?string
    {
        $certs = [];
        if (!openssl_pkcs12_read($p12Content, $certs, $password)) {
            \Illuminate\Support\Facades\Log::error("Error leyendo certificado PKCS12.");
            return null;
        }

        $doc = new \DOMDocument();
        $doc->loadXML($xmlContent);

        $objDSig = new \RobRichards\XMLSecLibs\XMLSecurityDSig();
        $objDSig->setCanonicalMethod(\RobRichards\XMLSecLibs\XMLSecurityDSig::EXC_C14N);
        $objDSig->addReference(
            $doc,
            \RobRichards\XMLSecLibs\XMLSecurityDSig::SHA256,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature']
        );

        $objKey = new \RobRichards\XMLSecLibs\XMLSecurityKey(\RobRichards\XMLSecLibs\XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
        $objKey->loadKey($certs['pkey']);
        $objDSig->sign($objKey);
        $objDSig->add509Cert($certs['cert']);
        $objDSig->appendSignature($doc->documentElement);

        return $doc->saveXML();
    }
}
