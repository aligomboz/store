<?php

use App\Http\Controllers\BackEnd\backEndController;
use App\Http\Controllers\BackEnd\CityController;
use App\Http\Controllers\BackEnd\CountryController;
use App\Http\Controllers\BackEnd\CustomerController;
use App\Http\Controllers\BackEnd\MediaController;
use App\Http\Controllers\BackEnd\ProductCategoryController;
use App\Http\Controllers\BackEnd\ProductController;
use App\Http\Controllers\BackEnd\ProductCouponController;
use App\Http\Controllers\BackEnd\ProductReviewController;
use App\Http\Controllers\BackEnd\ShippingCompanyController;
use App\Http\Controllers\BackEnd\StateController;
use App\Http\Controllers\BackEnd\SupervisorController;
use App\Http\Controllers\BackEnd\TagController;
use App\Http\Controllers\BackEnd\UserAddressController;
use App\Http\Controllers\FrontEnd\frontEndController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});
/////////////// FrontEnd  ///////////////////////
Route::get('/',[frontEndController::class,'index'])->name('front.index');
Route::get('/cart',[frontEndController::class,'cart'])->name('front.cart');
Route::get('/checkout',[frontEndController::class,'checkout'])->name('front.checkout');
Route::get('/product/{slug?}', [frontEndController::class, 'product'])->name('frontend.product');
Route::get('/shop/{slug?}', [frontEndController::class, 'shop'])->name('frontend.shop');
Auth::routes(['verify'=>true]);
//////////////////////////  BackEnd  /////////////////////
Route::prefix('admin')->as('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login',[backEndController::class,'login'])->name('login');
        Route::get('/forgot-password',[backEndController::class,'register'])->name('forgot-password');
    });
   
    Route::middleware(['roles','role:admin|supervisor'])-> group(function () {
        Route::get('/',[backEndController::class,'index'])->name('index_admin');
        Route::get('/account_settings', [BackendController::class, 'account_settings'])->name('account_settings');
        Route::post('/admin/remove-image', [BackendController::class, 'remove_image'])->name('remove_image');
        Route::patch('/account_settings', [backEndController::class, 'update_account_settings'])->name('update_account_settings');
        Route::get('/index',[backEndController::class,'index'])->name('index');
        Route::post('/product_categories/remove-image', [ProductCategoryController::class, 'remove_image'])->name('product_categories.remove_image');
        Route::resource('product_categories', ProductCategoryController::class);
        Route::post('/products/remove-image', [ProductController::class, 'remove_image'])->name('products.remove_image');
        Route::resource('products', ProductController::class);
        Route::resource('media', MediaController::class);
        Route::resource('tags', TagController::class);
        Route::resource('product_coupons',ProductCouponController::class);
        Route::resource('product_reviews', ProductReviewController::class);
        Route::post('/customers/remove-image', [CustomerController::class, 'remove_image'])->name('customers.remove_image');
        Route::get('/customers/get_customers', [CustomerController::class, 'get_customers'])->name('customers.get_customers');
        Route::resource('customers', CustomerController::class);
        Route::post('/supervisors/remove-image', [SupervisorController::class, 'remove_image'])->name('supervisors.remove_image');
        Route::resource('supervisors', SupervisorController::class);
        Route::resource('countries', CountryController::class);
        Route::get('states/get_states', [StateController::class, 'get_states'])->name('states.get_states');
        Route::resource('states', StateController::class);
        Route::get('cities/get_cities', [CityController::class, 'get_cities'])->name('cities.get_cities');
        Route::resource('cities', CityController::class);
        Route::resource('customer_addresses', UserAddressController::class);
        Route::resource('shipping_companies', ShippingCompanyController::class);

    });
});


Auth::routes(['verify' => true]);

