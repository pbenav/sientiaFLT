<?php

namespace App\Http\Controllers;

use App\Services\PDFService;

class PDFController extends Controller
{
    protected $pdfService;

    public function __construct(PDFService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function factura(int $invoiceId)
    {
        return $this->pdfService->generateFacturaPDF($invoiceId);
    }

    public function ticket(int $bookingId)
    {
        return $this->pdfService->generateTicketPDF($bookingId);
    }

    public function contract(int $bookingId)
    {
        return $this->pdfService->generateContractPDF($bookingId);
    }
}
