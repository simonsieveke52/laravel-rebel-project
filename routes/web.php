<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Backend routes
 */
Route::group(['prefix' => 'fme-admin'], function () {
    
    Route::group(['middleware' => 'admin.user', 'namespace' => 'Voyager'], function() {
        Route::get('products/mailchimp-sync', 'ProductsController@mailchimpSync')->name('products.mailchimp.sync');
    });

    Voyager::routes();

    Route::group(['middleware' => 'admin.user'], function(){
        Route::namespace('Voyager')->group(function() {

            Route::get('amazon-orders', 'OrdersController@amazonIndex')->name('voyager.amazon-orders.index');
            Route::get('export-orders', 'OrdersController@export')->name('export.orders');
            Route::get('export-orders-range', 'OrdersController@exportByRange')->name('export.orders-range');
            Route::get('google-orders', 'OrdersController@googleIndex')->name('voyager.google-orders.index');

            Route::post('/order/reviewd/{order?}', 'OrdersController@review')->name('order.review');
            Route::post('/update-order-status', 'OrdersController@updateOrderStatus')->name('voyager.update.orderStatus');
            Route::get('/order/{order}/refund', 'OrdersController@refund')->name('refund.order');
            Route::get('/order/{order}/{action}', 'OrdersController@approveOrDecline')->name('approve-or-decline.order');
            Route::post('/order/notify', 'OrdersController@notify')->name('voyager.mail.order');

            Route::get('clear-cache', 'VoyagerSettingsController@clearCache')->name('cache.clear');

            // blacklist skus and update products
            Route::get('product-handler', 'ProductHandlerController@index')->name('product-handler.index');
            Route::put('product-handler', 'ProductHandlerController@update')->name('product-handler.update');

            Route::get('/order/update-notes', 'OrdersController@updateNotes')->name('show-import.order');
            Route::put('/order/update-notes', 'OrdersController@storeUpdateNotes')->name('import.order');

            Route::get('/quotes/{id}/edit', 'QuotesController@show')->name('voyager.quotes.edit');
            Route::get('/quotes/{id}/', 'QuotesController@get')->name('voyager.quotes.get');
            Route::put('/quotes/{id}/update', 'QuotesController@update')->name('voyager.quotes.update');

            Route::get('product-handler/export', 'ProductHandlerController@exportProducts')->name('show-product-handler.export');
            Route::put('product-handler/export', 'ProductHandlerController@export')->name('product-handler.export');
        });

        Route::post('/order-details', 'API\OrdersController@show')->name('voyager.show.order');
        Route::get('/tracking/notify', 'Voyager\TrackingNumbersController@notify')->name('voyager.notify.tracking');
        Route::post('/tracking/create', 'Voyager\TrackingNumbersController@create')->name('voyager.create.tracking');
        Route::post('/tracking-numbers', 'API\TrackingNumbersController@show')->name('voyager.show.tracking');

        Route::get('/bulk-order-create', 'QuoteRequestController@adminCreate')->name('quoterequest.adminCreate');
        Route::post('/bulk-order-store', 'QuoteRequestController@adminStore')->name('quoterequest.adminStore');
    });
});

Route::group(['middleware' => ['urlPreTraitement']], function() {

    /**
     * Frontend routes
     */
    Route::namespace('Customer')->group(function() {
        Auth::routes();
    });

    // Sitemap
    Route::get('/sitemap', 'SitemapController@index')->name('sitemap');

    //
    Route::post('/facebook', 'ReportFacebookEventController@store')->name('facebook.store');

    Route::group(['middleware' => ['auth:customer', 'injectOrderData']], function () {

        // customer routes
        Route::namespace('Customer')->group(function() {
            Route::get('account', 'AccountController@index')->name('customer.account');
            Route::resource('/customer.address', 'CustomerAddressController')->only(['index', 'create', 'store', 'destroy']);
            Route::any('customer/address', 'CustomerAddressController@update')->name('customer.address.update');
        });

        Route::get('checkout', 'CheckoutController@index')->name('checkout.index');
        Route::post('checkout', 'CheckoutController@store')->name('checkout.store');
    });

    Route::group(['middleware' => ['checkoutValidation', 'injectOrderData']], function(){
        // Route::get('confirm', 'CheckoutBaseController@confirm')->name('checkout.confirm');
        // Route::get('execute', 'CheckoutBaseController@execute')->name('checkout.execute');
        Route::get('success', 'CheckoutBaseController@success')->name('checkout.success');
        Route::get('invoice/{order?}', 'InvoiceController@show')->name('invoice.show');
    });

    // Sitemap
    Route::get('/sitemap', 'SitemapController@index')->name('sitemap');

    Route::group(['middleware' => ['signed']], function () {
        Route::get('orders', 'ExportOrdersController@index')->name('export.output-orders');
    });

    Route::group(['middleware' => []], function () {

        Route::group(['middleware' => []], function() {
            Route::get('/', 'HomeController@index')->name('home');
            Route::get('privacy-policy', 'HomeController@privacy')->name('privacy-policy');
            Route::get('terms-and-conditions', 'HomeController@terms')->name('terms-and-conditions');
            Route::get('/about-us', 'HomeController@aboutUs')->name('about-us');
            Route::get('/faq', 'HomeController@faq')->name('faq');
            Route::get('/contact-us', 'HomeController@contact')->name('contact-us');
            Route::get('/shipping-and-return-policy', 'HomeController@shippingReturnPolicty')->name('shipping-return-policy');
        });

        Route::post('/contact-us', 'ContactController@contact')->name('contact');

        Route::get('quickorder', 'QuickorderController@index')->name('quickorder.index');
        Route::post('quickorder', 'QuickorderController@store')->name('quickorder.store');
        Route::post('/quickorder/{id}', 'QuickorderController@destroy')->name('quickorder.destroy');
        Route::get('quickorder/{id}', 'QuickorderController@move')->name('quickorder.move');

        Route::get('quoterequest', 'QuoteRequestController@index')->name('quoterequest.index');
        Route::get('/quotes/new', 'Voyager\QuotesController@new')->name('voyager.quotes.new');
        Route::post('/quotes', 'Voyager\QuotesController@store')->name('voyager.quotes.store');

        Route::post('/quoterequest/{id}', 'QuoteRequestController@destroy')->name('quoterequest.destroy');
        Route::post('/quoterequest/submit', 'QuoteRequestController@quoteRequest')->name('quoterequest.submit');

        Route::get('/quoterequest/search', 'QuoteRequestController@search')->name('quoterequest.search');
        Route::get('/quoterequest/convert-order/{order}', 'QuoteRequestController@convertOrder')->name('quoterequest.convertOrder');

        Route::get('/quoterequest/mail-order/{order}', 'QuoteRequestController@mailOrder')->name('quoterequest.mailOrder');

        Route::group(['middleware' => ['guest.cart:customer', 'injectOrderData']], function(){
            Route::get('guest-checkout', 'GuestCheckoutController@index')->name('guest.checkout.index');
            Route::post('guest-checkout', 'GuestCheckoutController@store')->name('guest.checkout.store');
        });

        Route::post('newsletter/subscribe', 'NewsletterController@store')->name('newsletter.subscribe');
        Route::post('track-cart', 'AbandonedCartController@store')->name('abandoned-cart.store');
        Route::get('/track-cart/{order}', 'AbandonedCartController@convertOrder')->name('abandoned-cart.convertOrder');

        Route::get('cart/couponcode/show', 'CartController@getCouponCode')->name('getCouponCode');
        Route::post('cart/couponcode', 'CartController@applyCouponCode')->name('applyCouponCode');
        Route::resource('cart', 'CartController')->only(['index', 'store', 'update', 'destroy']);

        Route::get('shipping', 'ShippingController@index')->name('shipping.index');
        Route::put('shipping', 'ShippingController@update')->name('shipping.update');
        Route::put('tax/{zipcode?}', 'TaxController@update')->name('tax.update');

        Route::resource('brand', 'BrandController')->only(['index', 'show']);

        Route::get("search", 'ProductController@search')->name('product.search');
        Route::post("search", 'ProductController@search')->name('ajax.product.search');
        Route::get('/favorites', 'FavoritesController@show')->name('favorites');

        Route::group(['middleware' => []], function() {
            Route::get('category/{category}', 'CategoryController@show')->name('category.filter');
            Route::post('category/{category?}', 'CategoryController@show')->name('category-ajax.filter');
            Route::get("product/{product}", 'ProductController@show')->name('product.index');
            Route::get("{product}", 'ProductController@show')->name('product.show');
        });

        Route::get('related-products/{product}', 'ProductController@relatedProducts')->name('product.related');
        Route::get('quick-view/{product}', 'ProductController@quickView')->name('product.quickview');
        Route::get('quick-order/{product}', 'ProductController@quickOrder')->name('product.quickOrder');
        Route::post('review/{product}', 'ProductController@review')->name('product.review');
    });

});
