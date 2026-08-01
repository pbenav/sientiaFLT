<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceService
{
    public function generateInvoice(Booking $booking): Invoice
    {
        return DB::transaction(function () use ($booking) {
            $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::whereYear('created_at', now()->year)->count() + 1, 5, '0', Str::PAD_LEFT);

            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'invoice_number' => $invoiceNumber,
                'type' => 'invoice',
                'issue_date' => now(),
                'due_date' => now()->addDays(config('extrarent.invoice_due_days', 30)),
                'subtotal' => $booking->subtotal,
                'tax_amount' => $booking->tax_amount,
                'total_amount' => $booking->total_amount,
                'currency_code' => $booking->currency_code,
                'status' => 'draft',
            ]);

            $this->generatePdf($invoice);

            return $invoice;
        });
    }

    protected function generatePdf(Invoice $invoice): void
    {
        $customer = $invoice->customer;
        $booking = $invoice->booking;
        $vehicle = $booking->vehicle;

        $html = view('invoices.template', compact('invoice', 'customer', 'booking', 'vehicle'))->render();

        $filename = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::disk('public')->put($filename, $html);

        $invoice->update(['pdf_path' => $filename]);
    }
}
