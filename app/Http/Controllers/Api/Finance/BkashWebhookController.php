<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\WebhookPayment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BkashWebhookController extends Controller
{
    /**
     * Handle the incoming bKash webhook.
     */
    public function handle(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('bKash Webhook Received:', $request->all());

        $payload = $request->all();

        // Basic validation of payload structure (adjust based on bKash docs)
        // Typically bKash payload has: trxID, amount, customerMsisdn, payerReference, etc.
        $trxID = $payload['trxID'] ?? null;
        $amount = $payload['amount'] ?? 0;
        $msisdn = $payload['customerMsisdn'] ?? null;
        $reference = $payload['payerReference'] ?? null;
        $status = $payload['transactionStatus'] ?? 'Completed'; // status might be different in actual IPN

        if (!$trxID) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        // Try to find the client based on reference (username or client_id_display)
        $client = null;
        if ($reference) {
            $client = Client::where('username', $reference)
                ->orWhere('client_id_display', $reference)
                ->first();
        }

        // Create the webhook payment record
        $webhookPayment = WebhookPayment::create([
            'client_id' => $client ? $client->id : null,
            'transaction_id' => $trxID,
            'amount' => $amount,
            'payment_method' => 'bkash',
            'payment_from' => $msisdn,
            'status' => strtolower($status) === 'completed' ? 'completed' : 'pending',
            'gateway_response' => $payload,
            'processed_at' => now(),
        ]);

        // If client found, we could potentially automate more (e.g. update billing)
        // For now, we just record it as per "Webhook Payments" requirement.

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook processed successfully',
            'data' => [
                'id' => $webhookPayment->id,
                'client' => $client ? $client->username : 'Unknown'
            ]
        ]);
    }
}
