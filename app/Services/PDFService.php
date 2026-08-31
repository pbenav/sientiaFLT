<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;

class PDFService
{
    private function getPdf(): \Barryvdh\DomPDF\PDF
    {
        $dompdf = new \Dompdf\Dompdf(app('config')->get('dompdf.options') ?: []);
        $path = realpath(app('config')->get('dompdf.public_path') ?: base_path('public'));
        if ($path) {
            $dompdf->setBasePath($path);
        }
        return new \Barryvdh\DomPDF\PDF($dompdf, app('config'), app('files'), app('view'));
    }

    public function generateFacturaPDF(int $invoiceId): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $invoice = Invoice::with(['booking.customer', 'booking.vehicle'])->findOrFail($invoiceId);
        
        $html = $this->generateFacturaHTML($invoice);
        
        $pdf = $this->getPdf()->loadHTML($html)
            ->setPaper('a4')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            
        $filename = 'factura_' . $invoice->invoice_number . '.pdf';
        
        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function generateTicketPDF(int $bookingId)
    {
        $booking = Booking::with(['customer', 'vehicle', 'user'])->findOrFail($bookingId);
        
        $html = $this->generateTicketHTML($booking);
        
        $pdf = $this->getPdf()->loadHTML($html)
            ->setPaper([0, 0, 226.77, 400]) // 80mm thermal
            ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            
        return $pdf->stream('ticket_' . $booking->booking_number . '.pdf');
    }

    public function generateContractPDF(int $bookingId)
    {
        $booking = Booking::with(['customer', 'vehicle', 'unit'])->findOrFail($bookingId);

        $data = [];
        $data['booking'] = $booking;
        $data['clauses'] = \App\Models\Setting::get('contract_clauses', "1. El arrendatario asume toda la responsabilidad...");

        $pdf = $this->getPdf()->loadView('pdf.contrato', $data);
        return $pdf->stream('contrato_' . $booking->booking_number . '.pdf');
    }

    public function generateFacturaHTML(Invoice $invoice): string
    {
        $companyName = \App\Models\Setting::get('company_name', 'Extrarent');
        $companyNif = \App\Models\Setting::get('company_nif', 'B12345678');
        $companyAddress = \App\Models\Setting::get('company_address', 'Calle Ejemplo 1, Madrid');
        $companyPhone = \App\Models\Setting::get('company_phone', '+34 900 000 000');
        $companyEmail = \App\Models\Setting::get('company_email', 'info@extrarent.com');
        
        return view('pdf.documento', [
            'doc' => $invoice,
            'companyName' => $companyName,
            'companyNif' => $companyNif,
            'companyAddress' => $companyAddress,
            'companyPhone' => $companyPhone,
            'companyEmail' => $companyEmail,
        ])->render();
    }

    public function generateTicketHTML(Booking $booking): string
    {
        $companyName = \App\Models\Setting::get('company_name', 'Extrarent');
        $companyNif = \App\Models\Setting::get('company_nif', 'B12345678');
        
        return view('pdf.ticket_pos', [
            'ticket' => $booking,
            'width' => '80mm',
            'companyName' => $companyName,
            'companyNif' => $companyNif,
        ])->render();
    }
}
