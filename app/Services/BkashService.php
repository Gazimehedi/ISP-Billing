<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BkashService
{
    private $baseUrl;
    private $appKey;
    private $appSecret;
    private $username;
    private $password;
    private $callbackUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.bkash.base_url');
        $this->appKey = config('services.bkash.app_key');
        $this->appSecret = config('services.bkash.app_secret');
        $this->username = config('services.bkash.username');
        $this->password = config('services.bkash.password');
        $this->callbackUrl = config('services.bkash.callback_url');
    }

    /**
     * Get bKash grant token (cached for 1 hour)
     */
    public function getToken()
    {
        return Cache::remember('bkash_token', 3600, function () {
            try {
                $response = Http::withHeaders([
                    'username' => $this->username,
                    'password' => $this->password,
                ])->post($this->baseUrl . '/tokenized/checkout/token/grant', [
                    'app_key' => $this->appKey,
                    'app_secret' => $this->appSecret,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('bKash Token Generated', ['token' => substr($data['id_token'] ?? '', 0, 20) . '...']);
                    return $data['id_token'] ?? null;
                }

                Log::error('bKash Token Generation Failed', ['response' => $response->body()]);
                return null;
            } catch (\Exception $e) {
                Log::error('bKash Token Exception', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }

    /**
     * Create a payment request
     */
    public function createPayment($amount, $invoiceNumber, $clientCode)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Failed to get bKash token'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/tokenized/checkout/create', [
                'mode' => '0011',
                'payerReference' => $clientCode,
                'callbackURL' => $this->callbackUrl . '?client_code=' . $clientCode,
                'amount' => $amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $invoiceNumber,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('bKash Payment Created', ['paymentID' => $data['paymentID'] ?? 'N/A']);
                return [
                    'success' => true,
                    'data' => $data,
                    'bkashURL' => $data['bkashURL'] ?? null,
                    'paymentID' => $data['paymentID'] ?? null,
                ];
            }

            Log::error('bKash Payment Creation Failed', ['response' => $response->body()]);
            return ['success' => false, 'message' => 'Failed to create payment'];
        } catch (\Exception $e) {
            Log::error('bKash Payment Creation Exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Execute a payment
     */
    public function executePayment($paymentID)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Failed to get bKash token'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/tokenized/checkout/execute', [
                'paymentID' => $paymentID,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('bKash Payment Executed', ['trxID' => $data['trxID'] ?? 'N/A']);
                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            Log::error('bKash Payment Execution Failed', ['response' => $response->body()]);
            return ['success' => false, 'message' => 'Failed to execute payment'];
        } catch (\Exception $e) {
            Log::error('bKash Payment Execution Exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Query payment status
     */
    public function queryPayment($paymentID)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['success' => false, 'message' => 'Failed to get bKash token'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
                'X-APP-Key' => $this->appKey,
            ])->post($this->baseUrl . '/tokenized/checkout/payment/status', [
                'paymentID' => $paymentID,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return ['success' => false, 'message' => 'Failed to query payment'];
        } catch (\Exception $e) {
            Log::error('bKash Payment Query Exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
