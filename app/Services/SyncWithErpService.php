<?php

namespace App\Services;

use App\Models\Booking;
use App\Services\SientiaErpClient;

class SyncWithErpService
{
    protected SientiaErpClient $erpClient;

    public function __construct(SientiaErpClient $erpClient)
    {
        $this->erpClient = $erpClient;
    }

    public function syncVehicleToErp(\App\Models\Vehicle $vehicle): void
    {
        $data = [
            'name' => $vehicle->name,
            'sku' => $vehicle->sku,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'year' => $vehicle->year,
            'license_plate' => $vehicle->license_plate,
            'category' => $vehicle->category,
            'fuel_type' => $vehicle->fuel_type,
            'transmission' => $vehicle->transmission,
            'seats' => $vehicle->seats,
            'is_active' => $vehicle->is_active,
            'is_available' => $vehicle->is_available,
        ];

        if ($vehicle->erp_product_id) {
            $this->erpClient->put('products/' . $vehicle->erp_product_id, $data);
        } else {
            $response = $this->erpClient->post('products', $data);
            if (isset($response['id'])) {
                $vehicle->update(['erp_product_id' => $response['id'], 'erp_sync_status' => 'synced']);
            }
        }
    }

    public function syncCustomerToErp(\App\Models\Customer $customer): void
    {
        $data = [
            'name' => $customer->first_name . ' ' . $customer->last_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'company' => $customer->company_name,
            'nif_cif' => $customer->nif_cif,
            'address' => $customer->address,
            'city' => $customer->city,
            'postal_code' => $customer->postal_code,
            'country' => $customer->country,
            'is_active' => $customer->is_active,
        ];

        if ($customer->erp_tercio_id) {
            $this->erpClient->put('tercios/' . $customer->erp_tercio_id, $data);
        } else {
            $response = $this->erpClient->post('tercios', $data);
            if (isset($response['id'])) {
                $customer->update(['erp_tercio_id' => $response['id'], 'erp_sync_status' => 'synced']);
            }
        }
    }

    public function syncBookingToErp(Booking $booking): void
    {
        $data = [
            'customer_id' => $booking->customer->erp_tercio_id ?? $booking->customer_id,
            'vehicle_id' => $booking->vehicle->erp_product_id ?? $booking->vehicle_id,
            'start_date' => $booking->start_date->format('Y-m-d H:i:s'),
            'end_date' => $booking->end_date->format('Y-m-d H:i:s'),
            'total' => $booking->total_amount,
            'status' => $booking->status,
            'booking_number' => $booking->booking_number,
        ];

        if ($booking->erp_documento_id) {
            $this->erpClient->put('documentos/' . $booking->erp_documento_id, $data);
        } else {
            $response = $this->erpClient->post('documentos', $data);
            if (isset($response['id'])) {
                $booking->update(['erp_documento_id' => $response['id'], 'erp_sync_status' => 'synced']);
            }
        }
    }
}
