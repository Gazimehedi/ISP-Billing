<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Billing;
use Carbon\Carbon;

class BillingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::with('package')->get();

        foreach ($clients as $client) {
            // Generate bills for last 6 months
            for ($i = 0; $i < 6; $i++) {
                $date = Carbon::now()->subMonths($i)->startOfMonth();
                
                // Skip if client joining date is after this month
                if ($client->joining_date && Carbon::parse($client->joining_date)->gt($date)) {
                    continue;
                }

                $amount = $client->monthly_fee > 0 ? $client->monthly_fee : 
                          ($client->package ? $client->package->price : rand(500, 2000));
                
                // Randomize status
                 // Most old bills should be paid, recent ones might be unpaid
                $isPaid = $i > 0 ? (rand(0, 10) < 9) : (rand(0, 10) < 6); 
                
                if ($isPaid) {
                    $status = 'paid';
                    $paid = $amount;
                    $due = 0;
                    $paidDate = (clone $date)->addDays(rand(1, 15));
                } else {
                    $status = 'unpaid';
                    $paid = 0;
                    $due = $amount;
                    $paidDate = null;
                }

                Billing::create([
                    'client_id' => $client->id,
                    'month' => $date,
                    'invoice_no' => 'INV-' . $date->format('Ym') . '-' . $client->id,
                    'amount' => $amount,
                    'discount' => 0,
                    'paid' => $paid,
                    'due' => $due,
                    'status' => $status,
                    'payment_method' => $isPaid ? 'cash' : null,
                    'transaction_id' => $isPaid ? 'TRX' . rand(100000, 999999) : null,
                    'generated_date' => $date,
                    'due_date' => (clone $date)->addDays(10),
                    'paid_date' => $paidDate,
                    'notes' => 'Auto generated dummy bill',
                ]);
            }
        }
    }
}
