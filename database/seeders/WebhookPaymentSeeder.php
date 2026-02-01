<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebhookPayment;
use App\Models\Client;
use Illuminate\Support\Str;

class WebhookPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();

        if ($clients->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $client = $clients->random();
            WebhookPayment::create([
                'client_id' => $client->id,
                'transaction_id' => 'DA' . strtoupper(Str::random(8)),
                'amount' => 525.00,
                'payment_method' => collect(['bkash', 'nagad', 'rocket'])->random(),
                'payment_from' => '017' . rand(10000000, 99999999),
                'status' => 'completed',
                'gateway_response' => ['test' => 'data'],
                'processed_at' => now(),
                'created_at' => $i < 5 ? now() : now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
