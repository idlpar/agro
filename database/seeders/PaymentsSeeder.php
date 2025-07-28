<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    public function run()
    {
        $customers = User::customer()->get();
        $staff = User::staff()->get();

        for ($i = 0; $i < 15; $i++) {
            $customer = $customers->random();
            $staffMember = $staff->random();
            $date = Carbon::now()->subDays(rand(0, 30));

            Payment::create([
                'user_id' => $customer->id,
                'received_by' => $staffMember->id,
                'payment_date' => $date,
                'amount' => rand(500, 5000),
                'notes' => rand(1, 100) <= 20 ? 'Advance payment' : null,
            ]);
        }
    }
}
