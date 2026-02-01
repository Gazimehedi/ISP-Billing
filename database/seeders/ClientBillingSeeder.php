<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Package;
use Carbon\Carbon;

class ClientBillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        $packages = Package::all()->keyBy('id');

        foreach ($clients as $client) {
            $isPaid = rand(0, 100) < 80; // 80% paid
            $hasAdvance = rand(0, 100) < 10; // 10% have advance

            // 1. Set Monthly Fee based on Package
            $monthlyFee = 0;
            if ($client->package_id && isset($packages[$client->package_id])) {
                $monthlyFee = (float) $packages[$client->package_id]->price; // Assuming price column exists
                // If package price is missing/zero, use random reasonable amount
                if ($monthlyFee <= 0) $monthlyFee = rand(5, 20) * 100;
            } else {
                $monthlyFee = rand(5, 20) * 100;
            }

            $client->monthly_fee = $monthlyFee;

            // 2. Set Status & Dues
            if ($isPaid) {
                $client->payment_status = 'paid';
                $client->monthly_fee_due = 0;
                $client->last_payment_date = Carbon::now()->subDays(rand(1, 20));
            } else {
                $client->payment_status = 'unpaid';
                $client->monthly_fee_due = $monthlyFee;
                $client->last_payment_date = rand(0, 1) ? Carbon::now()->subMonths(1) : null;
            }

            // 3. Set Balance (Advance)
            if ($hasAdvance) {
                $client->balance = rand(1, 10) * 50;
            } else {
                $client->balance = 0;
            }

            // 4. Set Billing/Expire Dates if missing
            if (!$client->billing_date) {
                $client->billing_date = Carbon::now()->subDays(rand(0, 30));
            }
            if (!$client->expire_date) {
                $client->expire_date = Carbon::parse($client->billing_date)->addMonth();
            }

            $client->save();
        }
    }
}
