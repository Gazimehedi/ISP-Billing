<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function index()
    {
        return response()->json(SmsTemplate::all());
    }

    public function show($type)
    {
        $template = SmsTemplate::where('type', $type)->first();
        return response()->json($template);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'status' => 'required|boolean',
            'message_type' => 'required|string',
        ]);

        $template = SmsTemplate::updateOrCreate(
            ['type' => $request->type],
            $request->only(['title', 'content', 'status', 'message_type'])
        );

        return response()->json($template);
    }
}
