<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymobService
{
    protected $baseUrl;
    protected $apiKey;
    protected $integrationId;

    public function __construct()
    {
        $this->baseUrl = env('PAYMOB_BASE_URL');
        $this->apiKey = env('PAYMOB_API_KEY');
        $this->integrationId = env('PAYMOB_INTEGRATION_ID');
    }

    public function authenticate()
    {
        $response = Http::post("$this->baseUrl/auth/tokens", [
            'api_key' => $this->apiKey,
        ]);

        return $response->json()['token'] ?? null;
    }

    public function registerOrder($authToken, $amountCents, $merchantOrderId)
    {
        $response = Http::post("$this->baseUrl/ecommerce/orders", [
            'auth_token' => $authToken,
            'delivery_needed' => 'false',
            'amount_cents' => $amountCents,
            'currency' => 'EGP',
            'merchant_order_id' => $merchantOrderId,
            'items' => []
        ]);

        $data = $response->json();

        if (!$response->successful() || !isset($data['id'])) {
            throw new \Exception('Error registering order with Paymob: ' . json_encode($data));
        }

        return $data['id']; // رجع فقط ID الطلب من Paymob
    }


    public function getPaymentKey($authToken, $amountCents, $orderId, $billingData)
    {
        $response = Http::post("$this->baseUrl/acceptance/payment_keys", [
            'auth_token' => $authToken,
            'amount_cents' => $amountCents,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $this->integrationId,
        ]);

        return $response->json()['token'] ?? null;
    }
}
