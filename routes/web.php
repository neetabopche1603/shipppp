<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/phpinfo', function () {
    return phpinfo();
})->name('phpinfo');


Route::get('/send', function () {
$data = "helloooo";

 Mail::send([], [], function($message) { 
 $message->to('nikhily86.ns@gmail.com', 'me')->subject('Welcome!'); });
});

Route::get('stripe', [App\Http\Controllers\StripeController::class, 'stripe']);
Route::post('stripe-pay', [App\Http\Controllers\StripeController::class, 'stripePost'])->name('stripe.post');

Route::get('/connect', [App\Http\Controllers\StripeController::class, 'index']);
Route::get('/redirect', [App\Http\Controllers\StripeController::class, 'redirect']);


Route::get('stripemob', [App\Http\Controllers\StripeController::class, 'stripemob']);


// Dashboard

Auth::routes();
// Auth::routes(['register' => false]);

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard')->middleware('isAdmin');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('admin.dashboard')->middleware('isAdmin');

Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/set-timer', [App\Http\Controllers\AdminController::class, 'timer'])->name('admin.timer');
    Route::post('/setTimer', [App\Http\Controllers\AdminController::class, 'setTimer'])->name('admin.setTimer');
    //All secure URL's for Admin
    Route::get('/profiles', [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/updateProfile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::post('/resetAdminPassword', [App\Http\Controllers\AdminController::class, 'resetAdminPassword'])->name('admin.resetAdminPassword');
    Route::get('/clients', [App\Http\Controllers\AdminController::class, 'clients'])->name('admin.clients');
    Route::post('/updateClientProfile', [App\Http\Controllers\AdminController::class, 'updateClientProfile'])->name('admin.updateClientProfile');
    Route::post('/updateShipmentProfile', [App\Http\Controllers\AdminController::class, 'updateShipmentProfile'])->name('admin.updateShipmentProfile');
    Route::post('/updateDocs', [App\Http\Controllers\AdminController::class, 'updateDocs'])->name('admin.updateDocs');
    Route::post('/updateTax', [App\Http\Controllers\AdminController::class, 'updateTax'])->name('admin.updateTax');
    Route::post('/updateDrivingLicence', [App\Http\Controllers\AdminController::class, 'updateDrivingLicence'])->name('admin.updateDrivingLicence');
    Route::get('/viewClientBook/{id}', [App\Http\Controllers\AdminController::class, 'viewClientBook'])->name('admin.viewClientBook');

    Route::get('/clientDetails/{id}', [App\Http\Controllers\AdminController::class, 'clientDetails'])->name('admin.clientDetails');
    Route::get('/clientDetailss/{name}', [App\Http\Controllers\AdminController::class, 'clientDetailss'])->name('admin.clientDetailss');


    Route::get('/shipmentDetails/{id}', [App\Http\Controllers\AdminController::class, 'shipmentDetails'])->name('admin.shipmentDetails');

    Route::get('/changeClientStatus/{id}', [App\Http\Controllers\AdminController::class, 'changeClientStatus'])->name('admin.changeClientStatus');
    Route::get('/changeShipmentStatus/{id}', [App\Http\Controllers\AdminController::class, 'changeShipmentStatus'])->name('admin.changeShipmentStatus');
    Route::get('/changeClientMarketStatus/{id}', [App\Http\Controllers\AdminController::class, 'changeClientMarketStatus'])->name('admin.changeClientMarketStatus');
    Route::post('/updateStatus', [App\Http\Controllers\AdminController::class, 'updateStatus'])->name('admin.updateStatus');
    Route::post('/updateStatusShipment', [App\Http\Controllers\AdminController::class, 'updateStatusShipment'])->name('admin.updateStatusShipment');
    Route::post('/updateClientMarketStatus', [App\Http\Controllers\AdminController::class, 'updateClientMarketStatus'])->name('admin.updateClientMarketStatus');
    Route::get('/shipment', [App\Http\Controllers\AdminController::class, 'shipment'])->name('admin.shipment');
    Route::post('/deleteClient/{id}', [App\Http\Controllers\AdminController::class, 'deleteClient'])->name('admin.deleteClient');
    Route::post('/deleteShipment/{id}', [App\Http\Controllers\AdminController::class, 'deleteShipment'])->name('admin.deleteShipment');

    Route::get('/scheduleShipment', [App\Http\Controllers\AdminController::class, 'scheduleShipment'])->name('admin.scheduleShipment');
    Route::get('/scheduleShipmentDetails/{id}', [App\Http\Controllers\AdminController::class, 'scheduleShipmentDetails'])->name('admin.scheduleShipmentDetails');
    
    Route::post('/deleteSchedule/{id}', [App\Http\Controllers\AdminController::class, 'deleteSchedule'])->name('admin.deleteSchedule');
    Route::get('/viewBooking', [App\Http\Controllers\AdminController::class, 'viewBooking'])->name('admin.viewBooking');
    Route::get('/viewBookingDetails/{id}', [App\Http\Controllers\AdminController::class, 'viewBookingDetails'])->name('admin.viewBookingDetails');
    Route::get('/bookingDetails/{id}', [App\Http\Controllers\AdminController::class, 'bookingDetails'])->name('admin.bookingDetails');

    Route::post('/deleteBooking/{id}', [App\Http\Controllers\AdminController::class, 'deleteBooking'])->name('admin.deleteBooking');
    Route::get('/clientBook/{id}', [App\Http\Controllers\AdminController::class, 'clientBook'])->name('admin.clientBook');
    Route::get('/viewAdsManagement', [App\Http\Controllers\AdminController::class, 'viewAdsManagement'])->name('admin.viewAdsManagement');
    Route::get('/viewAdvertisment', [App\Http\Controllers\AdminController::class, 'viewAdvertisment'])->name('admin.viewAdvertisment');
    Route::post('/createAdvertisment', [App\Http\Controllers\AdminController::class, 'createAdvertisment'])->name('admin.createAdvertisment');
    Route::post('/deleteAdvertisment/{id}', [App\Http\Controllers\AdminController::class, 'deleteAdvertisment'])->name('admin.deleteAdvertisment');
    Route::get('/createUsers', [App\Http\Controllers\AdminController::class, 'createUsers'])->name('admin.createUsers');
    Route::get('/viewUsers', [App\Http\Controllers\AdminController::class, 'viewUser'])->name('admin.viewUser');
    Route::post('/createUser', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.createUser');
    Route::get('/updateUserDetails/{id}', [App\Http\Controllers\AdminController::class, 'updateUserDetails'])->name('admin.updateUserDetails');
    Route::post('/updateUser', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::post('/deleteUser/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.deleteUser');

    Route::get('/viewScheduleDetails/{id}', [App\Http\Controllers\AdminController::class, 'viewScheduleDetails'])->name('admin.viewScheduleDetails');
    Route::get('/viewShipmentDocs/{id}', [App\Http\Controllers\AdminController::class, 'viewShipmentDocs'])->name('admin.viewShipmentDocs');
    Route::get('/createBlogs', [App\Http\Controllers\AdminController::class, 'createBlogs'])->name('admin.createBlogs');
    Route::get('/viewBlog', [App\Http\Controllers\AdminController::class, 'viewBlog'])->name('admin.viewBlog');
    Route::post('/createBlog', [App\Http\Controllers\AdminController::class, 'createBlog'])->name('admin.createBlog');
    Route::post('/deleteBlog/{id}', [App\Http\Controllers\AdminController::class, 'deleteBlog'])->name('admin.deleteBlog');
    Route::get('/scheduleStatusDetails/{id}', [App\Http\Controllers\AdminController::class, 'scheduleStatusDetails'])->name('admin.scheduleStatusDetails');
    Route::post('/scheduleStatus', [App\Http\Controllers\AdminController::class, 'scheduleStatus'])->name('admin.scheduleStatus');
    Route::get('/viewReviewDetails/{id}', [App\Http\Controllers\AdminController::class, 'viewReviewDetails'])->name('admin.viewReviewDetails');
    Route::get('/marketPlace', [App\Http\Controllers\AdminController::class, 'marketPlace'])->name('admin.marketPlace');
    Route::get('/marketDetails/{id}', [App\Http\Controllers\AdminController::class, 'marketDetails'])->name('admin.marketDetails');

    Route::get('/viewSupport', [App\Http\Controllers\AdminController::class, 'viewSupport'])->name('admin.viewSupport');
    Route::get('/viewQuery/{id}', [App\Http\Controllers\AdminController::class, 'viewQuery'])->name('admin.viewQuery');
    Route::get('/changeQueryStatus/{id}', [App\Http\Controllers\AdminController::class, 'changeQueryStatus'])->name('admin.changeQueryStatus');
    Route::get('/queryDetails/{id}', [App\Http\Controllers\AdminController::class, 'queryDetails'])->name('admin.queryDetails');
    Route::post('/updateQueryStatus', [App\Http\Controllers\AdminController::class, 'updateQueryStatus'])->name('admin.updateQueryStatus');

    Route::get('generatepdf/{id}', [App\Http\Controllers\AdminController::class, 'generatePDF'])->name('admin.generatePDF');
    Route::get('generatepdft/{id}', [App\Http\Controllers\AdminController::class, 'generatePDFT'])->name('admin.generatePDFT');
    Route::get('generatepdfd/{id}', [App\Http\Controllers\AdminController::class, 'generatePDFD'])->name('admin.generatePDFD');

    Route::get('/createCategory', [App\Http\Controllers\AdminController::class, 'createCategory'])->name('admin.createCategory');
    Route::get('/viewCategory', [App\Http\Controllers\AdminController::class, 'viewCategory'])->name('admin.viewCategory');
    Route::post('/createCategories', [App\Http\Controllers\AdminController::class, 'createCategories'])->name('admin.createCategories');
    Route::post('/deleteCategory/{id}', [App\Http\Controllers\AdminController::class, 'deleteCategory'])->name('admin.deleteCategory');

    Route::get('/createItems', [App\Http\Controllers\AdminController::class, 'createItems'])->name('admin.createItems');
    Route::get('/viewItems', [App\Http\Controllers\AdminController::class, 'viewItem'])->name('admin.viewItem');
    Route::post('/createItem', [App\Http\Controllers\AdminController::class, 'createItem'])->name('admin.createItem');
    Route::get('/updateItemDetails/{id}', [App\Http\Controllers\AdminController::class, 'updateItemDetails'])->name('admin.updateItemDetails');
    Route::post('/updateItem', [App\Http\Controllers\AdminController::class, 'updateItem'])->name('admin.updateItem');
    Route::post('/deleteItem/{id}', [App\Http\Controllers\AdminController::class, 'deleteItem'])->name('admin.deleteItem');

    Route::get('/viewCoupons', [App\Http\Controllers\AdminController::class, 'viewCoupon'])->name('admin.viewCoupons');
    Route::post('/deleteCoupon/{id}', [App\Http\Controllers\AdminController::class, 'deleteCoupon'])->name('admin.deleteCoupon');
    Route::get('/createCoupons', [App\Http\Controllers\AdminController::class, 'createCoupons'])->name('admin.createCoupons');
    Route::post('/createCoupon', [App\Http\Controllers\AdminController::class, 'createCoupon'])->name('admin.createCoupon');
    Route::get('/updateCoupon/{id}', [App\Http\Controllers\AdminController::class, 'updateCoupon'])->name('admin.updateCoupon');
    Route::post('/updateCoupons', [App\Http\Controllers\AdminController::class, 'updateCoupons'])->name('admin.updateCoupons');

    Route::get('/getNotification', [App\Http\Controllers\AdminController::class, 'getNotification'])->name('admin.getNotification');
    Route::get('/mark-read', [App\Http\Controllers\AdminController::class, 'mark'])->name('admin.mark');

    Route::get('/bookingPayment', [App\Http\Controllers\AdminController::class, 'bookingPayment'])->name('admin.bookingPayment');
    Route::get('/marketPayment', [App\Http\Controllers\AdminController::class, 'marketPayment'])->name('admin.marketPayment');


    Route::get('/createPlans', [App\Http\Controllers\AdminController::class, 'createPlans'])->name('admin.createPlans');
    Route::post('/createPlan', [App\Http\Controllers\AdminController::class, 'createPlan'])->name('admin.createPlan');

    Route::get('/viewPlans', [App\Http\Controllers\AdminController::class, 'viewPlan'])->name('admin.viewPlans');

    Route::post('/deletePlan/{id}', [App\Http\Controllers\AdminController::class, 'deletePlan'])->name('admin.deletePlan');

     Route::get('/updatePlan/{id}', [App\Http\Controllers\AdminController::class, 'updatePlan'])->name('admin.updatePlan');
    Route::post('/updatePlans', [App\Http\Controllers\AdminController::class, 'updatePlans'])->name('admin.updatePlans');

    Route::get('/viewTransactions', [App\Http\Controllers\AdminController::class, 'viewTransactions'])->name('admin.viewTransactions');

    Route::get('/viewTransactionDetails/{id}', [App\Http\Controllers\AdminController::class, 'viewTransactionDetails'])->name('admin.viewTransactionDetails');
   
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    //All secure URL's For SubAdmin 
    Route::get('/subAdminModule', [App\Http\Controllers\SubadminController::class, 'module'])->name('subadmin.modules');

    Route::get('/subAdminClients', [App\Http\Controllers\SubadminController::class, 'subAdminClients'])->name('subadmin.subAdminClients');
    Route::get('/subAdminShipment', [App\Http\Controllers\SubadminController::class, 'subAdminShipment'])->name('subadmin.subAdminShipment');
    Route::post('/subAdminDeleteShipment/{id}', [App\Http\Controllers\SubadminController::class, 'subAdminDeleteShipment'])->name('subadmin.subAdminDeleteShipment');
    
    Route::get('/subAdminScheduleShipment', [App\Http\Controllers\SubadminController::class, 'subAdminScheduleShipment'])->name('subadmin.subAdminScheduleShipment');
    Route::post('/subAdminDeleteSchedule/{id}', [App\Http\Controllers\SubadminController::class, 'subAdminDeleteSchedule'])->name('subadmin.subAdminDeleteSchedule');
    Route::get('/subAdminViewScheduleDetails/{id}', [App\Http\Controllers\SubadminController::class, 'subAdminViewScheduleDetails'])->name('subadmin.subAdminViewScheduleDetails');

    Route::get('/subAdminProfiles', [App\Http\Controllers\SubadminController::class, 'subAdminProfile'])->name('subadmin.subAdminProfile');
    Route::post('/subAdminUpdateProfile', [App\Http\Controllers\SubadminController::class, 'subAdminUpdateProfile'])->name('subadmin.subAdminUpdateProfile');
    Route::post('/subAdminResetAdminPassword', [App\Http\Controllers\SubadminController::class, 'subAdminResetAdminPassword'])->name('subadmin.subAdminResetAdminPassword');

    Route::get('/subAdminViewBooking', [App\Http\Controllers\SubadminController::class, 'subAdminViewBooking'])->name('.subAdminViewBooking');
    Route::post('/subAdmindeleteBooking/{id}', [App\Http\Controllers\SubadminController::class, 'subAdmindeleteBooking'])->name('subadmin.subAdmindeleteBooking');
    Route::get('/subAdminViewAdsManagement', [App\Http\Controllers\SubadminController::class, 'subAdminViewAdsManagement'])->name('subadmin.subAdminViewAdsManagement');
    Route::get('/subAdminViewAdvertisment', [App\Http\Controllers\SubadminController::class, 'subAdminViewAdvertisment'])->name('subadmin.subAdminViewAdvertisment');
    Route::post('/subAdmincreateAdvertisment', [App\Http\Controllers\SubadminController::class, 'subAdmincreateAdvertisment'])->name('subadmin.subAdmincreateAdvertisment');
    Route::post('/subAdmindeleteAdvertisment/{id}', [App\Http\Controllers\SubadminController::class, 'subAdmindeleteAdvertisment'])->name('subadmin.subAdmindeleteAdvertisment');

});

// Route::get('/linkstorage', function () {
//     Artisan::call('storage:link');
// });
