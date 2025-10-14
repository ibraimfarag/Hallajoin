<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;

class UpdateOldBookingCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:update-old-codes {--dry-run : Run without actually updating the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update old booking codes that are null or too long to new sequential format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be saved to database');
            $this->newLine();
        }

        $this->info('📋 Checking for old booking codes...');
        $this->newLine();

        // Find bookings with null or very long codes (more than 10 characters)
        $oldBookings = Booking::where(function ($query) {
            $query->whereNull('code')
                ->orWhere('code', '')
                ->orWhereRaw('LENGTH(code) > 10')
                ->orWhereRaw('code NOT REGEXP "^[0-9]+$"'); // Not purely numeric
        })
            ->orderBy('id')
            ->get();

        if ($oldBookings->isEmpty()) {
            $this->info('✅ No old booking codes found. All bookings are up to date!');

            return 0;
        }

        $this->info('Found '.$oldBookings->count().' bookings to update:');
        $this->newLine();

        // Create a table to show what will be updated
        $tableData = [];
        foreach ($oldBookings->take(10) as $booking) {
            $oldCode = $booking->code ?: '[NULL]';
            if (strlen($oldCode) > 30) {
                $oldCode = substr($oldCode, 0, 27).'...';
            }
            $tableData[] = [
                'ID' => $booking->id,
                'Old Code' => $oldCode,
                'Created' => $booking->created_at->format('Y-m-d H:i'),
            ];
        }

        $this->table(['ID', 'Old Code', 'Created'], $tableData);

        if ($oldBookings->count() > 10) {
            $this->line('... and '.($oldBookings->count() - 10).' more bookings');
        }

        $this->newLine();

        if (! $dryRun) {
            if (! $this->confirm('Do you want to proceed with updating these booking codes?', true)) {
                $this->warn('❌ Operation cancelled.');

                return 0;
            }
        }

        $this->newLine();
        $this->info('🔄 Starting update process...');
        $this->newLine();

        $progressBar = $this->output->createProgressBar($oldBookings->count());
        $progressBar->start();

        $updated = 0;
        $failed = 0;
        $errors = [];

        // Get the starting number for new codes
        // Find the absolute maximum numeric code (to avoid conflicts)
        $maxNumericCode = DB::table('bravo_bookings')
            ->whereRaw('code REGEXP "^[0-9]+$"')
            ->orderByRaw('CAST(code AS UNSIGNED) DESC')
            ->value('code');

        $nextCode = ($maxNumericCode && intval($maxNumericCode) >= 1001)
            ? intval($maxNumericCode) + 1
            : 1001;

        foreach ($oldBookings as $booking) {
            try {
                $oldCode = $booking->code;

                if (! $dryRun) {
                    // Find next available code
                    while (Booking::where('code', $nextCode)->exists()) {
                        $nextCode++;
                    }

                    // Update the booking
                    DB::table('bravo_bookings')
                        ->where('id', $booking->id)
                        ->update([
                            'code' => (string) $nextCode,
                            'updated_at' => now(),
                        ]);

                    $updated++;
                } else {
                    // Just simulate
                    $updated++;
                }

                $nextCode++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Booking ID {$booking->id}: {$e->getMessage()}";
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Show results
        if ($dryRun) {
            $this->info('🔍 DRY RUN RESULTS:');
        } else {
            $this->info('✅ UPDATE COMPLETE:');
        }

        $this->newLine();
        $this->line("  • Total bookings checked: {$oldBookings->count()}");
        $this->line('  • Successfully '.($dryRun ? 'would be ' : '')."updated: {$updated}");

        if ($failed > 0) {
            $this->error("  • Failed: {$failed}");
            $this->newLine();
            $this->error('Errors:');
            foreach ($errors as $error) {
                $this->line("  - {$error}");
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->info('💡 To actually update the database, run the command without --dry-run:');
            $this->line('   php artisan booking:update-old-codes');
        } else {
            $this->info('🎉 All done! Old booking codes have been updated.');
        }

        return 0;
    }
}
