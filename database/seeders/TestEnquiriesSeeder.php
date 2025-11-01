<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Modules\Booking\Models\Enquiry;
use Modules\Tour\Models\Tour;

class TestEnquiriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get real data from database
        $user = User::where('role_id', 4)->where('status', 'publish')->first(); // Seals user
        $tours = Tour::where('status', 'publish')->take(3)->get();

        if ($tours->isEmpty()) {
            echo "No tours found in database. Please add tours first.\n";

            return;
        }

        // Create 3 test enquiries
        $enquiries = [
            [
                'object_id' => $tours[0]->id ?? 1,
                'object_model' => 'tour',
                'name' => 'Ahmed Mohamed',
                'email' => 'ahmed@example.com',
                'phone' => '+20 123 456 7890',
                'note' => 'I would like to book this tour for my family. We are 4 adults and 2 children.',
                'activity_name' => $tours[0]->title ?? 'Cairo City Tour',
                'package_name' => '', // Will be empty as requested
                'units' => 'Adults: 4, Children: 2',
                'user_type' => 'customer',
                'from_type' => 'B2C',
                'salesman' => $user ? $user->id : null,
                'vendor_id' => $tours[0]->author_id ?? null,
                'status' => 'pending',
                'publish_date' => now(),
                'create_user' => $user ? $user->id : null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'object_id' => $tours[1]->id ?? 2,
                'object_model' => 'tour',
                'name' => 'Sara Ali',
                'email' => 'sara@example.com',
                'phone' => '+20 100 123 4567',
                'note' => 'Looking for a group booking. We are a company trip with 15 employees.',
                'activity_name' => $tours[1]->title ?? 'Pyramids Tour',
                'package_name' => '', // Will be empty as requested
                'units' => 'Adults: 15',
                'user_type' => 'vendor',
                'from_type' => 'B2B',
                'salesman' => $user ? $user->id : null,
                'vendor_id' => $tours[1]->author_id ?? null,
                'status' => 'pending',
                'publish_date' => now(),
                'create_user' => $user ? $user->id : null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'object_id' => $tours[2]->id ?? 3,
                'object_model' => 'tour',
                'name' => 'Mohamed Hassan',
                'email' => 'mohamed@example.com',
                'phone' => '+20 111 987 6543',
                'note' => 'Interested in private tour for anniversary celebration. Just 2 people.',
                'activity_name' => $tours[2]->title ?? 'Nile Cruise',
                'package_name' => '', // Will be empty as requested
                'units' => 'Adults: 2',
                'user_type' => 'customer',
                'from_type' => 'B2C',
                'salesman' => $user ? $user->id : null,
                'vendor_id' => $tours[2]->author_id ?? null,
                'status' => 'pending',
                'publish_date' => now(),
                'create_user' => $user ? $user->id : null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($enquiries as $enquiryData) {
            Enquiry::create($enquiryData);
        }

        echo "✅ Successfully created 3 test enquiries!\n";
    }
}
