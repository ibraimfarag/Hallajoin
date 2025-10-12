<?php

use Illuminate\Support\Facades\Route;

/*
// Test route
Route::get('/test-cart-clear', function() {
    return view('test-cart-clear');
});

// Test cart clear without auth (for debugging)
Route::get('/test-clear-debug', function() {
    if (!Auth::check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }

    $cart = \App\Models\Cart::getActiveCartForUser(Auth::id());
    if ($cart) {
        $itemCount = $cart->items()->count();
        $cart->items()->delete();
        $cart->updateTotalAmount();
        return response()->json([
            'success' => true,
            'message' => "Cleared cart with {$itemCount} items"
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'No cart found'
    ]);
});

Route::middleware(['auth'])->group(function () {---------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/intro', 'LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

// Logs
Route::get(config('admin.admin_route_prefix') . '/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard', 'system_log_view'])->name('admin.logs');

use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {

    // Clear route cache
    Artisan::call('cache:clear');

    // Clear application cache
    Artisan::call('route:clear');
    // Clear config cache

    Artisan::call('config:clear');

    // Clear view cache
    Artisan::call('view:clear');

    return 'All caches have been cleared!';
});

// Cart Routes
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::get('/cart/count', 'CartController@getCount')->name('cart.count');

// Test route
Route::get('/test-cart-clear', function () {
    return view('test-cart-clear');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add', 'CartController@addItem')->name('cart.add');
    Route::put('/cart/item/{cartItem}', 'CartController@updateItem')->name('cart.update');
    Route::delete('/cart/item/{cartItem}', 'CartController@removeItem')->name('cart.remove');
    Route::delete('/cart/clear', 'CartController@clear')->name('cart.clear');
    Route::post('/cart/remove', 'CartController@removeItemById')->name('cart.remove.byid');
});

// Admin Cart Routes
Route::middleware(['auth', 'dashboard'])->prefix(config('admin.admin_route_prefix'))->group(function () {
    Route::get('/carts', 'Admin\CartAdminController@index')->name('admin.carts.index');
    Route::get('/carts/{cart}', 'Admin\CartAdminController@show')->name('admin.carts.show');
    Route::put('/carts/{cart}/status', 'Admin\CartAdminController@updateStatus')->name('admin.carts.status');
    Route::delete('/carts/{cart}', 'Admin\CartAdminController@destroy')->name('admin.carts.destroy');
    Route::get('/carts/stats/overview', 'Admin\CartAdminController@getStats')->name('admin.carts.stats');
});

// Route::get('/install','InstallerController@redirectToRequirement')->name('LaravelInstaller::welcome');
// Route::get('/install/environment','InstallerController@redirectToWizard')->name('LaravelInstaller::environment');
