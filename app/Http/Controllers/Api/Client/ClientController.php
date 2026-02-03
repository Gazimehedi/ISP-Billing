<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\MikrotikRouter;
use App\Models\Package;
use App\Services\RouterOSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index(Request $request)
    {
        $query = Client::query()->with(['zone', 'subZone', 'package', 'box', 'serviceProfile']);

        // Search by name, username, ID, or mobile
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('client_id_display', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        // Advanced Filters
        if ($request->filled('server_id')) $query->where('server_id', $request->server_id);
        if ($request->filled('zone_id')) $query->where('zone_id', $request->zone_id);
        if ($request->filled('sub_zone_id')) $query->where('sub_zone_id', $request->sub_zone_id);
        if ($request->filled('box_id')) $query->where('box_id', $request->box_id);
        if ($request->filled('package_id')) $query->where('package_id', $request->package_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);
        if ($request->filled('billing_type')) $query->where('billing_type', $request->billing_type);
        if ($request->filled('client_type_id')) $query->where('client_type', $request->client_type_id); // Changed to match frontend

        // Date Range Filters
        if ($request->filled('billing_cycle_start') && $request->filled('billing_cycle_end')) {
            $query->whereBetween('billing_date', [$request->billing_cycle_start, $request->billing_cycle_end]);
        }
        if ($request->filled('expire_date_start') && $request->filled('expire_date_end')) {
            $query->whereBetween('expire_date', [$request->expire_date_start, $request->expire_date_end]);
        }
        if ($request->filled('promise_date_start') && $request->filled('promise_date_end')) {
            $query->whereBetween('promise_date', [$request->promise_date_start, $request->promise_date_end]);
        }

        // Days Left Filters
        if ($request->filled('expire_date_left')) {
            $days = (int) $request->expire_date_left;
            // Clients expiring within X days
            $query->whereRaw('DATEDIFF(expire_date, NOW()) <= ?', [$days])
                  ->where('expire_date', '>=', now());
        }
        if ($request->filled('billing_cycle_left')) {
            $days = (int) $request->billing_cycle_left;
            $query->whereRaw('DATEDIFF(billing_date, NOW()) <= ?', [$days])
                  ->where('billing_date', '>=', now());
        }
        if ($request->filled('promise_date_left')) {
            $days = (int) $request->promise_date_left;
            $query->whereRaw('DATEDIFF(promise_date, NOW()) <= ?', [$days])
                  ->where('promise_date', '>=', now());
        }

        // Aggregates for header summary
        // Note: For simplicity, balance = monthly_fee - amount_paid (not fully implemented yet)
        // Here we just aggregate the monthly_fee as 'bill' and a dummy balance for now.
        $summary = [
            'total_bill' => (clone $query)->sum('monthly_fee'),
            'total_balance' => (clone $query)->sum('monthly_fee_due'), // Using monthly_fee_due as balance for demo
        ];

        $clients = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json([
            'clients' => $clients,
            'summary' => $summary
        ]);
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request, RouterOSService $routerData)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'username' => 'required|string|unique:clients,username',
            'password' => 'required|string|min:4',
            'zone_id' => 'required|exists:zones,id',
            'package_id' => 'required|exists:packages,id',
            'protocol_type' => 'required|string',
            'monthly_fee' => 'required|numeric|min:0',
            'joining_date' => 'required|date',
            'billing_start_month' => 'required|string',
            'profile_pic' => 'nullable|image|max:2048',
            'nid_pic' => 'nullable|image|max:2048',
            'res_form_pic' => 'nullable|image|max:2048',
            'employee_id' => 'nullable|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['profile_pic', 'nid_pic', 'res_form_pic']);
            
            // Boolean handling for form-data (which often sends strings)
            $data['is_vip'] = $request->is_vip === 'true' || $request->is_vip === 1;
            $data['is_same_address'] = $request->is_same_address === 'true' || $request->is_same_address === 1;
            $data['send_greeting_sms'] = $request->send_greeting_sms === 'true' || $request->send_greeting_sms === 1;

            // Handle File Uploads
            if ($request->hasFile('profile_pic')) {
                $data['profile_pic'] = $request->file('profile_pic')->store('clients/profiles', 'public');
            }
            if ($request->hasFile('nid_pic')) {
                $data['nid_pic'] = $request->file('nid_pic')->store('clients/nids', 'public');
            }
            if ($request->hasFile('res_form_pic')) {
                $data['res_form_pic'] = $request->file('res_form_pic')->store('clients/res_forms', 'public');
            }

            // Auto-generate display ID
            $lastId = Client::max('id') ?? 1000;
            $data['client_id_display'] = 'CL-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

            $client = Client::create($data);

            DB::commit();

            // --- Mikrotik Sync Start ---
            if ($client->server_id && ($client->connection_type === 'pppoe' || $client->connection_type === 'PPPoE')) {
                try {
                    $router = MikrotikRouter::find($client->server_id);
                    if ($router) {
                        $decryptedPassword = Crypt::decryptString($router->password);
                        if ($routerData->connect($router->ip_address, $router->username, $decryptedPassword, $router->port)) {
                            
                            // Get package to use as profile
                            $package = Package::find($client->package_id);
                            $profileName = $package ? $package->name : 'default';
                            
                            // Check if secret exists to avoid error
                            $exists = $routerData->comm('/ppp/secret/print', [
                                '?name' => $client->username
                            ]);

                            if (empty($exists)) {
                                $params = [
                                    'name' => $client->username,
                                    'password' => $request->password,
                                    'service' => 'pppoe',
                                    'profile' => $profileName,
                                    'comment' => 'Added from ISP Billing: ' . $client->name . ' (' . $client->client_id_display . ')'
                                ];

                                // Add remote-address if exists
                                if ($client->remote_address) {
                                    $params['remote-address'] = $client->remote_address;
                                }

                                $routerData->comm('/ppp/secret/add', $params);
                            }

                            $routerData->disconnect();
                        } else {
                            Log::error("Failed to connect to Mikrotik: " . $routerData->getError());
                        }
                    }
                } catch (\Exception $ex) {
                    Log::error("Mikrotik Sync Error: " . $ex->getMessage());
                    // We do not fail the request if Mikrotik sync fails, but we assume it's "mostly" fine or will be synced later
                }
            }
            // --- Mikrotik Sync End ---
            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
                'data' => $client
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        return response()->json($client->load(['zone', 'subZone', 'package', 'box', 'serviceProfile', 'employee', 'server']));
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, Client $client, RouterOSService $routerData)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|unique:clients,username,' . $client->id,
            'mobile' => 'sometimes|string|max:20',
            'profile_pic' => 'nullable|image|max:2048',
            'nid_pic' => 'nullable|image|max:2048',
            'res_form_pic' => 'nullable|image|max:2048',
            'status' => 'sometimes|in:active,inactive,expired,suspended',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['profile_pic', 'nid_pic', 'res_form_pic']);

            // Handle Booleans
            if ($request->has('is_vip')) $data['is_vip'] = $request->is_vip === 'true' || $request->is_vip === 1;
            if ($request->has('is_same_address')) $data['is_same_address'] = $request->is_same_address === 'true' || $request->is_same_address === 1;
            if ($request->has('send_greeting_sms')) $data['send_greeting_sms'] = $request->send_greeting_sms === 'true' || $request->send_greeting_sms === 1;

            // File Replacements
            if ($request->hasFile('profile_pic')) {
                if ($client->profile_pic) \Storage::disk('public')->delete($client->profile_pic);
                $data['profile_pic'] = $request->file('profile_pic')->store('clients/profiles', 'public');
            }
            if ($request->hasFile('nid_pic')) {
                if ($client->nid_pic) \Storage::disk('public')->delete($client->nid_pic);
                $data['nid_pic'] = $request->file('nid_pic')->store('clients/nids', 'public');
            }
            if ($request->hasFile('res_form_pic')) {
                if ($client->res_form_pic) \Storage::disk('public')->delete($client->res_form_pic);
                $data['res_form_pic'] = $request->file('res_form_pic')->store('clients/res_forms', 'public');
            }

            $client->update($data);

            DB::commit();

            // --- Mikrotik Sync Start (Update) ---
            // --- Mikrotik Sync Start (Update) ---
            if ($client->server_id && ($client->connection_type === 'pppoe' || $client->connection_type === 'PPPoE')) {
                try {
                    $router = MikrotikRouter::find($client->server_id);
                    if ($router) {
                        $decryptedPassword = Crypt::decryptString($router->password);
                        if ($routerData->connect($router->ip_address, $router->username, $decryptedPassword, $router->port)) {
                            
                            // Find secret by ID or Name
                            $secret = $routerData->comm('/ppp/secret/print', [
                                '?name' => $client->username
                            ]);

                            if (!empty($secret)) {
                                $secretId = $secret[0]['.id'];
                                $updateData = [];

                                // Update Password if changed
                                if ($request->filled('password')) {
                                    $updateData['password'] = $request->password;
                                }

                                // Update Profile if package changed
                                if ($request->filled('package_id')) {
                                    $package = Package::find($request->package_id);
                                    if ($package) {
                                        $updateData['profile'] = $package->name;
                                    }
                                }

                                // Update remote-address
                                if ($request->filled('remote_address')) {
                                    $updateData['remote-address'] = $request->remote_address;
                                }

                                // Update Status (Enable/Disable)
                                // If status is passed in request, use it. Otherwise use client's current status.
                                $currentStatus = $request->status ?? $client->status;
                                
                                if ($currentStatus === 'active') {
                                    $routerData->comm('/ppp/secret/enable', ['.id' => $secretId]);
                                } else {
                                    $routerData->comm('/ppp/secret/disable', ['.id' => $secretId]);
                                }

                                if (!empty($updateData)) {
                                    $updateData['.id'] = $secretId;
                                    $routerData->comm('/ppp/secret/set', $updateData);
                                }
                            }

                            $routerData->disconnect();
                        } else {
                            Log::error("Failed to connect to Mikrotik during update: " . $routerData->getError());
                        }
                    }
                } catch (\Exception $ex) {
                    Log::error("Mikrotik Sync Update Error: " . $ex->getMessage());
                }
            }
            // --- Mikrotik Sync End ---

            return response()->json([
                'status' => 'success',
                'message' => 'Client updated successfully',
                'data' => $client
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update client: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Client $client, RouterOSService $routerData)
    {
        try {
            // --- Mikrotik Sync Start (Delete) ---
            if ($client->server_id && ($client->connection_type === 'pppoe' || $client->connection_type === 'PPPoE')) {
                try {
                    $router = MikrotikRouter::find($client->server_id);
                    if ($router) {
                        $decryptedPassword = Crypt::decryptString($router->password);
                        if ($routerData->connect($router->ip_address, $router->username, $decryptedPassword, $router->port)) {
                            
                            $secret = $routerData->comm('/ppp/secret/print', [
                                '?name' => $client->username
                            ]);

                            if (!empty($secret)) {
                                $routerData->comm('/ppp/secret/remove', ['.id' => $secret[0]['.id']]);
                            }

                            $routerData->disconnect();
                        }
                    }
                } catch (\Exception $ex) {
                    Log::error("Mikrotik Sync Destroy Error: " . $ex->getMessage());
                }
            }
            // --- Mikrotik Sync End ---

            $client->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Client deleted successfully'
            ]);

        } catch (\Exception $e) {
             return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete client: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get billing history for the client.
     */
    public function billingHistory(Client $client)
    {
        // 1. Invoices
        $invoices = \App\Models\Billing::where('client_id', $client->id)
            ->get()
            ->map(function ($item) {
                $date = $item->generated_date ? $item->generated_date : $item->created_at;
                return [
                    'id' => $item->id,
                    'date' => \Carbon\Carbon::parse($date)->format('Y-m-d'),
                    'type' => 'invoice',
                    'amount' => $item->amount,
                    'status' => $item->status, // paid, unpaid, partial
                    'description' => 'Invoice #' . $item->invoice_no,
                    'transaction_id' => $item->invoice_no
                ];
            });

        // 2. Manual Payments
        $manualPayments = \App\Models\Payment::where('client_id', $client->id)
            ->get()
            ->map(function ($item) {
                $date = $item->date ? $item->date : $item->created_at;
                return [
                    'id' => $item->id,
                    'date' => \Carbon\Carbon::parse($date)->format('Y-m-d'),
                    'type' => 'payment',
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'description' => 'Manual Payment (' . $item->method . ')',
                    'transaction_id' => $item->transaction_id
                ];
            });

        // 3. Webhook Payments (Online)
        $onlinePayments = \App\Models\WebhookPayment::where('client_id', $client->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'date' => \Carbon\Carbon::parse($item->created_at)->format('Y-m-d'),
                    'type' => 'payment_gateway',
                    'amount' => $item->amount,
                    'status' => $item->status, // success, failure
                    'description' => 'Online Payment (' . $item->payment_method . ')',
                    'transaction_id' => $item->transaction_id
                ];
            });

        // Merge and Sort
        $history = $invoices->concat($manualPayments)->concat($onlinePayments)->sortByDesc('date')->values();

        return response()->json([
            'client_summary' => [
                'id' => $client->id,
                'display_id' => $client->client_id_display,
                'monthly_fee' => $client->monthly_fee,
                'balance' => $client->balance,
                'due' => $client->monthly_fee_due,
                'last_payment' => $client->last_payment_date ? \Carbon\Carbon::parse($client->last_payment_date)->format('Y-m-d') : null,
                'status' => $client->status
            ],
            'history' => $history
        ]);
    }
}
