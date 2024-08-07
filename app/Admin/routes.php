<?php

use App\Admin\Controllers\AddressController;
use App\Admin\Controllers\AttributeController;
use App\Admin\Controllers\AttributeValueController;
use App\Admin\Controllers\CategoryController;
use App\Admin\Controllers\OrderController;
use App\Admin\Controllers\OrderItemController;
use App\Admin\Controllers\PaymentController;
use App\Admin\Controllers\ProductController;
use App\Admin\Controllers\ReviewController;
use App\Admin\Controllers\SettingController;
use App\Admin\Controllers\UserController;
use App\Admin\Controllers\VariantAttributeController;
use App\Admin\Controllers\VariantController;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('addresses', AddressController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('products', ProductController::class);
    $router->resource('reviews', ReviewController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('order-items', OrderItemController::class);
    $router->resource('payments', PaymentController::class);
    $router->resource('attributes', AttributeController::class);
    $router->resource('attribute-values', AttributeValueController::class);
    $router->resource('variants', VariantController::class);
    $router->resource('variant-attributes', VariantAttributeController::class);

    // Individual routes for settings
    $router->get('settings/general', [SettingController::class, 'general'])->name('settings.general');
    $router->get('settings/site', [SettingController::class, 'site'])->name('settings.site');
    $router->get('settings/shipping', [SettingController::class, 'shipping'])->name('settings.shipping');
    $router->get('settings/payment', [SettingController::class, 'payment'])->name('settings.payment');
    $router->get('settings/tax', [SettingController::class, 'tax'])->name('settings.tax');
    $router->get('settings/notifications', [SettingController::class, 'notifications'])->name('settings.notifications');
    $router->get('settings/api', [SettingController::class, 'api'])->name('settings.api');
});
