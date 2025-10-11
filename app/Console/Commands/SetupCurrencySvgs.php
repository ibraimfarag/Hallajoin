<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Core\Models\Settings;

class SetupCurrencySvgs extends Command
{
    protected $signature = 'currency:setup-svgs';

    protected $description = 'Setup SVG symbols for different currencies';

    public function handle()
    {
        $currencies = [
            'usd' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7 15h2c1.1 0 2-.9 2-2s-.9-2-2-2H7V9h2c1.1 0 2-.9 2-2s-.9-2-2-2H7V3H5v2H3v2h2v2H3v2h2v2H3v2h2v2h2v-2h2c1.1 0 2-.9 2-2s-.9-2-2-2h-2v-2z"/></svg>',
            'eur' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7.07 12.51c.07-.4.07-.82 0-1.22h4.45v-1.3H6.19c.25-.66.7-1.24 1.3-1.68l-.74-1.08c-.85.61-1.46 1.47-1.74 2.46H3v1.3h2.05c-.09.39-.09.83 0 1.22H3v1.3h2.01c.28.99.89 1.85 1.74 2.46l.74-1.08c-.6-.44-1.05-1.02-1.3-1.68h4.33v-1.3H7.07z"/></svg>',
            'aed' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>',
        ];

        foreach ($currencies as $currency => $svg) {
            $setting = Settings::where('name', 'currency_svg_' . $currency)->first();

            if (!$setting) {
                $setting = new Settings;
                $setting->name = 'currency_svg_' . $currency;
            }

            $setting->val = $svg;
            $setting->save();

            $this->info("SVG for {$currency} currency has been set up.");
        }

        $this->info('All currency SVGs have been set up successfully!');

        // مسح الـ cache
        \Artisan::call('cache:clear');

        return 0;
    }
}
