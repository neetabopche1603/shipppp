<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\NotificationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Api For Login All Panels Client & Shipment
    Route::post('/loginAll', [ShipmentController::class, 'loginAll']);
/*
|--------------------------------------------------------------------------
|   Client API Routes
|--------------------------------------------------------------------------
|
*/

// CLient Registration and Login 
    Route::post('/registerClient', [UserController::class, 'registerClient']);
    Route::post('/login', [UserController::class, 'login']);
//Reset Password Api for Client
    Route::put('/resetPassword', [UserController::class, 'resetPassword']);
// Add Receipent By Client Api 
    Route::post('/addReceptionist', [UserController::class, 'addReceptionist']);
//Receptionist Login Api
    Route::post('/loginReceptionist', [UserController::class, 'loginReceptionist']);
// View Receptionist Detail
    // Route::get('/viewReceptionist/{id}', [UserController::class, 'viewReceptionist']);
    Route::get('/viewReceptionist', [UserController::class, 'viewReceptionist']);
// Client Profile Show Api
    Route::get('/clientProfile', [UserController::class, 'clientProfile']);
// Client Profile Update
    Route::post('/updateClientProfile', [UserController::class, 'updateClientProfile']);
// Delete Profile Client Api
    // Route::delete('/deleteProfile/{id}', [UserController::class, 'deleteProfile']);
    Route::get('/deactivateProfile', [UserController::class, 'deleteProfile']);
// Deactivate Receptionist profile by client
    Route::post('/deactivateProfileReceptionist', [UserController::class, 'deactivateProfileReceptionist']);
// Receptionist Profile Api
    Route::get('/receptionistProfile', [UserController::class, 'receptionistProfile']);
// Update Receptionist Profile Api
    Route::post('/updateReceptionistProfile', [UserController::class, 'updateReceptionistProfile']);
// Client Pickup dropOff Api
    Route::post('/clientDropoff', [UserController::class, 'clientDropoff']);
// Client Items Store And Display Api
    Route::post('/clientItem', [UserController::class, 'clientItem']);
    Route::post('/bookingItem', [UserController::class, 'bookingItem']);
    Route::get('/viewBookingItem', [UserController::class, 'viewBookingItem']);
    Route::get('/displayItem', [UserController::class, 'displayItem']);
// Client Add Bookings Api And Display All Bookings Api 
    Route::post('/bookingAdd' ,[UserController::class, 'bookingAdd']);
    Route::get('/viewBooking', [UserController::class, 'viewBooking']);
// Market Place Api 
    Route::post('/marketPlace', [UserController::class, 'marketPlace']);
// Api For View Market Place Bookings
    Route::get('/viewMarketPlace', [UserController::class, 'viewMarketPlace']);
// Card Detail Save Api
    Route::post('/saveCard', [UserController::class, 'saveCard']);    
// Dipslay Saved Cards Api
    Route::get('/viewCard', [UserController::class, 'viewCard']);
// Calculation in Booking Api
    Route::post('/calculation', [UserController::class, 'calculation']);
// List Transaction Api
    Route::get('/listTransaction', [UserController::class, 'listTransaction']);
// List Transaction Api
    Route::get('/listTransactionMarket', [UserController::class, 'listTransactionMarket']);
// Api For Updating Booking Status
    Route::post('/updateBooking', [UserController::class, 'updateBooking']);

    Route::post('/updateBooking1', [UserController::class, 'updateBooking1']);
// Api For Getting  Schedule Items Category List
    Route::post('/scheduleCategory', [UserController::class, 'scheduleCategory']);
// Api For Forget Password
    Route::post('/forgot-password', [UserController::class, 'forgotPassword']);
    // Route::post('/forgotPasswordClient', [UserController::class, 'forgotPasswordClient']);
// Api for Searching by Location From & to
    Route::post('/search', [UserController::class, 'search'])->name('search');
// Api for Searching by Title
    Route::post('/search-date', [UserController::class, 'searchdate'])->name('searchDate');
// Api For Searching By All Data
    Route::post('/search-all', [UserController::class, 'searchall']);
// Api For Social-Auth
    Route::post('/client-social-login',[UserController::class, 'client_socialLogin']);
// Api For Getting All Coupons
    Route::get('/all-coupons', [UserController::class, 'allCoupons']);
// Api For Getting Checking Coupons
    Route::post('/check-coupon', [UserController::class, 'checkCoupon']);
// Api For Getting Receptionist Details
    Route::post('/receptionist-details', [UserController::class, 'receptionistDetails']);
// Api For Receptionist Dashboard so Receptionist can see those bookings which assigned to him
    Route::get('/receptionistDashboard', [UserController::class, 'receptionistDashboard']);
// Api For Updating Status By Receptionist for booking
    Route::post('/receptionistStatus', [UserController::class, 'receptionistStatus']);
// Api For get Shipment COmpany by Searching its name and companyname in Client Panel
    Route::post('/shipmentnameSearch', [UserController::class, 'shipmentnameSearch']);
// Api for viewing all advertisment
    Route::get('/viewAdvertisment', [UserController::class, 'viewAdvertisment']);
// Api For Viewing all proposals to client
    Route::get('/viewProposal', [UserController::class, 'viewProposal']);
// APi For Accepting Proposal By Shipment Company
    Route::post('/acceptProposal', [UserController::class, 'acceptProposal']);
// Api For Getting Stats in Receptionist Dashboard
    Route::get('/receptionistStats', [UserController::class, 'receptionistStats']);
// Api For Permorming Search in Receptionist Dashboard
    Route::post('/receptionistSearch', [UserController::class, 'receptionistSearch']);
// Api For View Accepted Market Place Bookings
    Route::get('/viewAcceptedMarket', [UserController::class, 'viewAcceptedMarket']);
// Api For Updating Status By Receptionist for MarketPlace
    Route::post('/receptionistStatusMarket', [UserController::class, 'receptionistStatusMarket']);

// Api For verify otp 
    Route::post('/verifyOtp', [UserController::class, 'verifyOtp']); 

    // Api For resend otp 
    Route::post('/resendOtp', [UserController::class, 'resendOtp']); 

    
    Route::get('/settings', [UserController::class, 'settings']);

    Route::get('/subscriptionPlanList', [UserController::class, 'subscriptionPlanList']);

    Route::get('/subscriptionPlanList1', [UserController::class, 'subscriptionPlanList1']);


    Route::get('/getTimer', [UserController::class, 'getTimer']);
    
/*
|--------------------------------------------------------------------------
|   Shipment API Routes
|--------------------------------------------------------------------------
|
*/

// Shipment Registration And Login
    Route::post('/registerShipment', [ShipmentController::class, 'registerShipment']);
    Route::post('/loginShipment', [ShipmentController::class, 'loginShipment']);
// Shipment Profile Show Api
    Route::get('/shipmentProfile', [ShipmentController::class, 'shipmentProfile']);
// Shipment Profile Update
    Route::post('/updateShipmentProfile', [ShipmentController::class, 'updateShipmentProfile']);
// Shipment Update Documents Api
    Route::post('/updateDocs',[ShipmentController::class, 'updateDocs']);    
// Shipment Update Driving Licence Api
    Route::post('/updateDrivingLicence',[ShipmentController::class, 'updateDrivingLicence']);   
// Shipment Update Tax Paid Documents Api
    Route::post('/updateTaxDocs',[ShipmentController::class, 'updateTaxDocs']);   
// Shipment Company Add Employees Api
    Route::post('/addEmployee', [ShipmentController::class, 'addEmployee']);
// Schedule Shipment Api for shipment Company
    Route::post('/scheduleShipment', [ShipmentController::class, 'scheduleShipment']);
// Client View Scheduled Shipments Api
    Route::get('/viewScheduleShipment', [ShipmentController::class, 'viewScheduleShipment']);
// All Employees Login Api
    Route::post('/loginEmployee', [ShipmentController::class, 'loginEmployee']);
// Shipment Company And all Employees Profile Details Show
    Route::get('/employeeDetails', [ShipmentController::class, 'employeeDetails']);
// All Agents List Show
    Route::get('/pickupAgents', [ShipmentController::class, 'pickupAgents']);    
// Show Bookings With Pickup to Pickup Agent
    Route::get('/pickupBookings', [ShipmentController::class, 'pickupBookings']);
// Show Bookings With Pickup to Pickup Agent
    Route::get('/shipmentBookings', [ShipmentController::class, 'shipmentBookings']);
// Accountant And Manager Accept & Reject Orders (Market Place)
    Route::post('/acceptBooking', [ShipmentController::class, 'acceptBooking']);    
// Reset Password for Shipment Company And It's Employee
    Route::put('/passwordReset', [ShipmentController::class, 'passwordReset']);    
// Get all Items Category
    Route::get('/schedule-items', [ShipmentController::class, 'scheduleItem']);  
// Api To Get Schedule Shipment By Shipment Company  
    Route::get('/schedules', [ShipmentController::class, 'mySchedules']);
// Api To Get Orders Accept & Reject By Shipment Company
    Route::get('/confirmedOrders', [ShipmentController::class, 'confirmedOrders']);
// Api to Accept or Reject Confirmed Orders (Bookings)
    Route::post('/acceptOrders', [ShipmentController::class, 'acceptOrders']);
// APi to get data of Schedule By its ID
    Route::post('/scheduleDetail', [ShipmentController::class, 'scheduleDetail']);
// Api to get Selected Client All Booking Information
    Route::post('/clientBookings', [ShipmentController::class, 'clientBookings']);
// Api For Forget Password
    Route::post('/forgot-password-shipment', [ShipmentController::class, 'shipmentForgotPassword']);
    // Api For Shipment Social Login
    Route::post('/shipment-social-login',[ShipmentController::class, 'shipment_socialLogin']);
// Delete Profile Client Api
    Route::get('/deactivateProfileShipment', [ShipmentController::class, 'deleteProfileShipment']);
// Api for Searching by Title
    Route::post('/search-title', [ShipmentController::class, 'searchTitle'])->name('searchTitle');
    Route::post('/search-titles', [ShipmentController::class, 'searchTitles']);
// Route to get All Bookings to that Particular Schedule in Shipment Dashboard
    Route::post('/get-bookings', [ShipmentController::class, 'getBooking']);
// Api For View Market Place Bookings
    Route::get('/shipmentMarketPlace', [ShipmentController::class, 'shipmentMarketPlace']);
// Api For Sending Proposal To Client After Accepting Market Place Booking
    Route::post('/sendProposal', [ShipmentController::class, 'sendProposal']);
// APi for getting all bookings on shipment company schedules with status "confirmed"
    Route::get('/confirmedBookings', [ShipmentController::class, 'confirmedBookings']);
// APi for getting all bookings on shipment company schedules with status "accepted"
    Route::get('/shipmentOrders', [ShipmentController::class, 'shipmentOrders']);
// APi for getting all bookings on shipment company schedules with status "done"
    Route::get('/doneBookings', [ShipmentController::class, 'doneBookings']);
// Api for Getting Market Place Details
    Route::post('/market-details', [ShipmentController::class, 'marketDetails']);
// All Departure Manager List Show
    Route::get('/departureManager', [ShipmentController::class, 'departureManager']);  
// All Arrival Manager List Show
    Route::get('/arrivalManager', [ShipmentController::class, 'arrivalManager']);  
// Departure Warehouse Manager Dashboard Api
    Route::get('/departureDashboard', [ShipmentController::class, 'departureDashboard']);
// Departure Manager Assign Pickup Agent to Bookings
    Route::post('/assignAgent', [ShipmentController::class, 'assignAgent']);
// Pickup Agent Dashboard Api
    Route::get('/pickupDashboard', [ShipmentController::class, 'pickupDashboard']);
// APi for getting all bookings on Departure Warehouse Manager schedules with status "accepted"
    Route::get('/departureOrders', [ShipmentController::class, 'departureOrders']);
// APi for getting all bookings on Departure Warehouse Manager schedules with status "accepted"
    Route::get('/arrivalOrders', [ShipmentController::class, 'arrivalOrders']);
// Api For getting all panels of shipment company 
    Route::get('/shipmentEmployees', [ShipmentController::class, 'shipmentEmployees']);
// Api For Creating Categoris With Item
    Route::post('/addItems', [ShipmentController::class, 'addItems']);
// Api For Removing Categoris With Item
    Route::post('/removeItems', [ShipmentController::class, 'removeItems']);
// Api for Creating Warehouse by Shipment Company
    Route::post('/createWarehouse', [ShipmentController::class, 'createWarehouse']);
// Api for getting Warehouse List for assign Employee to it
    Route::get('/viewWarehouse', [ShipmentController::class, 'viewWarehouse']);
// Api For assigning manager to warehouse by shipment company
    Route::post('/assignManager', [ShipmentController::class, 'assignManager']);
// Statistics of Shipment Dashboard
    Route::get('/shipmentStats', [ShipmentController::class, 'shipmentStats']);
// Statistics of Pickup Agent Dashboard
    Route::get('/pickupStats', [ShipmentController::class, 'pickupStats']);
// Statistics of Pickup Agent Dashboard
    Route::get('/departureStats', [ShipmentController::class, 'departureStats']);
// Statistics of Pickup Agent Dashboard
    Route::get('/arrivalStats', [ShipmentController::class, 'arrivalStats']);
// Api For Updating Status By Shipment Company
    Route::post('/changeStatus', [ShipmentController::class, 'changeStatus']);
// Departure Warehouse Manager Dashboard Api
    Route::get('/arrivalDashboard', [ShipmentController::class, 'arrivalDashboard']);
// Api For Perform Search By Pickup Agent In Bookings
    Route::post('/pickup-search', [ShipmentController::class, 'pickupSearch']);
// Api For get Clients by Searching client name in Shipment Panel
    Route::post('/clientnameSearch', [ShipmentController::class, 'clientnameSearch']);
// Api For Deactivating Shipment Company Employee
    Route::post('/deactivateProfileEmployee', [ShipmentController::class, 'deactivateProfileEmployee']);
// Api For Getting Clients Previous Booking on particular shipment company
    Route::post('/previousBooking', [ShipmentController::class, 'previousBooking']);
// Departure Manager Assign Pickup Agent to Bookings
    Route::post('/assignAgentMarket', [ShipmentController::class, 'assignAgentMarket']);
// Api For View Accepted Market Place Bookings
    Route::get('/viewAcceptedMarketShipment', [ShipmentController::class, 'viewAcceptedMarketShipment']);
// Api For Forget Password
    Route::post('/all-forgot-password', [ShipmentController::class, 'allForgotPassword']);
// Api For Updating Status By Shipment Company (Market Place Booking)
    Route::post('/changeStatusMarket', [ShipmentController::class, 'changeStatusMarket']);
// Api For Edit Items Under Particular Ctaegory
    Route::post('/editItems', [ShipmentController::class, 'editItems']);
// Api For Pickup Agent Accept & Reject Booking
    Route::post('/pickupAccept', [ShipmentController::class, 'pickupAccept']);
// Api For Pickup Agent Accept & Reject Booking
    Route::post('/pickupAcceptMarket', [ShipmentController::class, 'pickupAcceptMarket']);
// Api For check Shipment Company Has Connected Its Stripe Account or Not
    Route::get('/check-stripe', [ShipmentController::class, 'checkStripe']);

    



/*
|--------------------------------------------------------------------------
|   Notification API Routes
|--------------------------------------------------------------------------
|
*/

// Api for getting Client Notifications
    Route::get('/clientNotification', [NotificationController::class, 'clientNotification']);
// Api for getting shipment company Notifications
    Route::get('/shipmentNotification', [NotificationController::class, 'shipmentNotification']);
// Api for give all shipment company notification
    Route::get('/allShipmentNotification', [NotificationController::class, 'allShipmentNotification']);
// Api For Clearing Client Notificatins
    Route::get('/clientClear', [NotificationController::class, 'clientClear']);
// Api For Clearing shipment company Notificatins
    Route::get('/shipmentClear', [NotificationController::class, 'shipmentClear']);
// Api for getting Client Notifications
    Route::get('/clientNotificationCount', [NotificationController::class, 'clientNotificationCount']);
// Api for getting Client Notifications
    Route::get('/shipmentNotificationCount', [NotificationController::class, 'shipmentNotificationCount']);
// Api For Sending Broadcast message to users
    Route::post('/broadcast', [NotificationController::class, 'broadcast']);
// Api to get Broadcast message
    Route::post('/getClientBroadcast', [NotificationController::class, 'getClientBroadcast']);
    Route::post('/getClientBroadcast1', [NotificationController::class, 'getClientBroadcast1']);


/*
|--------------------------------------------------------------------------
|   Reviews and Rating API Routes
|--------------------------------------------------------------------------
|
*/

/**
* Posting Client Feedback(Review) Api 
* Showing All Feedbacks to Client Api
*/
Route::post('/clientReview', [ReviewController::class, 'clientReview']);
Route::post('/clientReviewBlog', [ReviewController::class, 'clientReviewBlog']);
Route::post('/displayClientReview', [ReviewController::class, 'displayClientReview']);
Route::put('/clientReviewResponse', [ReviewController::class, 'clientReviewResponse']);
/**
* Posting Shipment Feedback(Review) Api 
* Showing All Feedbacks to Shipment Api
*/
Route::post('/shipmentReview', [ReviewController::class, 'shipmentReview']);
Route::get('/displayShipmentReview', [ReviewController::class, 'displayShipmentReview']);



/*
|--------------------------------------------------------------------------
|   Query's API Routes
|--------------------------------------------------------------------------
|
*/

Route::post('/clientQuery', [QueryController::class, 'clientQuery']);
Route::post('/shipmentQuery', [QueryController::class, 'shipmentQuery']);


Route::post('/imageUrl', [QueryController::class, 'imageUrl']);

/*
Cron Jon Route
*/
Route::get('/cron-job1', [QueryController::class, 'cronJob1']);
Route::get('/cron-job', [QueryController::class, 'cronJob']);
Route::get('/checkSchedule', [QueryController::class, 'checkSchedule']);
Route::get('/sendAlert', [QueryController::class, 'sendAlert']);
Route::get('/schedulePack', [QueryController::class, 'schedulePack']);
Route::get('/removeBooking', [QueryController::class, 'removeBooking']);
Route::get('/checkplanExpire', [QueryController::class, 'checkplanExpire']);