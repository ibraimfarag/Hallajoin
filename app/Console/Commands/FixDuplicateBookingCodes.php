<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;

class FixDuplicateBookingCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:fix-duplicates {--dry-run : Run without actually updating the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix duplicate booking codes by assigning unique sequential numbers';

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

        $this->info('🔍 Checking for duplicate booking codes...');
        $this->newLine();

        // Find duplicate codes
        $duplicates = DB::table('bravo_bookings')
            ->select('code', DB::raw('COUNT(*) as count'))
            ->groupBy('code')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicate booking codes found!');

            return 0;
        }

        $this->warn('Found '.$duplicates->count().' duplicate codes:');
        $this->newLine();

        // Show duplicates
        $tableData = [];
        foreach ($duplicates as $dup) {
            $tableData[] = [
                'Code' => $dup->code,
                'Count' => $dup->count,
            ];
        }
        $this->table(['Code', 'Count'], $tableData);
        $this->newLine();

        if (! $dryRun) {
            if (! $this->confirm('Do you want to proceed with fixing these duplicates?', true)) {
                $this->warn('❌ Operation cancelled.');

                return 0;
            }
        }

        $this->newLine();
        $this->info('🔄 Fixing duplicates...');
        $this->newLine();

        // Get the highest numeric code
        $maxNumericCode = DB::table('bravo_bookings')
            ->whereRaw('code REGEXP "^[0-9]+$"')
            ->orderByRaw('CAST(code AS UNSIGNED) DESC')
            ->value('code');

        $nextCode = intval($maxNumericCode) + 1;

        $fixed = 0;
        $failed = 0;

        foreach ($duplicates as $duplicate) {
            // Get all bookings with this code
            $bookings = Booking::where('code', $duplicate->code)
                ->orderBy('id')
                ->get();

            // Skip the first one (keep it), update the rest
            $bookingsToUpdate = $bookings->skip(1);

            foreach ($bookingsToUpdate as $booking) {
                try {
                    // Find next available code
                    while (Booking::where('code', $nextCode)->exists()) {
                        $nextCode++;
                    }

                    $oldCode = $booking->code;

                    if (! $dryRun) {
                        DB::table('bravo_bookings')
                            ->where('id', $booking->id)
                            ->update([
                                'code' => (string) $nextCode,
                                'updated_at' => now(),
                            ]);

                        $this->line("  ✓ Booking ID {$booking->id}: {$oldCode} → {$nextCode}");
                    } else {
                        $this->line("  [DRY RUN] Would update Booking ID {$booking->id}: {$oldCode} → {$nextCode}");
                    }

                    $fixed++;
                    $nextCode++;
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("  ✗ Failed to update Booking ID {$booking->id}: {$e->getMessage()}");
                }
            }
        }

        $this->newLine();

        if ($dryRun) {
            $this->info('🔍 DRY RUN RESULTS:');
        } else {
            $this->info('✅ FIX COMPLETE:');
        }

        $this->newLine();
        $this->line('  • Bookings '.($dryRun ? 'would be ' : '')."updated: {$fixed}");

        if ($failed > 0) {
            $this->error("  • Failed: {$failed}");
        }

        $this->newLine();

        if ($dryRun) {
            $this->info('💡 To actually update the database, run without --dry-run:');
            $this->line('   php artisan booking:fix-duplicates');
        } else {
            $this->info('🎉 All duplicates have been fixed!');
        }

        return 0;
    }
}
