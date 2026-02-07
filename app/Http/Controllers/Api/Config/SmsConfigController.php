<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\SmsConfig;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsConfigController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('provider')) {
            return response()->json(SmsConfig::where('provider', $request->provider)->first());
        }
        return response()->json(SmsConfig::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'api_key' => 'required|string',
            'sender_id' => 'required|string',
            'api_url' => 'required|url',
        ]);

        // If this config is set to active, disable all other providers
        if ($request->is_active) {
            SmsConfig::where('provider', '!=', $request->provider)->update(['is_active' => false]);
        }

        $config = SmsConfig::updateOrCreate(
            ['provider' => $request->provider],
            [
                'api_key' => $request->api_key,
                'sender_id' => $request->sender_id,
                'api_url' => $request->api_url,
                'is_active' => $request->is_active ?? false,
            ]
        );

        return response()->json($config);
    }

    public function checkBalance(Request $request)
    {
        $provider = $request->get('provider', 'bulksmsbd');
        $config = SmsConfig::where('provider', $provider)->first();
        
        if (!$config || !$config->api_key) {
            return response()->json(['error' => 'API Key not configured'], 422);
        }

        try {
            if ($provider === 'bulksmsbd') {
                // BulksmsBD Balance Check API: http://bulksmsbd.net/api/getBalanceApi
                $response = Http::get('http://bulksmsbd.net/api/getBalanceApi', [
                    'api_key' => $config->api_key
                ]);
            } else if ($provider === 'mimsms') {
                // MimSMS Balance Check API: https://api.mimsms.com/api/SmsSending/GetBalance
                $response = Http::get('https://api.mimsms.com/api/SmsSending/GetBalance', [
                    'api_key' => $config->api_key
                ]);
            } else {
                return response()->json(['error' => 'Unsupported provider'], 400);
            }

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not connect to provider'], 500);
        }
    }

    public function testSend(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'message' => 'required|string',
        ]);

        $smsService = new SmsService();
        $result = $smsService->send($request->number, $request->message);

        return response()->json($result);
    }
}
