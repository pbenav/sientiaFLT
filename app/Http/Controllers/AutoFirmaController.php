<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\FacturaEService;
use Illuminate\Http\Request;

class AutoFirmaController extends Controller
{
    public function showSignPage(int $invoiceId, FacturaEService $facturaEService)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        
        // Generate raw XML for FacturaE
        $xml = $facturaEService->generateXML($invoice);
        
        // Base64 encode the document for AutoFirma
        $base64Document = base64_encode($xml);

        return view('autofirma.sign', compact('invoice', 'base64Document'));
    }

    public function saveSignature(Request $request, int $invoiceId)
    {
        $request->validate([
            'signature_base64' => 'required|string',
        ]);

        $invoice = Invoice::findOrFail($invoiceId);
        
        // En un caso real, guardaríamos el XML firmado en Storage
        $signedXml = base64_decode($request->signature_base64);
        
        $fileName = 'facturas_firmadas/factura_' . $invoice->invoice_number . '_signed.xml';
        \Illuminate\Support\Facades\Storage::disk('local')->put($fileName, $signedXml);
        
        // Actualizamos el estado
        $invoice->update([
            'status' => 'sent', // Marcamos como enviada/firmada
            'pdf_path' => $fileName, // Reutilizamos este campo o creamos uno nuevo para la ruta XML
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Factura firmada y guardada correctamente.',
            'redirect' => '/admin/invoices'
        ]);
    }
}
