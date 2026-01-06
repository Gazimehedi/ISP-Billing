<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if ($request->filled('client_type')) $query->where('client_type', $request->client_type);

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
    public function store(Request $request)
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
    public function update(Request $request, Client $client)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|unique:clients,username,' . $client->id,
            'mobile' => 'sometimes|string|max:20',
            'profile_pic' => 'nullable|image|max:2048',
            'nid_pic' => 'nullable|image|max:2048',
            'res_form_pic' => 'nullable|image|max:2048',
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
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Client deleted successfully'
        ]);
    }
}
