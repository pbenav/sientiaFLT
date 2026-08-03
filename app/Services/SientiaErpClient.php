<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ErpSyncLog;

class SientiaErpClient
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;

    public function __construct()
    {
        $this->baseUrl = config('services.erp.base_url', 'http://localhost/sientiaERP/public/api');
        $this->apiKey = config('services.erp.api_key', '');
        $this->apiSecret = config('services.erp.api_secret', '');
    }

    public function get(string $endpoint, array $params = []): array
    {
        return $this->request('GET', $endpoint, $params);
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, $data);
    }

    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, $data);
    }

    public function delete(string $endpoint, array $data = []): array
    {
        return $this->request('DELETE', $endpoint, $data);
    }

    protected function request(string $method, string $endpoint, array $payload = []): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        $params = $method === 'GET' ? $payload : [];
        $body = $method === 'GET' ? [] : $payload;

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'X-API-Secret' => $this->apiSecret,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->$method($url, $body, $params);

            $statusCode = $response->status();
            $responseData = $response->json();

            $this->logSync($endpoint, $method, $payload, $responseData, $statusCode);

            if ($response->successful()) {
                return $responseData;
            }

            Log::warning('ERP API Error', [
                'method' => $method,
                'url' => $url,
                'status' => $statusCode,
                'response' => $responseData,
            ]);

            return ['success' => false, 'error' => $responseData ?? 'API Error', 'status' => $statusCode];
        } catch (\Exception $e) {
            Log::error('ERP Connection Error', [
                'method' => $method,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            $this->logSync($endpoint, $method, $payload, ['error' => $e->getMessage()], 0);

            return ['success' => false, 'error' => $e->getMessage(), 'status' => 0];
        }
    }

    protected function logSync(string $endpoint, string $method, array $request, array $response, int $statusCode): void
    {
        ErpSyncLog::create([
            'entity_type' => 'erp_api',
            'entity_id' => null,
            'action' => strtolower($method),
            'request_data' => $request,
            'response_data' => $response,
            'status_code' => $statusCode,
            'status' => $statusCode >= 200 && $statusCode < 300 ? 'success' : 'failed',
            'error_message' => $statusCode < 200 || $statusCode >= 300 ? 'API Error' : null,
        ]);
    }

    // Tercero (Customer) methods
    public function getTerceroById(int $id): array
    {
        return $this->get('terceros/' . $id);
    }

    public function createTercero(array $data): array
    {
        return $this->post('terceros', $data);
    }

    public function updateTercero(int $id, array $data): array
    {
        return $this->put('terceros/' . $id, $data);
    }

    public function getAllTerceros(array $params = []): array
    {
        return $this->get('terceros', $params);
    }

    // Producto (Vehicle/Product) methods
    public function getProductById(int $id): array
    {
        return $this->get('productos/' . $id);
    }

    public function createProduct(array $data): array
    {
        return $this->post('productos', $data);
    }

    public function updateProduct(int $id, array $data): array
    {
        return $this->put('productos/' . $id, $data);
    }

    public function getAllProducts(array $params = []): array
    {
        return $this->get('productos', $params);
    }

    // Documento (Booking/Invoice) methods
    public function getDocumentoById(int $id): array
    {
        return $this->get('documentos/' . $id);
    }

    public function createDocumento(array $data): array
    {
        return $this->post('documentos', $data);
    }

    public function updateDocumento(int $id, array $data): array
    {
        return $this->put('documentos/' . $id, $data);
    }

    public function getAllDocumentos(array $params = []): array
    {
        return $this->get('documentos', $params);
    }

    // Stock methods
    public function getStockByProduct(int $productId): array
    {
        return $this->get('stock/' . $productId);
    }

}
