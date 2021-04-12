<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('invites', 'InviteController@store');
Route::get('invites/{invite}','InviteController@show')->name('invite');

Route::post('users/{invite}', 'UserController@store');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout');
Route::post('/reset-password', 'PasswordResetController@forgot');
Route::get('/get-token/{confirmationToken}', 'PasswordResetController@getToken');
Route::put('/reset-password/{confirmationToken}', 'PasswordResetController@resetPassword');

Route::get('/view-images/{id}', 'FileController@viewImagesById');
Route::delete('/delete-images/{id}', 'FileController@deleteImageById');

Route::post('/register-autoshops', 'AutoShopController@register');
Route::get('/view-autoshops/{id}', 'AutoShopController@viewAutoShopsById');
Route::get('/view-autoshops', 'AutoShopController@viewAutoShops');
Route::put('/update-autoshops/{id}', 'AutoShopController@updateAutoShopsById');
Route::delete('/delete-autoshops/{id}', 'AutoShopController@deleteAutoShopsById');

Route::get('/view-users/{id}', 'UserController@viewUsersById');
Route::get('/view-users', 'UserController@viewUsers');
Route::put('/update-users/{id}', 'UserController@updateUsersById');
Route::delete('/delete-users/{id}', 'UserController@deleteUsersById');

Route::post('/vehicleowners/autoshop/{autoshop}', 'VehicleOwnerController@register');
Route::get('/view-vehicle-owners/{id}', 'VehicleOwnerController@viewVehicleOwnersById');
Route::get('/view-vehicle-owners', 'VehicleOwnerController@viewVehicleOwners');
Route::put('/update-vehicleowners/{id}/autoshop/{autoshop}', 'VehicleOwnerController@updateVehicleOwnersById');
Route::delete('/delete-vehicle-owners/{id}', 'VehicleOwnerController@deleteVehicleOwnersById');

Route::post('/vehicles/vehicleOwner/{vehicleOwner}', 'VehicleController@register');
Route::get('/view-vehicles/{id}', 'VehicleController@viewVehiclesById');
Route::get('/view-vehicles', 'VehicleController@viewVehicles');
Route::put('/update-vehicles/{id}/vehicleOwner/{vehicleOwner}', 'VehicleController@updateVehiclesById');
Route::delete('/delete-vehicles/{id}', 'VehicleController@deleteVehiclesById');

Route::post('/work-orders/service/{service}', 'WorkOrderController@register');
Route::get('/view-work-orders/{id}', 'WorkOrderController@viewVehicleWorkOrdersById');
Route::get('/view/work-orders', 'WorkOrderController@viewVehicleServices');
Route::put('/update/workorders/{id}/service/{service}', 'WorkOrderController@updateVehicleWorkOrdersById');
Route::delete('/delete-vehicle-work-orders/{id}', 'WorkOrderController@deleteVehicleWorkOrdersById');

Route::post('/billings/{workOrder}/vehicleOwner/{vehicleOwner}', 'BillingController@billings');
Route::get('/view-billings/{id}', 'BillingController@viewBillingsById');
Route::get('/view-billings', 'BillingController@viewBillings');
Route::put('/update-billings/{id}/{workOrder}/vehicleOwner/{vehicleOwner}', 'BillingController@updateBillingsById');
Route::delete('/delete-billings/{id}', 'BillingController@deleteBillingsById');

Route::post('users/{user}/userprofiles', 'UserProfileController@store');
Route::get('users/{user}/userprofiles/{userProfile}', 'UserProfileController@show');
Route::put('users/{user}/userprofiles/{userProfile}', 'UserProfileController@update');
Route::delete('users/{user}/userprofiles/{userProfile}', 'UserProfileController@destroy');

Route::get('users/{user}/userprofiles/{userProfile}/image', 'UserProfileController@image');

Route::post('/service/{vehicle}/inventory{id}', 'ServiceController@services');
Route::get('/view-services/{id}', 'ServiceController@viewServicesId');
Route::get('/view-services', 'ServiceController@viewServices');
Route::put('/update-services/{id}/vehicle{id}/inventory/{id}', 'ServiceController@updateServices');
Route::delete('/delete-services/{id}', 'ServiceController@deleteServices');


Route::post('/estimate/{service}', 'EstimateController@estimates');
Route::put('/estimate-approved/{token}', 'EstimateController@estimatesApproved');
Route::get('/view-billings', 'EstimateController@viewEstimates');
Route::delete('/estimate/{id}', 'EstimateController@deleteEstimates');

Route::get('subscription','SubscriptionController@index')->name('subscription');

Route::get('subscribe/{subscription}', 'AutoshopSubscriptionController@initiateSubscription');
Route::post('subscribe', 'AutoshopSubscriptionController@store')->name('subscribe');
Route::delete('subscribe', 'AutoshopSubscriptionController@destroy');

Route::get('pay', 'PaymentController@show')->name('show-pay');
Route::post('pay', 'PaymentController@redirectToGateway')->name('pay');
Route::get('payment/callback', 'PaymentController@handleGatewayCallback');

Route::get('inventories', 'InventoryController@index');
Route::post('inventories', 'InventoryController@store');
Route::get('inventories/{inventory}', 'InventoryController@show');
Route::get('vehicles/{vehicle}/inventories', 'InventoryController@showAll');
Route::put('inventories/{inventory}', 'InventoryController@update');
Route::delete('inventories/{inventory}', 'InventoryController@destroy');

Route::namespace('Admin')->prefix('admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace

   Route::post('invites', 'InviteController@store');
   Route::get('invites/{invite}','InviteController@show')->name('invite');

   Route::post('users/{invite}', 'UserController@store');
   Route::get('users', 'UserController@index');
   Route::get('users/{user}', 'UserController@show');
   Route::get('', 'UserController@getAdminUsers');
   Route::get('autoshop-owners', 'UserController@getShopOwners');
   Route::get('autoshop-users/{autoshop}', 'UserController@getUsersByAutoshop');
   Route::post('users/{user}/update-role', 'UserController@updateUserRole');
   Route::put('users/{user}', 'UserController@update');
   Route::delete('users/{user}', 'UserController@destroy');

   Route::post('users/{user}/userprofiles', 'UserProfileController@store');
   Route::get('users/{user}/userprofiles/{userProfile}', 'UserProfileController@show');
   Route::put('users/{user}/userprofiles/{userProfile}', 'UserProfileController@update');
   Route::delete('users/{user}/userprofiles/{userProfile}', 'UserProfileController@destroy');

   Route::get('users/{user}/userprofiles/{userProfile}/image', 'UserProfileController@image');

   Route::get('roles','RoleController@index');
   Route::post('roles','RoleController@store');
   Route::get('roles/{role}','RoleController@show');
   Route::put('roles/{role}','RoleController@update');
   Route::delete('roles/{role}','RoleController@destroy');

   Route::get('subscription','SubscriptionController@index')->name('subscription');
   Route::post('subscription','SubscriptionController@store');
   Route::put('subscription/{subscription}','SubscriptionController@update');
   Route::delete('subscription/{subscription}','SubscriptionController@destroy');

   Route::get('autoshop','AutoshopController@index');
   Route::get('autoshop/{autoShop}','AutoshopController@show');
   Route::put('autoshop/{autoShop}','AutoshopController@update');
   Route::get('autoshop/{autoShop}/subscription-status','AutoshopController@subscriptionStatus');
   Route::get('autoshop/{autoShop}/payment-record','AutoshopController@paymentRecord');
   Route::put('autoshop/{autoShop}/enable','AutoshopController@enable');
   Route::put('autoshop/{autoShop}/disable','AutoshopController@disable');
   Route::delete('autoshop/{autoShop}','AutoshopController@destroy');

   Route::get('payments','PaymentController@index');
   Route::get('payments/{payment}','PaymentController@show');
   Route::put('payments/{payment}','PaymentController@updatePaymentStatus');
   Route::get('autoshop-payments/{autoshop}','PaymentController@getPaymentByAutoshop');
  // Route::delete('payments/{payment}','PaymentController@destroy');
});


