<?php

namespace App\Services;

use App\Models\SmsConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS through the active gateway.
     * 
     * @param string $number Phone number (including country code)
     * @param string $message The message content
     * @param string $type Message type (text, unicode)
     * @return array
     */
    public function send($number, $message)
    {
        $config = SmsConfig::where('is_active', true)->first();

        if (!$config) {
            return [
                'status' => 'error',
                'message' => 'No active SMS gateway configured.'
            ];
        }

        // Detect if message has unicode
        $isUnicode = preg_match('/[^\x00-\x7F]/', $message);
        $type = $isUnicode ? 'unicode' : 'text';
        
        // Calculate SMS Count (Parts)
        $limit = $isUnicode ? 70 : 160;
        $sms_count = ceil(mb_strlen($message) / $limit);

        try {
            if ($config->provider === 'bulksmsbd') {
                $result = $this->sendBulksmsBD($config, $number, $message, $isUnicode);
            } else if ($config->provider === 'mimsms') {
                $result = $this->sendMimSMS($config, $number, $message);
            } else {
                return ['status' => 'error', 'message' => 'Unsupported provider'];
            }

            // Automatic Logging
            \App\Models\SmsLog::create([
                'number' => $number,
                'content' => $message,
                'provider' => $config->provider,
                'type' => $type,
                'sms_count' => $sms_count,
                'status' => ($result['status'] === 'success' || isset($result['response_code'])) ? 'success' : 'failed',
                'response_msg' => json_encode($result)
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error("SMS Sending Failed: " . $e->getMessage());
            
            \App\Models\SmsLog::create([
                'number' => $number,
                'content' => $message,
                'provider' => $config->provider,
                'status' => 'failed',
                'response_msg' => $e->getMessage()
            ]);

            return [
                'status' => 'error',
                'message' => 'SMS Gateway Connection Error'
            ];
        }
    }

    private function sendBulksmsBD($config, $number, $message, $isUnicode)
    {
        // BulksmsBD API: http://bulksmsbd.net/api/smsapi
        $response = Http::get($config->api_url, [
            'api_key' => $config->api_key,
            'type' => $isUnicode ? 'unicode' : 'text',
            'number' => $number,
            'senderid' => $config->sender_id,
            'message' => $message
        ]);

        return $response->json();
    }

    private function sendMimSMS($config, $number, $message)
    {
        // MimSMS API: https://api.mimsms.com/api/SmsSending/Send
        // They typically use POST with JSON or Params
        $response = Http::post($config->api_url, [
            'api_key' => $config->api_key,
            'type' => 'text', // MimSMS usually handles unicode automatically or via different params
            'contacts' => $number,
            'senderid' => $config->sender_id,
            'msg' => $message
        ]);

        return $response->json();
    }
}
