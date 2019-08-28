<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/info', 'BaseController@getInfo');

// ---
// Department End-Points
// ---
Route::group(['prefix' => 'departments'], function () {
    Route::get('/', 'DepartmentController@getAllDepartments');
    Route::get('/{department_id}', 'DepartmentController@getDepartmentById');

});

// ---
// Category End-Points
// ---
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', 'CategoryController@getAllCategories');
    Route::get('/{category_id}', 'CategoryController@getCategoryById');
    Route::get('/inProduct/{product_id}', 'CategoryController@getCategoryOfProduct');
    Route::get('/inDepartment/{department_id}', 'CategoryController@getCategoriesInDepartment');
});

// ---
// Attribute End-Points
// ---
Route::group(['prefix' => 'attributes'], function () {
    Route::get('/', 'AttributeController@getAllAttributes');
    Route::get('/{attribute_id}', 'AttributeController@getAttributeById');
    Route::get('/values/{attribute_id}', 'AttributeController@getAttributeValues');
    Route::get('/inProduct/{product_id}', 'AttributeController@getProductAttributes');
});

// --
// Product End-Points
// --
Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@getAllProducts');
    Route::get('/search', 'ProductController@searchProducts');
    Route::get('/{product_id}', 'ProductController@getProductById');
    Route::get('/inCategory/{category_id}', 'ProductController@getProductsInCategory');
    Route::get('/inDepartment/{department_id}', 'ProductController@getProductsInDepartment');
    Route::get('/{product_id}/reviews', 'ProductController@getProductReviews');
    Route::post('/{product_id}/reviews', 'ProductController@postProductReview');
});

// --
// Customer End-Points
// --
Route::post('/customers', 'CustomerController@createProfile');
Route::get('/customers/login', 'CustomerController@login');
Route::post('/customers/facebook', 'CustomerController@fbLogin');
Route::get('/customers', 'CustomerController@getCustomerProfile');
Route::put('/customer', 'CustomerController@updateCustomerProfile');
Route::put('/customer/address', 'CustomerController@updateCustomerAddress');
Route::put('/customer/creditCard', 'CustomerController@updateCreditCard');

// --
// Order End-Points
// --
Route::group(['prefix' => 'orders'], function () {
    Route::post('/', 'ShoppingCartController@createOrder');
    Route::get('/{order_id}', 'ShoppingCartController@getOrderById');
    Route::get('/inCustomer', 'ShoppingCartController@getCustomerOrders');
    Route::get('/shortDetail/{order_id}', 'ShoppingCartController@getOrderSummary');

});

// --
// Shopping Cart End-Points
// --
Route::group(['prefix' => 'shoppingcart'], function () {
    Route::get('/generateUniqueId', 'ShoppingCartController@generateUniqueCart');
    Route::post('/add', 'ShoppingCartController@addItemToCart');
    Route::get('/{cart_id}', 'ShoppingCartController@getCartDetails');
    Route::put('/update/{item_id}', 'ShoppingCartController@updateCartItem'); // different from docs
    Route::delete('/empty/{cart_id}', 'ShoppingCartController@emptyCart');
    Route::delete('/removeProduct/{item_id}', 'ShoppingCartController@removeItemFromCart');
});

// --
// Tax End-Points
// --
Route::get('/tax', 'TaxController@getAllTax');
Route::get('/tax/{tax_id}', 'TaxController@getTaxById');

// --
// Shipping End-Points
// --
Route::get('/shipping/regions', 'ShippingController@getShippingRegions');
Route::get('/shipping/regions/{shipping_region_id}', 'ShippingController@getShippingInRegion');


// --
// Stripe End-Point
// --
Route::post('/stripe/charge', 'ShoppingCartController@processStripePayment');

