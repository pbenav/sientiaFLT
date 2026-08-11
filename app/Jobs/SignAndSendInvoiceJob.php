<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\FacturaEService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SignAndSendInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle(FacturaEService $facturaEService): void
    {
        try {
            // 1. Generar XML base
            $xml = $facturaEService->generateXML($this->invoice);
            
            // 2. Obtener certificado y contraseña desde la BD (Settings)
            $certPathRelative = \App\Models\Setting::get('verifactu_cert_path');
            $certPassword = \App\Models\Setting::get('verifactu_cert_password');

            if (!$certPathRelative || !Storage::disk('local')->exists($certPathRelative)) {
                Log::warning("Certificado no configurado o no encontrado en Settings. No se puede automatizar la firma de la factura {$this->invoice->invoice_number}.");
                return;
            }

            $certStore = Storage::disk('local')->get($certPathRelative);
            
            // 3. Firmar XML
            $signedXml = $facturaEService->signXMLServerSide($xml, $certStore, $certPassword);
            
            if (!$signedXml) {
                Log::error("Fallo al firmar la factura {$this->invoice->invoice_number}.");
                return;
            }

            // 4. Guardar archivo firmado
            $fileName = 'facturas_firmadas/factura_' . $this->invoice->invoice_number . '_signed.xml';
            Storage::disk('local')->put($fileName, $signedXml);
            
            // 5. Actualizar estado
            $this->invoice->update([
                'status' => 'sent',
                'pdf_path' => $fileName,
                'erp_sync_status' => 'signed_and_ready'
            ]);

            // 6. (Opcional) Aquí iría la llamada cURL a la API de Veri*Factu de Hacienda.
            Log::info("Factura {$this->invoice->invoice_number} firmada y lista para VeriFactu.");

        } catch (\Exception $e) {
            Log::error("Error en SignAndSendInvoiceJob: " . $e->getMessage());
        }
    }
}
