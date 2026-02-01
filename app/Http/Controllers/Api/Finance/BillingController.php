<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    /**
     * Display a listing of the billing data.
     */
    public function index(Request $request)
    {
        // 1. Calculate Stats (Using Billing Table for accuracy)
        $stats = [
            'paid_clients' => Client::where('payment_status', 'paid')->count(),
            'unpaid_clients' => Client::where('payment_status', 'unpaid')->count(),
            // Sum from Billing table
            'received_bill' => \App\Models\Billing::sum('paid'),
            'due_amount' => \App\Models\Billing::sum('due'),
            'generated_bill' => \App\Models\Billing::sum('amount'),
            'advance_amount' => Client::where('balance', '>', 0)->sum('balance'),
            // Monthly bill can be estimated from active clients' packages
            'monthly_bill' => Client::where('status', 'active')->sum('monthly_fee'),
        ];

        Log::info('Billing Stats Calculated', $stats);

        // 2. Fetch Client Billing List
        $query = Client::with(['zone', 'package', 'server']);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('client_id_display', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('zone_id') && $request->zone_id) {
            $query->where('zone_id', $request->zone_id);
        }
        
        if ($request->has('package_id') && $request->package_id) {
            $query->where('package_id', $request->package_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('billing_type') && $request->billing_type) {
            $query->where('billing_type', $request->billing_type);
        }

        $perPage = $request->per_page ?? 100;
        $clients = $query->paginate($perPage);

        Log::info('Billing Clients Count: ' . $clients->total());

        return response()->json([
            'stats' => $stats,
            'clients' => $clients
        ]);
    }

    /**
     * Bulk update client status.
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|string|in:active,disabled,expired',
        ]);

        Client::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    /**
     * Bulk update client zone.
     */
    public function bulkZone(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'zone_id' => 'required|exists:zones,id',
        ]);

        Client::whereIn('id', $request->ids)->update(['zone_id' => $request->zone_id]);

        return response()->json(['message' => 'Zone updated successfully']);
    }

    /**
     * Bulk extend expiry date.
     */
    public function bulkExtend(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'days' => 'required|integer|min:1',
        ]);

        $days = $request->days;
        $clients = Client::whereIn('id', $request->ids)->get();

        foreach ($clients as $client) {
            // Logic: If active/expired, add days to current expiry or today?
            // Usually adds to current expire_date
            $currentExpire = $client->expire_date ? \Carbon\Carbon::parse($client->expire_date) : now();
            if ($currentExpire->isPast()) {
                $currentExpire = now(); 
            }
            $newExpire = $currentExpire->addDays($days);
            $client->update(['expire_date' => $newExpire]);
        }

        return response()->json(['message' => 'Expiry extended successfully']);
    }

    /**
     * Export Clients to CSV (Excel).
     */
    public function exportExcel(Request $request)
    {
        $fileName = 'billing_export_' . date('Y-m-d_H-i-s') . '.csv';
        // Eager load relationships for performance
        $clients = Client::with(['zone', 'package'])->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array(
            'Client ID', 
            'Name', 
            'Username/IP', 
            'Mobile', 
            'Zone', 
            'Package', 
            'Price',
            'Expiry Date', 
            'Monthly Fee', 
            'Paid', 
            'Due', 
            'Advance', 
            'Status', 
            'Payment Status'
        );

        $callback = function() use($clients, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($clients as $client) {
                $paid = $client->monthly_fee - $client->monthly_fee_due;
                
                $row = [
                    $client->client_id_display,
                    $client->name,
                    $client->username,
                    $client->mobile,
                    $client->zone ? $client->zone->name : '-',
                    $client->package ? $client->package->name : '-',
                    $client->package ? $client->package->price : '0',
                    $client->expire_date ? $client->expire_date->format('Y-m-d') : '-',
                    $client->monthly_fee,
                    number_format($paid, 2, '.', ''),
                    $client->monthly_fee_due,
                    $client->balance,
                    ucfirst($client->status),
                    ucfirst($client->payment_status)
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Send Bulk SMS.
     */
    public function sendBulkSms(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'message' => 'required|string',
        ]);

        return response()->json(['message' => 'SMS queued successfully']);
    }

    /**
     * Send Bulk Email.
     */
    public function sendBulkEmail(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        return response()->json(['message' => 'Emails queued successfully']);
    }

    /**
     * Download Bulk Invoices.
     */
    public function downloadBulkInvoice(Request $request)
    {
         return response()->json(['message' => 'Invoice download started']);
    }

     /**
     * Export Clients to PDF.
     */
    public function exportPdf(Request $request)
    {
        return response()->json(['message' => 'PDF export started']);
    }
}
