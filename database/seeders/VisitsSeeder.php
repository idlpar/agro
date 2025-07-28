<?php

namespace Database\Seeders;

use App\Models\Visit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VisitsSeeder extends Seeder
{
    public function run()
    {
        $customers = User::customer()->get();
        $staff = User::staff()->get();

        // Past visits
        for ($i = 0; $i < 10; $i++) {
            $customer = $customers->random();
            $staffMember = $staff->random();
            $date = Carbon::now()->subDays(rand(1, 60));

            Visit::create([
                'customer_id' => $customer->id,
                'assigned_to' => $staffMember->id,
                'scheduled_date' => $date,
                'purpose' => $this->getRandomPurpose(),
                'notes' => rand(1, 100) <= 30 ? 'Customer requested follow up' : null,
                'completed_at' => $date->addHours(rand(1, 3)),
                'collected_amount' => rand(1, 100) <= 70 ? rand(500, 5000) : null,
                'outcome' => $this->getRandomOutcome(),
            ]);
        }

        // Upcoming visits
        for ($i = 0; $i < 5; $i++) {
            $customer = $customers->random();
            $staffMember = $staff->random();
            $date = Carbon::now()->addDays(rand(1, 14));

            Visit::create([
                'customer_id' => $customer->id,
                'assigned_to' => $staffMember->id,
                'scheduled_date' => $date,
                'purpose' => $this->getRandomPurpose(),
                'expected_amount' => rand(1, 100) <= 50 ? rand(1000, 10000) : null,
                'notes' => rand(1, 100) <= 20 ? 'Customer requested morning visit' : null,
            ]);
        }
    }

    private function getRandomPurpose()
    {
        $purposes = [
            'Payment Collection',
            'Product Delivery',
            'Customer Follow-up',
            'New Order',
            'Complaint Resolution',
            'Account Update'
        ];

        return $purposes[array_rand($purposes)];
    }

    private function getRandomOutcome()
    {
        $outcomes = [
            'Payment Collected',
            'Order Placed',
            'Rescheduled',
            'Customer Not Available',
            'Issue Resolved',
            'Follow-up Needed'
        ];

        return $outcomes[array_rand($outcomes)];
    }
}
