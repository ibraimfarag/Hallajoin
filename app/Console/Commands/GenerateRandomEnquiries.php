<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Booking\Models\Enquiry;
use App\User;
use Illuminate\Support\Facades\DB;

class GenerateRandomEnquiries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enquiry:generate {count=10 : Number of enquiries to generate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random enquiries without salesman assigned';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = (int) $this->argument('count');

        $this->info("Generating {$count} random enquiries...");

        // Get random users from database
        $users = User::where('status', 'publish')->pluck('id')->toArray();

        if (empty($users)) {
            $this->error('No users found in database!');
            return 1;
        }

        // Get random activities (tours, hotels, cars, etc.)
        $activities = [];

        // Tours
        $tours = DB::table('bravo_tours')
            ->where('status', 'publish')
            ->select('id', 'title')
            ->get();
        foreach ($tours as $tour) {
            $activities[] = [
                'id' => $tour->id,
                'model' => 'tour',
                'title' => $tour->title,
            ];
        }

        // Hotels
        $hotels = DB::table('bravo_hotels')
            ->where('status', 'publish')
            ->select('id', 'title')
            ->get();
        foreach ($hotels as $hotel) {
            $activities[] = [
                'id' => $hotel->id,
                'model' => 'hotel',
                'title' => $hotel->title,
            ];
        }

        // Cars
        $cars = DB::table('bravo_cars')
            ->where('status', 'publish')
            ->select('id', 'title')
            ->get();
        foreach ($cars as $car) {
            $activities[] = [
                'id' => $car->id,
                'model' => 'car',
                'title' => $car->title,
            ];
        }

        // Spaces
        $spaces = DB::table('bravo_spaces')
            ->where('status', 'publish')
            ->select('id', 'title')
            ->get();
        foreach ($spaces as $space) {
            $activities[] = [
                'id' => $space->id,
                'model' => 'space',
                'title' => $space->title,
            ];
        }

        // Events
        $events = DB::table('bravo_events')
            ->where('status', 'publish')
            ->select('id', 'title')
            ->get();
        foreach ($events as $event) {
            $activities[] = [
                'id' => $event->id,
                'model' => 'event',
                'title' => $event->title,
            ];
        }

        if (empty($activities)) {
            $this->error('No activities found in database!');
            return 1;
        }

        $messages = [
            'I am interested in booking this activity. Can you provide more details?',
            'What are the available dates for this service?',
            'Can you give me a discount for group booking?',
            'Is this available for the next month?',
            'I would like to know more about pricing and availability.',
            'Can I customize this package according to my needs?',
            'What is included in this package?',
            'Do you offer any special promotions?',
            'I need this for a corporate event. Can you help?',
            'Please send me more information about this service.',
        ];

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        for ($i = 0; $i < $count; $i++) {
            // Random user
            $randomUserId = $users[array_rand($users)];
            $user = User::find($randomUserId);

            // Random activity
            $randomActivity = $activities[array_rand($activities)];

            // Random units (1-10 people)
            $units = rand(1, 10);

            // Create enquiry
            $enquiry = new Enquiry();
            $enquiry->object_id = $randomActivity['id'];
            $enquiry->object_model = $randomActivity['model'];
            $enquiry->activity_name = $randomActivity['title'];
            $enquiry->name = $user->getDisplayName(true);
            $enquiry->email = $user->email;
            $enquiry->phone = $user->phone ?? '+20100' . rand(1000000, 9999999);
            $enquiry->message = $messages[array_rand($messages)];
            $enquiry->units = $units;
            $enquiry->status = 'pending';
            $enquiry->from_type = 'B2C';
            $enquiry->create_user = $randomUserId;
            $enquiry->salesman = null; // No salesman assigned
            $enquiry->created_at = now()->subDays(rand(0, 30));
            $enquiry->updated_at = $enquiry->created_at;

            $enquiry->save();

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info("✓ Successfully generated {$count} enquiries!");
        $this->info('All enquiries have NO salesman assigned and are ready for assignment.');

        return 0;
    }
}
