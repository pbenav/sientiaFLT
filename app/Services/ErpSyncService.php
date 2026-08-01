<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Booking;
use App\Services\SientiaErpClient;
use Illuminate\Support\Facades\Log;

class ErpSyncService
{
    protected SientiaErpClient $erpClient;

    public function __construct(SientiaErpClient $erpClient)
    {
        $this->erpClient = $erpClient;
    }

    public function syncVehicle(Vehicle $vehicle): void
    {
        try {
            if ($vehicle->erp_product_id) {
                $this->erpClient->updateProduct($vehicle->erp_product_id, [
                    'nombre' => $vehicle->name,
                    'sku' => $vehicle->sku,
                    'marca' => $vehicle->brand,
                    'modelo' => $vehicle->model,
                    'activo' => $vehicle->is_active,
                    'disponible' => $vehicle->is_available,
                ]);
            } else {
                $response = $this->erpClient->createProduct([
                    'nombre' => $vehicle->name,
                    'sku' => $vehicle->sku,
                    'marca' => $vehicle->brand,
                    'modelo' => $vehicle->model,
                    'activo' => $vehicle->is_active,
                    'disponible' => $vehicle->is_available,
                ]);

                if (isset($response['id'])) {
                    $vehicle->update(['erp_product_id' => $response['id'], 'erp_sync_status' => 'synced']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Vehicle ERP sync failed', ['vehicle_id' => $vehicle->id, 'error' => $e->getMessage()]);
            $vehicle->update(['erp_sync_status' => 'failed']);
        }
    }

    public function syncCustomer(Customer $customer): void
    {
        try {
            if ($customer->erp_tercio_id) {
                $this->erpClient->updateTercero($customer->erp_tercio_id, [
                    'nombre' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'telefono' => $customer->phone,
                    'nif_cif' => $customer->nif_cif,
                    'razon_social' => $customer->company_name,
                    'activo' => $customer->is_active,
                ]);
            } else {
                $response = $this->erpClient->createTercero([
                    'nombre' => $customer->first_name . ' ' . $customer->last_name,
                    'email' => $customer->email,
                    'telefono' => $customer->phone,
                    'nif_cif' => $customer->nif_cif,
                    'razon_social' => $customer->company_name,
                    'activo' => $customer->is_active,
                ]);

                if (isset($response['id'])) {
                    $customer->update(['erp_tercio_id' => $response['id'], 'erp_sync_status' => 'synced']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Customer ERP sync failed', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            $customer->update(['erp_sync_status' => 'failed']);
        }
    }

    public function syncBooking(Booking $booking): void
    {
        try {
            if ($booking->erp_documento_id) {
                $this->erpClient->updateDocumento($booking->erp_documento_id, [
                    'cliente_id' => $booking->customer->erp_tercio_id,
                    'vehiculo_id' => $booking->vehicle->erp_product_id,
                    'fecha_inicio' => $booking->start_date->format('Y-m-d H:i:s'),
                    'fecha_fin' => $booking->end_date->format('Y-m-d H:i:s'),
                    'total' => $booking->total_amount,
                    'estado' => $booking->status,
                ]);
            } else {
                $response = $this->erpClient->createDocumento([
                    'cliente_id' => $booking->customer->erp_tercio_id,
                    'vehiculo_id' => $booking->vehicle->erp_product_id,
                    'fecha_inicio' => $booking->start_date->format('Y-m-d H:i:s'),
                    'fecha_fin' => $booking->end_date->format('Y-m-d H:i:s'),
                    'total' => $booking->total_amount,
                    'estado' => $booking->status,
                ]);

                if (isset($response['id'])) {
                    $booking->update(['erp_documento_id' => $response['id'], 'erp_sync_status' => 'synced']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Booking ERP sync failed', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            $booking->update(['erp_sync_status' => 'failed']);
        }
    }

    public function syncAllPendingVehicles(): void
    {
        Vehicle::where('erp_sync_status', '!=', 'synced')->get()->each(function ($vehicle) {
            $this->syncVehicle($vehicle);
        });
    }

    public function syncAllPendingCustomers(): void
    {
        Customer::where('erp_sync_status', '!=', 'synced')->get()->each(function ($customer) {
            $this->syncCustomer($customer);
        });
    }

    public function syncAllPendingBookings(): void
    {
        Booking::where('erp_sync_status', '!=', 'synced')->get()->each(function ($booking) {
            $this->syncBooking($booking);
        });
    }
}
