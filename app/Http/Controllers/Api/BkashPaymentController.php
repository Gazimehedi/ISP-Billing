<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\WebhookPayment;
use App\Services\BkashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BkashPaymentController extends Controller
{
    protected $bkashService;

    public function __construct(BkashService $bkashService)
    {
        $this->bkashService = $bkashService;
    }

    /**
     * Get client details by client code
     */
    public function getClientDetails($clientCode)
    {
        try {
            $client = Client::where('client_id_display', $clientCode)
                ->with(['package:id,name,price'])
                ->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid client code'
                ], 404);
            }

            // Calculate due amount based on billing cycle and payment history
            $monthlyFee = $client->monthly_fee ?? $client->package->price ?? 0;
            $dueAmount = 0;

            // Check if balance is negative (client owes money from previous months)
            if ($client->balance < 0) {
                $dueAmount = abs($client->balance);
            } else {
                // Check if client needs to pay for current billing period
                $today = now();
                $billingDate = $client->billing_date;
                $lastPaymentDate = $client->last_payment_date;
                
                // If payment status is unpaid, they owe for the current month
                if ($client->payment_status === 'unpaid') {
                    // Check if we're in a new billing cycle
                    if ($billingDate) {
                        $billingDay = \Carbon\Carbon::parse($billingDate)->day;
                        
                        // If last payment was in a previous month, client owes for current month
                        if ($lastPaymentDate) {
                            $lastPayment = \Carbon\Carbon::parse($lastPaymentDate);
                            
                            // If last payment was before current month, they owe
                            if ($lastPayment->month < $today->month || $lastPayment->year < $today->year) {
                                $dueAmount = $monthlyFee;
                            }
                        } else {
                            // No payment record, check if billing date has passed
                            $currentMonthBillingDate = \Carbon\Carbon::create(
                                $today->year,
                                $today->month,
                                min($billingDay, $today->daysInMonth)
                            );
                            
                            if ($today->greaterThanOrEqualTo($currentMonthBillingDate)) {
                                $dueAmount = $monthlyFee;
                            }
                        }
                    } else {
                        // No billing date set, assume monthly fee is due if unpaid
                        $dueAmount = $monthlyFee;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'client_code' => $client->client_id_display,
                    'name' => $client->name,
                    'mobile' => $client->mobile,
                    'package' => $client->package->name ?? 'N/A',
                    'monthly_fee' => $monthlyFee,
                    'due_amount' => $dueAmount,
                    'total_balance' => $client->balance,
                    'billing_date' => $billingDate ? \Carbon\Carbon::parse($billingDate)->format('Y-m-d') : null,
                    'payment_status' => $client->payment_status,
                    'last_payment_date' => $lastPaymentDate ? \Carbon\Carbon::parse($lastPaymentDate)->format('Y-m-d') : null,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Client Details Fetch Error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch client details'
            ], 500);
        }
    }

    /**
     * Initiate bKash payment
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'client_code' => 'required|string',
            'amount' => 'required|numeric|min:10',
        ]);

        try {
            $client = Client::where('client_id_display', $request->client_code)->first();

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid client code'
                ], 404);
            }

            // Generate unique invoice number
            $invoiceNumber = 'INV-' . strtoupper(Str::random(10));

            // Create payment with bKash
            $result = $this->bkashService->createPayment(
                $request->amount,
                $invoiceNumber,
                $request->client_code
            );

            if ($result['success']) {
                // Store payment info in session or cache for verification later
                session([
                    'bkash_payment_' . $result['paymentID'] => [
                        'client_id' => $client->id,
                        'client_code' => $request->client_code,
                        'amount' => $request->amount,
                        'invoice_number' => $invoiceNumber,
                    ]
                ]);

                return response()->json([
                    'success' => true,
                    'bkashURL' => $result['bkashURL'],
                    'paymentID' => $result['paymentID'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to initiate payment'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Payment Initiation Error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment'
            ], 500);
        }
    }

    /**
     * Handle bKash callback
     */
    public function callback(Request $request)
    {
        $paymentID = $request->paymentID;
        $status = $request->status;
        $clientCodeFromUrl = $request->input('client_code');

        Log::info('bKash Callback Received', [
            'paymentID' => $paymentID,
            'status' => $status,
            'client_code_param' => $clientCodeFromUrl,
            'all_params' => $request->all(),
        ]);

        if ($status === 'success' && $paymentID) {
            // Execute the payment
            $result = $this->bkashService->executePayment($paymentID);

            Log::info('bKash Payment Execution Result', [
                'paymentID' => $paymentID,
                'result' => $result,
            ]);

            if ($result['success']) {
                $paymentData = $result['data'];
                $sessionData = session('bkash_payment_' . $paymentID);

                Log::info('Session Data Retrieved', [
                    'paymentID' => $paymentID,
                    'sessionData' => $sessionData,
                ]);

                if ($sessionData) {
                    // Create webhook payment record
                    $webhookPayment = WebhookPayment::create([
                        'client_id' => $sessionData['client_id'],
                        'transaction_id' => $paymentData['trxID'],
                        'amount' => $paymentData['amount'],
                        'payment_method' => 'bkash',
                        'payment_from' => $paymentData['customerMsisdn'] ?? null,
                        'status' => 'completed',
                        'gateway_response' => $paymentData,
                        'processed_at' => now(),
                    ]);

                    // Update client balance and payment status
                    $client = Client::find($sessionData['client_id']);
                    if ($client) {
                        $client->balance = $client->balance + $paymentData['amount'];
                        $client->payment_status = 'paid';
                        $client->last_payment_date = now();
                        $client->save();

                        Log::info('Client Balance Updated', [
                            'client_id' => $client->id,
                            'new_balance' => $client->balance,
                        ]);
                    }

                    // Clear session
                    session()->forget('bkash_payment_' . $paymentID);

                    // Redirect to success page
                    return redirect('/pay/' . $sessionData['client_code'] . '?status=success&trxID=' . $paymentData['trxID']);
                } else {
                    // Session data not found - try to find client from payment data
                    Log::warning('Session data not found for payment', [
                        'paymentID' => $paymentID,
                        'paymentData' => $paymentData,
                    ]);

                    // Try to extract client code from payerReference
                    $clientCode = $paymentData['payerReference'] ?? null;
                    
                    if ($clientCode) {
                        $client = Client::where('client_id_display', $clientCode)->first();
                        
                        if ($client) {
                            // Create webhook payment record
                            WebhookPayment::create([
                                'client_id' => $client->id,
                                'transaction_id' => $paymentData['trxID'],
                                'amount' => $paymentData['amount'],
                                'payment_method' => 'bkash',
                                'payment_from' => $paymentData['customerMsisdn'] ?? null,
                                'status' => 'completed',
                                'gateway_response' => $paymentData,
                                'processed_at' => now(),
                            ]);

                            // Update client balance and payment status
                            $client->balance = $client->balance + $paymentData['amount'];
                            $client->payment_status = 'paid';
                            $client->last_payment_date = now();
                            $client->save();

                            return redirect('/pay/' . $clientCode . '?status=success&trxID=' . $paymentData['trxID']);
                        }
                    }

                    // If we still can't find the client, redirect to a generic success page
                    return redirect('/pay/unknown?status=success&trxID=' . ($paymentData['trxID'] ?? 'unknown'));
                }
            } else {
                Log::error('bKash Payment Execution Failed', [
                    'paymentID' => $paymentID,
                    'result' => $result,
                ]);
            }
        }

        // Redirect to failure page
        $sessionData = session('bkash_payment_' . $paymentID);
        $clientCode = $sessionData['client_code'] ?? $clientCodeFromUrl ?? 'unknown';
        
        Log::info('Payment Failed or Cancelled', [
            'paymentID' => $paymentID,
            'status' => $status,
            'clientCode' => $clientCode,
        ]);

        return redirect('/pay/' . $clientCode . '?status=failed');
    }

    /**
     * Execute payment (alternative method if needed)
     */
    public function executePayment(Request $request)
    {
        $request->validate([
            'paymentID' => 'required|string',
        ]);

        $result = $this->bkashService->executePayment($request->paymentID);

        return response()->json($result);
    }
}
