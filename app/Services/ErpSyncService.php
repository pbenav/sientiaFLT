<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Log;

class ErpSyncService implements \App\Interfaces\ErpClientInterface
{
    protected SientiaErpClient $erpClient;

    public function __construct(SientiaErpClient $erpClient)
    {
        $this->erpClient = $erpClient;
    }

    // ── Vehicle Sync ──────────────────────────────────────────────

    public function syncVehicle(Vehicle $vehicle): void
    {
        try {
            if ($vehicle->erp_product_id) {
                $this->erpClient->updateProduct($vehicle->erp_product_id, $this->buildVehiclePayload($vehicle));
            } else {
                $response = $this->erpClient->createProduct($this->buildVehiclePayload($vehicle));
                if (isset($response['id'])) {
                    $vehicle->update(['erp_product_id' => $response['id'], 'erp_sync_status' => 'synced']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Vehicle ERP sync failed', [
                'vehicle_id' => $vehicle->id,
                'error' => $e->getMessage(),
            ]);
            $vehicle->update(['erp_sync_status' => 'failed']);
        }
    }

    private function buildVehiclePayload(Vehicle $vehicle): array
    {
        return [
            'nombre' => $vehicle->name,
            'sku' => $vehicle->sku,
            'marca' => $vehicle->brand,
            'modelo' => $vehicle->model,
            'anio' => $vehicle->year,
            'placa' => $vehicle->license_plate,
            'categoria' => $vehicle->category,
            'tipo_combustible' => $vehicle->fuel_type,
            'transmision' => $vehicle->transmission,
            'asientos' => $vehicle->seats,
            'activo' => $vehicle->is_active,
            'disponible' => $vehicle->is_available,
        ];
    }

    // ── Customer Sync ─────────────────────────────────────────────

    public function syncCustomer(Customer $customer): void
    {
        try {
            $payload = $this->buildCustomerPayload($customer);

            if ($customer->erp_tercio_id) {
                $this->erpClient->updateTercero($customer->erp_tercio_id, $payload);
            } else {
                $response = $this->erpClient->createTercero($payload);
                if (isset($response['id'])) {
                    $customer->update(['erp_tercio_id' => $response['id'], 'erp_sync_status' => 'synced']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Customer ERP sync failed', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);
            $customer->update(['erp_sync_status' => 'failed']);
        }
    }

    private function buildCustomerPayload(Customer $customer): array
    {
        return [
            'nombre' => $customer->first_name . ' ' . $customer->last_name,
            'email' => $customer->email,
            'telefono' => $customer->phone,
            'nif_cif' => $customer->nif_cif,
            'razon_social' => $customer->company_name,
            'direccion' => $customer->address,
            'ciudad' => $customer->city,
            'provincia' => $customer->province,
            'codigo_postal' => $customer->postal_code,
            'pais' => $customer->country,
            'activo' => $customer->is_active,
        ];
    }

    // ── Booking Sync ──────────────────────────────────────────────

    public function syncBooking(Booking $booking): void
    {
        try {
            $payload = $this->buildBookingPayload($booking);

            if ($booking->erp_documento_id) {
                $this->erpClient->updateDocumento($booking->erp_documento_id, $payload);
            } else {
                $response = $this->erpClient->createDocumento($payload);
                if (isset($response['id'])) {
                    $booking->update(['erp_documento_id' => $response['id'], 'erp_sync_status' => 'synced']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Booking ERP sync failed', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
            $booking->update(['erp_sync_status' => 'failed']);
        }
    }

    private function buildBookingPayload(Booking $booking): array
    {
        return [
            'cliente_id' => $booking->customer->erp_tercio_id ?? $booking->customer_id,
            'vehiculo_id' => $booking->vehicle->erp_product_id ?? $booking->vehicle_id,
            'fecha_inicio' => $booking->start_date->format('Y-m-d H:i:s'),
            'fecha_fin' => $booking->end_date->format('Y-m-d H:i:s'),
            'total' => $booking->total_amount,
            'estado' => $booking->status,
            'numero_reserva' => $booking->booking_number,
        ];
    }

    // ── Bulk Sync ─────────────────────────────────────────────────

    public function syncAllPendingVehicles(): void
    {
        Vehicle::where('erp_sync_status', '!=', 'synced')->each(fn ($v) => $this->syncVehicle($v));
    }

    public function syncAllPendingCustomers(): void
    {
        Customer::where('erp_sync_status', '!=', 'synced')->each(fn ($c) => $this->syncCustomer($c));
    }

    public function syncAllPendingBookings(): void
    {
        Booking::where('erp_sync_status', '!=', 'synced')->each(fn ($b) => $this->syncBooking($b));
    }
}
