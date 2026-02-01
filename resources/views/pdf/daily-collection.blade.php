<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Collection Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1e293b;
        }
        .header p {
            margin: 5px 0;
            color: #64748b;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .stat-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background: #1e293b;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            font-weight: bold;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #64748b;
        }
        tfoot {
            background: #f1f5f9;
            font-weight: bold;
        }
        tfoot td {
            padding: 8px 5px;
            border-top: 2px solid #1e293b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Collection Report</h1>
        <p>Generated on {{ date('d M Y, h:i A') }}</p>
        @if($filters['from_date'] && $filters['to_date'])
            <p>Period: {{ date('d M Y', strtotime($filters['from_date'])) }} - {{ date('d M Y', strtotime($filters['to_date'])) }}</p>
        @endif
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="stat-label">Total Received</div>
            <div class="stat-value">BDT {{ number_format($stats['total_received'], 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Discount</div>
            <div class="stat-value">BDT {{ number_format($stats['total_discount'], 2) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Transactions</div>
            <div class="stat-value">{{ $collections->count() }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Client ID</th>
                <th>Customer Name</th>
                <th>Mobile</th>
                <th class="text-right">M.Bill</th>
                <th class="text-right">Received</th>
                <th class="text-right">Discount</th>
                <th>Method</th>
                <th>Received By</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($collections as $item)
            <tr>
                <td>{{ date('d M Y', strtotime($item->date)) }}</td>
                <td>{{ $item->client->client_id_display ?? '-' }}</td>
                <td>{{ $item->client->name ?? '-' }}</td>
                <td>{{ $item->client->mobile ?? '-' }}</td>
                <td class="text-right">BDT {{ number_format($item->client->monthly_fee ?? 0, 2) }}</td>
                <td class="text-right"><span class="badge badge-success">BDT {{ number_format($item->amount, 2) }}</span></td>
                <td class="text-right"><span class="badge badge-warning">BDT {{ number_format($item->discount, 2) }}</span></td>
                <td style="text-transform: uppercase;">{{ $item->method }}</td>
                <td>{{ $item->collector->name ?? '-' }}</td>
                <td>
                    @if($item->status === 'approved')
                        <span class="badge badge-success">APPROVED</span>
                    @else
                        <span class="badge badge-warning">PENDING</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No transactions found</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right">TOTAL</td>
                <td class="text-right">BDT {{ number_format($collections->sum('amount'), 2) }}</td>
                <td class="text-right">BDT {{ number_format($collections->sum('discount'), 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>This is a computer-generated document. No signature required.</p>
        <p>ISP Billing System - Daily Collection Report</p>
    </div>
</body>
</html>
