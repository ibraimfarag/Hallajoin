<?php

namespace Modules\Core\Admin;

use App\Currency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $activeCurrencies = Currency::getActiveCurrency();
        $currentCurrency = Currency::getCurrent('currency_main');
        $currentSvgSymbol = setting_item('currency_symbol_svg');

        // الحصول على SVGs للعملات المختلفة
        $currencySvgs = [];
        foreach ($activeCurrencies as $currency) {
            $code = strtolower($currency['currency_main']);
            $currencySvgs[$code] = setting_item('currency_svg_' . $code);
        }

        return view('Core::admin.currency.index', compact('activeCurrencies', 'currentCurrency', 'currentSvgSymbol', 'currencySvgs'));
    }

    public function updateSvgSymbol(Request $request)
    {
        $request->validate([
            'currency_symbol_svg' => 'nullable|string|max:5000',
        ]);

        try {
            // تحديث إعداد رمز SVG
            DB::table('core_settings')
                ->where('name', 'currency_symbol_svg')
                ->update([
                    'val' => $request->currency_symbol_svg ?? '',
                    'updated_at' => now(),
                ]);

            // مسح الـ cache لضمان التحديث الفوري
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث رمز العملة SVG بنجاح',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث رمز العملة: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function preview(Request $request)
    {
        $svgSymbol = $request->svg_symbol ?? '';
        $testAmount = 100.50;

        // حفظ رمز SVG مؤقتاً للمعاينة
        $originalSvg = setting_item('currency_symbol_svg');

        // تحديث مؤقت
        DB::table('core_settings')
            ->where('name', 'currency_symbol_svg')
            ->update(['val' => $svgSymbol]);

        // مسح الـ cache للمعاينة
        \Artisan::call('cache:clear');

        // معاينة مع SVG
        $formattedWithSvg = format_money_with_svg($testAmount, true);

        // معاينة بدون SVG (النص العادي)
        $formattedWithoutSvg = format_money_main($testAmount);

        // إرجاع الإعداد الأصلي
        DB::table('core_settings')
            ->where('name', 'currency_symbol_svg')
            ->update(['val' => $originalSvg]);

        \Artisan::call('cache:clear');

        return response()->json([
            'with_svg' => $formattedWithSvg,
            'without_svg' => $formattedWithoutSvg,
            'test_amount' => $testAmount,
        ]);
    }

    public function updateCurrencySvg(Request $request)
    {
        $request->validate([
            'currency_code' => 'required|string|size:3',
            'svg_symbol' => 'nullable|string|max:5000',
        ]);

        try {
            $currencyCode = strtolower($request->currency_code);
            $settingName = 'currency_svg_' . $currencyCode;

            // تحديث أو إنشاء إعداد SVG للعملة
            $setting = \Modules\Core\Models\Settings::where('name', $settingName)->first();

            if (!$setting) {
                $setting = new \Modules\Core\Models\Settings;
                $setting->name = $settingName;
            }

            $setting->val = $request->svg_symbol ?? '';
            $setting->save();

            // مسح الـ cache لضمان التحديث الفوري
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث رمز العملة ' . strtoupper($currencyCode) . ' بنجاح',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث رمز العملة: ' . $e->getMessage(),
            ], 500);
        }
    }
}
