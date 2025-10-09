<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Admin\UserController;

// Admin Route
/*Route::group(['prefix'=>'admin','middleware' => ['auth','dashboard']], function() {
    Route::match(['get','post'],'/',function (){
        $module = ucfirst(htmlspecialchars('Dashboard'));
        $controller = ucfirst(htmlspecialchars($module));
        $class = "\\Modules\\$module\\Admin\\";
        $action = 'index';
        if(class_exists($class.$controller.'Controller') && method_exists($class.$controller.'Controller',$action)){
            return App::call($class.$controller.'Controller@'.$action,[]);
        }
        abort(404);
    });
    Route::match(['get','post'],'/module/{module}/{controller?}/{action?}/{param1?}/{param2?}/{param3?}',function ($module,$controller = '',$action = '',$param1 = '',$param2 = '',$param3 = ''){
        $module = ucfirst(htmlspecialchars($module));
        $controller = ucfirst(htmlspecialchars($controller));
        $class = "\\Modules\\$module\\Admin\\";
        if(!class_exists($class.$controller.'Controller')){
            $param3 = $param2;
            $param2 = $param1;
            $param1 = $action;
            $action = $controller;
            $controller = $module;
        }
        $action = $action ? $action : 'index';
        if(class_exists($class.$controller.'Controller') && method_exists($class.$controller.'Controller',$action)){
            $p = array_values(array_filter([$param1,$param2,$param3]));
            return App::call($class.$controller.'Controller@'.$action,$p);
//            return App::make($class.$controller.'Controller')->callAction($action,$p);
        }
        abort(404);
    });
});*/

Route::group(['prefix' => config('admin.admin_route_prefix'), 'middleware' => ['auth', 'dashboard']], function () {
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/user/session/{id}', [UserController::class, 'deleteSession'])->name('admin.user.session.delete');
    Route::post('/user/{id}/toggle-block', [UserController::class, 'toggleBlock'])->name('admin.user.toggle.block');
    Route::post('/user/{id}/toggle-order-block', [UserController::class, 'toggleOrderBlock'])->name('admin.user.toggle.order.block');

    // Favourites routes
    Route::get('/favourites', [\App\Http\Controllers\Admin\FavouriteAdminController::class, 'index'])->name('admin.favourites.index');
    Route::get('/favourites/{favourite}', [\App\Http\Controllers\Admin\FavouriteAdminController::class, 'show'])->name('admin.favourites.show');
    Route::delete('/favourites/{favourite}', [\App\Http\Controllers\Admin\FavouriteAdminController::class, 'destroy'])->name('admin.favourites.destroy');

    // Balance route
    Route::get('/balance', [\App\Http\Controllers\Admin\BalanceAdminController::class, 'index'])->name('admin.balance.index');
});