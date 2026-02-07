<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SmsLog::query();

        if ($request->filled('search')) {
            $query->where('number', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('provider')) {
            $query->where('provider', $request->provider);
        }

        $logs = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($logs);
    }

    public function stats()
    {
        return response()->json([
            'total' => SmsLog::count(),
            'success' => SmsLog::where('status', 'success')->count(),
            'failed' => SmsLog::where('status', 'failed')->count(),
            'today_count' => SmsLog::whereDate('created_at', now()->today())->count(),
        ]);
    }

    public function destroy($id)
    {
        $log = SmsLog::findOrFail($id);
        $log->delete();
        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        SmsLog::truncate();
        return response()->json(['success' => true]);
    }
}
