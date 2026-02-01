<?php

namespace App\Http\Controllers\Api\Finance;

use App\Http\Controllers\Controller;
use App\Models\WebhookPayment;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookPaymentController extends Controller
{
    /**
     * Display a listing of webhook payments with stats.
     */
    public function index(Request $request)
    {
        $startDate = $request->from_date ? $request->from_date . ' 00:00:00' : now()->startOfMonth();
        $endDate = $request->to_date ? $request->to_date . ' 23:59:59' : now()->endOfDay();

        // Base Query
        $query = WebhookPayment::with(['client'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest();

        // Filters
        if ($request->has('username') && $request->username && $request->username != 'All User') {
            $username = $request->username;
            $query->whereHas('client', function($q) use ($username) {
                $q->where('username', 'like', "%{$username}%")
                  ->orWhere('name', 'like', "%{$username}%");
            });
        }

        if ($request->has('status') && $request->status && $request->status != 'Select') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method') && $request->payment_method && $request->payment_method != 'Select') {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('payment_from') && $request->payment_from) {
            $query->where('payment_from', 'like', "%{$request->payment_from}%");
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('client', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%")
                         ->orWhere('mobile', 'like', "%{$search}%")
                         ->orWhere('client_id_display', 'like', "%{$search}%");
                  });
            });
        }

        // Stats Calculation
        $stats = [
            'total_received' => (clone $query)->where('status', 'completed')->sum('amount'),
            'total_pending' => (clone $query)->where('status', 'pending')->count(),
            'total_count' => (clone $query)->count(),
        ];

        $perPage = $request->per_page ?? 25;
        $webhookPayments = $query->paginate($perPage);

        // Metadata for filters
        $users = \App\Models\User::select('id', 'name')->get();

        return response()->json([
            'stats' => $stats,
            'webhookPayments' => $webhookPayments,
            'users' => $users
        ]);
    }

    /**
     * Display the specified webhook payment.
     */
    public function show($id)
    {
        $payment = WebhookPayment::with('client')->findOrFail($id);
        return response()->json($payment);
    }

    /**
     * Export webhook payments to CSV or PDF.
     */
    public function export(Request $request)
    {
        $type = $request->type ?? $request->format ?? 'csv';
        $startDate = $request->from_date ? $request->from_date . ' 00:00:00' : now()->startOfMonth();
        $endDate = $request->to_date ? $request->to_date . ' 23:59:59' : now()->endOfDay();

        $query = WebhookPayment::with(['client'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest();

        // Apply filters
        if ($request->has('username') && $request->username && $request->username != 'All User') {
            $username = $request->username;
            $query->whereHas('client', fn($q) => $q->where('username', 'like', "%{$username}%"));
        }

        if ($request->has('status') && $request->status && $request->status != 'Select') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_method') && $request->payment_method && $request->payment_method != 'Select') {
            $query->where('payment_method', $request->payment_method);
        }

        $data = $query->get();

        if ($type === 'csv') {
            $filename = "webhook_payments_" . date('Y-m-d') . ".csv";
            $handle = fopen('php://temp', 'w+');

            // CSV Headers
            fputcsv($handle, [
                'Date', 'Transaction ID', 'Trxn.Ref', 'Customer Name', 'Mobile',
                'Amount', 'Payment From', 'Payment Method', 'Status', 'Processed At'
            ]);

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->created_at->format('Y-m-d H:i:s'),
                    $row->transaction_id,
                    $row->client->client_id_display ?? '-',
                    $row->client->name ?? '-',
                    $row->client->mobile ?? '-',
                    $row->amount,
                    $row->payment_from,
                    $row->payment_method,
                    $row->status,
                    $row->processed_at ? $row->processed_at->format('Y-m-d H:i:s') : '-'
                ]);
            }

            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            return response($content)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', "attachment; filename=\"$filename\"");
        }

        // PDF Export
        $collections = $data;
        $stats = [
            'total_received' => $collections->where('status', 'completed')->sum('amount'),
            'total_pending' => $collections->where('status', 'pending')->count(),
        ];

        $filters = [
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ];

        $pdf = \PDF::loadView('pdf.webhook-payments', compact('collections', 'stats', 'filters'));
        
        $filename = 'webhook-payments-' . date('Y-m-d-His') . '.pdf';
        
        return $pdf->download($filename);
    }
}
