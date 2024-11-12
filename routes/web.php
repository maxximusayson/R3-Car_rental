<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\addNewAdminController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\clientCarController;
use App\Http\Controllers\adminDashboardController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;
use App\Models\Car;
use App\Models\Reservation;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadController;
use App\Models\AuditTrail;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\CarSearchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\CurlTestController;
use App\Http\Controllers\FailedController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuccessController;
use App\Http\Controllers\TestFilterController;
use Illuminate\Auth\Events\Failed;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CMSController;
use App\Http\Controllers\GpsLogController;
use App\Http\Controllers\GpsTrackingController;
use App\Http\Controllers\LastKnownLocationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\GalleryImageController;


// ------------------- customer routes --------------------------------------- //
Route::get('/', function () {
    $cars = Car::take(6)->where('status', '=', 'available')->get();
    return view('home', compact('cars'));
})->name('home');

Route::get('/cars', [clientCarController::class, 'index'])->name('cars');


Route::get('location', function () {
    return view('location');
})->name('location');

Route::get('contact_us', function () {
    return view('contact_us');
})->name('contact_us');

Route::get('reviews', function () {
    return view('reviews');
})->name('reviews');

Route::get('auth/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('auth/login', [LoginController::class, 'login'])->name('admin.login.submit');
// web.php




Route::redirect('/admin', 'admin/login');

Route::get('/privacy_policy',
function () {
    return view('Privacy_Policy');
})->name('privacy_policy');

Route::get('/terms_conditions',
function () {
    return view('Terms_Conditions');
})->name('terms_conditions');



// ------------------- admin routes --------------------------------------- //

Route::prefix('admin')->middleware('admin')->group(function () {

    Route::get(
        '/dashboard',
        adminDashboardController::class
    )->name('adminDashboard');

    Route::resource('cars', CarController::class);



    Route::get('/users', function () {

        $admins = User::where('role', 'admin')->get();
        $clients = User::where('role', 'client')->paginate(5);

        return view('admin.users', compact('admins', 'clients'));
    })->name('users');

    Route::get('/auditTrail', [adminDashboardController::class, 'auditTrail'])->name('auditTrail');

    


// Define resourceful routes for posts
Route::resource('posts', 'PostController');
    

Route::get('/updateReservation/{reservation}', [ReservationController::class, 'editStatus'])->name('editStatus');
# Edit status
Route::put('/updateReservation/{reservation}', [ReservationController::class, 'updateStatus'])->name('updateStatus');
# Edit payment
Route::get('/editPayment/{reservation}', [ReservationController::class, 'editPayment'])->name('editPayment');
# Edit car status
Route::put('/updatePayment/{reservation}', [ReservationController::class, 'updatePayment'])->name('updatePayment');
# Edit mode of payment
Route::get('/reservations/{reservation}/editPaymentMode', [ReservationController::class, 'editPaymentMode'])
    ->name('editPaymentMode');
    Route::put('/admin/reservations/{reservation}/updatePaymentMode', [ReservationController::class, 'updatePaymentMode'])
    ->name('updatePaymentMode');


    






    Route::get('/reservations/{id}', [ReservationController::class, 'showReservation'])->name('reservations.show');

    Route::get('/reservations', 'App\Http\Controllers\ReservationController@index')->name('admin.dashboard');

    Route::get('/reservation/{id}', 'ReservationController@showReservation')->name('showReservation');

    Route::delete('/users/{user}', [ReservationController::class, 'destroy'])->name('deleteUser');



    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroyReservation'])->name('deleteReservation');
    Route::delete('/users/{user}', [ReservationController::class, 'destroyUser'])->name('deleteUser');



    // Route::get('/edit-homepage', 'HomeController@editHomepage')->name('homepage.edit');
    // Route::post('/update-homepage', 'HomeController@updateHomepage')->name('homepage.update');
    
    // Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about_us');
    // Route::post('/update-about-us', [HomeController::class, 'updateAboutUs'])->name('about_us.update');


    // Route for editing the homepage content
Route::get('/homepage/edit', [ContentController::class, 'editHomepage'])->name('homepage.edit');

// Route for updating the homepage content
Route::post('/homepage/update', [ContentController::class, 'updateHomepage'])->name('homepage.update');

// Route for editing the About Us content
Route::get('/about-us/edit', [ContentController::class, 'editAboutUs'])->name('about_us.edit');

// Route for updating the About Us content
Route::post('/about-us/update', [ContentController::class, 'updateAboutUs'])->name('about_us.update');


Route::get('/audittrail', [AuditTrailController::class, 'index'])->name('audittrail.index');


    
});

// --------------------------------------------------------------------------//




// ------------------- client routes --------------------------------------- //

Route::get('/reservations/{car}', [ReservationController::class, 'create'])->name('car.reservation')->middleware('auth', 'restrictAdminAccess');
Route::post('/reservations/{car}', [ReservationController::class, 'store'])->name('car.reservationStore')->middleware('auth', 'restrictAdminAccess');

Route::get('/reservations', function () {

    $reservations = Reservation::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
    return view('clientReservations', compact('reservations'));
})->name('clientReservation')->middleware('auth', 'restrictAdminAccess');

Route::resource('clients', ClientController::class);



route::get('invoice/{reservation}', [invoiceController::class, 'invoice'])->name('invoice')->middleware('auth', 'restrictAdminAccess');


Route::get('/reservations/delete/{id}', 'App\Http\Controllers\ReservationController@cancelReservation')
    ->name('cancel_reservation');




    Route::post('/cars/{car}/rate', [CarController::class, 'rate'])->name('car.rate');
    Route::get('/cars/{car}/rating', [CarController::class, 'showRatingForm'])->name('car.rating');

    Route::get('/pdf-invoice/{reservation}', [InvoiceController::class, 'pdfInvoice'])->name('pdfInvoice');
    Route::get('/invoice/{reservation_id}', [InvoiceController::class, 'invoice'])->name('invoice');
    


    Auth::routes();

    Route::post('send', [ChatBotController::class, 'sendChat']);

    Route::resource('insurances', InsuranceController::class);
    
    Route::get('/terms_conditions', function () {
        return view('Terms_Conditions'); 
    })->name('terms_conditions');
    

   


    Route::get('/reservations/{reservation}/edit-payment-mode', 'ReservationController@editPaymentMode')->name('reservation.edit-payment-mode');
    Route::put('/reservations/{reservation}/update-payment-mode', 'ReservationController@updatePaymentMode')->name('reservation.update-payment-mode');




Route::get('/upload', [FileUploadController ::class, 'showForm']);
Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
Route::get('/upload-form', [FileUploadController::class, 'showForm'])->name('upload.form');




// Route for showing the upload form
Route::get('/upload', [FileUploadController::class, 'showForm'])->name('upload.form');

// Route for submitting the upload form
Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');

// Route for handling file uploads
Route::post('/upload-files', [FileUploadController::class, 'upload'])->name('uploadFiles');





//---------------------------------------------------------------------------//



Route::get('/test', function () {
    return view('test');
})->name('test');


// new routes
Route::get('/car/{id}', [CarController::class, 'show'])->name('car.details');
Route::get('/car/{car}', [CarController::class, 'show'])->name('car.details');


// chatbot

Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);




Route::get('/car/{car}', [CarController::class, 'show'])->name('car.details');

Route::post('webhook-receiver',[WebhookController::class,'webhook'])->name('webhook');

Route::post('/upload-profile-picture', [UsersController::class, 'uploadProfilePicture'])->name('uploadProfilePicture');


Route::post('/upload-profile-picture', [UsersController::class, 'uploadProfilePicture'])
    ->name('uploadProfilePicture')
    ->middleware('auth');

    Route::post('/profile/remove-picture', [ProfileController::class, 'removePicture'])->name('profile.removePicture');
  


// Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');





Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/uploadFiles', [ProfileController::class, 'uploadFiles'])->name('uploadFiles');


Route::get('/payments', [ReservationController::class, 'showPayments'])->name('payments');
Route::get('/reservation/payments', [ReservationController::class, 'showPaymentOptions'])->name('reservation.payments');



// routes/web.php
Route::get('/paymongo/redirect', [PaymentController::class, 'redirectToPayMongo'])->name('paymongo.redirect');


Route::get('/reservation/thankyou', [ReservationController::class, 'thankyou'])->name('reservation.thankyou');

Route::get('/reservation/create/{car_id}', [ReservationController::class, 'create'])->name('reservation.create');
Route::get('/reservation/done', [ReservationController::class, 'done'])->name('reservation.done');
Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::get('/admin/audit-trail', [adminDashboardController::class, 'auditTrail'])->name('admin.audit-trail');

// Route::get('/clients/{client}', [usersController::class, 'show'])->name('clients.show');
// Route::get('/clients/{client}', [usersController::class, 'show'])->name('clients.show');


Route::post('/upload-files', [FileUploadController::class, 'uploadFiles'])->name('uploadFiles');
Route::get('/rent-details/{reservation}', [ReservationController::class, 'show'])->name('rentDetails');


Route::post('/request-otp', [RegisterController::class, 'requestOtp'])->name('request.otp');
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify.otp');

Route::post('/register/request-otp', [RegisterController::class, 'requestOtp'])->name('request.otp');
Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify.otp');



route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'requestOtp'])->name('request.otp');
Route::get('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/otp', [OTPController::class, 'sendMessage'])->name('send.otp');


Route::post('/password/phone', [ForgotPasswordController::class, 'sendResetLinkViaPhone'])->name('password.phone');


// Paymongo
// Route::post('/paymongo/success', [SuccessController::class, 'success'])->name('paymongo.success');
// Route::get('/paymongo/success', [SuccessController::class, 'success'])->name('paymongo.success');

// Route::post('/paymongo/failed', [FailedController::class, 'failed'])->name('paymongo.failed');
// Route::get('/paymongo/failed', [FailedController::class, 'failed'])->name('paymongo.failed');

// Route::post('/paymongo/create-source', [PaymentController::class, 'createSource'])->name('paymongo.createSource');
Route::post('/paymongo/paid', [PaymentController::class, 'paymentSuccess'])->name('paymongo.paid');
Route::get('/paymongo/success', function () {
    // Handle success
    return view('paymongo.success');
})->name('paymongo.success');

Route::get('/paymongo/failed', function () {
    // Handle failure
    return view('paymongo.failed');
})->name('paymongo.failed');

Route::get('/paymongo/paid', function () {
    // Handle paid
    return view('paymongo.paid');
})->name('paymongo.paid');

// Going back to reservation form
Route::get('/reservations/{id}', [ReservationController::class, 'show'])->name('reservations.show');


Route::get('/test-curl', [CurlTestController::class, 'testCurl']);
Route::get('/test-filter', [TestFilterController::class, 'testFilter']);

Route::post('/request-otp', [OTPController::class, 'sendOtp'])->name('request.otp');




// search filter
Route::get('/car/search', [CarController::class, 'search'])->name('car.search');

// users
Route::get('/users', [UsersController::class, 'index'])->name('users.index');




// Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'login']);
// Route::post('2fa/verify', [LoginController::class, 'verify2fa'])->name('2fa.verify');

// Add this line to your routes/web.php
// Route::post('/2fa/resend', [App\Http\Controllers\TwoFactorController::class, 'resendCode'])->name('2fa.resend');
Route::post('/2fa/resend', [App\Http\Controllers\Auth\LoginController::class, 'resend2FA'])->name('2fa.resend');

// Route::post('/password/resend-code', [PasswordResetController::class, 'resendCode'])->name('password.resend.code');

// Route to show the 2FA method selection form
Route::get('/2fa/verify', [App\Http\Controllers\Auth\LoginController::class, 'show2FAVerifyForm'])->name('2fa.verify');

// Route to handle sending OTP (based on the method)
Route::post('/2fa/send', [App\Http\Controllers\Auth\LoginController::class, 'send2FA'])->name('2fa.send');

// Route to verify OTP after entering the code
Route::post('/verify-otp', [App\Http\Controllers\Auth\LoginController::class, 'verify2FA'])->name('verify.otp');

// Route to resend OTP (if the user requests it)
Route::post('/2fa/resend', [App\Http\Controllers\Auth\LoginController::class, 'resend2FA'])->name('2fa.resend');
Route::post('resend-2fa', [\App\Http\Controllers\AdminAuth\LoginController::class, 'resend2FA'])->name('2fa.resend');

Route::post('resend-2fa', [\App\Http\Controllers\AdminAuth\LoginController::class, 'resend2FA'])->name('2fa.resend');


// Route::post('/request-otp', [OTPController::class, 'requestOtp'])->name('request.otp');
Route::post('/verify-otp', [OTPController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/registration-success', function () {
    return view('registration-success');
})->name('registration.success');


//otp
// Route to display the registration form
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Route to handle OTP request
Route::post('/send-otp', [OTPController::class, 'sendOtp'])->name('send.otp');
// Route to handle OTP verification (display OTP form and verify OTP)
Route::post('/verify-otp', [OTPController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/register/verify-otp', [OTPController::class, 'verifyOtp'])->name('register.verify-otp');






// web.php
Route::get('/verify-otp', function () {
    // Change 'verify' to the correct view name, for example, 'auth.2fa-verify' 
    return view('auth.2fa-verify'); // Ensure this matches your file structure
})->name('verify.otp');

// Add this route for resending the OTP
Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('register.resend-otp');
Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->name('register.resend-otp');
// Route for requesting OTP
Route::post('/request-otp', [RegisterController::class, 'requestOtp'])->name('register.request-otp');

// Route for verifying OTP
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('register.verify-otp');
// Calendar
// Route::get('/available-dates', [ReservationController::class, 'getAvailableDates']);


//car search sa homepage

Route::post('/search-cars', [CarSearchController::class, 'search'])->name('search.cars');
Route::get('/search-cars', [CarController::class, 'searchCars'])->name('search-cars');
Route::post('/search-cars', [CarController::class, 'searchCars'])->name('search-cars');

// upload pic/id
Route::post('/uploadFiles', [FileUploadController::class, 'uploadFiles'])->name('uploadFiles');
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');


// details view,  nakikita ni admin yung details

Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');
Route::post('/clients/import', [ClientController::class, 'import'])->name('clients.import');
Route::get('/clients/{id}', [ClientController::class, 'show'])->name('clients.show');




// CSV

Route::get('/notifications/export', [ReservationController::class, 'exportNotifications'])->name('notifications.export');
Route::post('/notifications/import', [ReservationController::class, 'importNotifications'])->name('notifications.import');


// ratings
Route::resource('ratings', RatingController::class)->only(['store', 'update', 'destroy']);

// settings admin

Route::get('/settings', [SettingsController::class, 'setting'])->name('settings');
Route::post('/settings/add-admin', [SettingsController::class, 'addAdmin'])->name('settings.addAdmin');


// gps
Route::get('/gps-tracking', [GpsTrackingController::class, 'track'])->name('gps.tracking');

Route::post('/last-known-locations', [LastKnownLocationController::class, 'store']);


Route::post('/save-last-known-location', [GpsTrackingController::class, 'saveLastKnownLocation']);
Route::get('/save-last-known-location', [GpsTrackingController::class, 'fetchLastKnownLocation']);



// Example of a route to handle saving the last known location
Route::post('/save-last-known-location', function (Request $request) {
    // Validate incoming data
    $validatedData = $request->validate([
        'gps_id' => 'required|string',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'speed' => 'required|numeric',
        'satellites' => 'required|integer',
        'gps_status' => 'required|string',
        'timestamp' => 'required|integer',
    ]);

    

    return response()->json(['message' => 'Last known location saved successfully']);
});




// notif
Route::get('/notifications/latest', function () {
    $user = Auth::user();
    $notifications = $user->notifications()->latest()->take(10)->get(); // Adjust the number as needed
    $upcomingBookings = $user->upcomingBookings()->take(10)->get(); // Adjust as needed

    return response()->json([
        'notifications' => $notifications,
        'upcomingBookings' => $upcomingBookings,
    ]);
})->name('notifications.fetchLatest');

// password reset


// Route to show the form where users can input their phone number
Route::get('/password/forgot', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');

// Route to handle the submission of the phone number and send the reset code
Route::post('/password/forgot', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');

// Route to show the form where users can input the verification code
Route::get('/password/verify-code', [ForgotPasswordController::class, 'showVerifyCodeForm'])->name('password.verify');

// Route to handle the submission of the verification code
Route::post('/password/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify.code');

// Route to show the reset password form with the token
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');

// Route to handle the reset password form submission
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');


// 2fa
Route::get('/2fa/verify', [LoginController::class, 'show2FAVerifyForm'])->name('2fa.verify');
Route::post('/2fa/verify', [LoginController::class, 'verify2FA'])->name('2fa.verify');



// notif approved
Route::put('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
Route::put('/reservations/{id}/reject', [ReservationController::class, 'reject'])->name('reservations.reject');


Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');



// cms


// CMS Routes
Route::get('/cms', [CMSController::class, 'index'])->name('cms.index');

// Route to manage sections (shows list or edit form based on parameters)
Route::get('/cms/manage', [HomeController::class, 'manage'])->name('cms.manage');

// Route to edit a section
Route::get('/cms/edit/{section_name}', [CMSController::class, 'edit'])->name('cms.edit');

// Route to update section content
Route::put('/cms/update/{section_name}', [CMSController::class, 'update'])->name('cms.update');

// Route to add a new section
Route::post('/cms/add', [CMSController::class, 'add'])->name('cms.add');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cms/manage', [HomeController::class, 'manage'])->name('cms.manage');
Route::get('/cms/edit/{section_name}', [CMSController::class, 'edit'])->name('cms.edit');
Route::put('/cms/update/{section_name}', [CMSController::class, 'update'])->name('cms.update');
Route::get('/cms/about-us', [AboutUsController::class, 'edit'])->name('aboutus.edit');
Route::put('/cms/about-us', [AboutUsController::class, 'update'])->name('aboutus.update');
// Route to display the edit form
Route::get('/about-us/edit', [AboutUsController::class, 'edit'])->name('aboutUs.edit');

// Route to handle the update
Route::put('/about-us/update', [AboutUsController::class, 'update'])->name('aboutUs.update');

// Routes for GPS Tracking
Route::get('/gps/tracking', [GpsTrackingController::class, 'track'])->name('gps.tracking');

// Route to store GPS data (via POST request)
Route::post('/gps/store', [GpsTrackingController::class, 'storeGpsData'])->name('gps.store');



Route::get('/cms/manage', [CmsController::class, 'manage'])->name('cms.manage');


// Gallery management routes


Route::get('/cms/manage', [CmsController::class, 'manage'])->name('cms.manage');
Route::post('/cms/update-logo', [CmsController::class, 'updateLogo'])->name('cms.updateLogo');



// Routes for the About Us section
Route::get('/cms/about-us/edit', [CmsController::class, 'editAboutUs'])->name('cms.aboutUs.edit');
Route::put('/cms/about-us/update', [CmsController::class, 'updateAboutUs'])->name('cms.aboutUs.update');

Route::post('/cms/about-us/update', [CMSController::class, 'updateAboutUs'])->name('cms.aboutUs.update');
Route::get('/about', function () {
    $aboutUs = DB::table('cms_sections')->where('section_name', 'about_us')->first();
    return view('about', compact('aboutUs')); // Ensure you have a view for about us
});

Route::get('/about', [CMSController::class, 'showHomePage'])->name('about'); // For showing the homepage with about us

Route::get('/', [CMSController::class, 'showHomePage'])->name('home');

// Route to edit the About Us section
Route::get('/cms/about-us/edit', [CMSController::class, 'editAboutUs'])->name('cms.aboutUs.edit');

// Route to handle the update
Route::put('/cms/about-us/update', [CMSController::class, 'updateAboutUs'])->name('cms.aboutUs.update');


Route::get('/', [PostsController::class, 'index'])->name('home'); // Make sure the route matches
Route::resource('posts', PostController::class);

Route::get('/', [CMSController::class, 'showHomePage'])->name('home'); // Adjust the URL as needed
Route::resource('posts', PostsController::class);
Route::get('/posts', [PostsController::class, 'index'])->name('posts.index');
Route::delete('/posts/{id}', [PostsController::class, 'destroy'])->name('posts.destroy');

Route::get('/cms/manage', [CMSController::class, 'manage'])->name('cms.manage');
Route::post('/cms/gallery', [CMSController::class, 'storeGalleryImage'])->name('gallery.store');
Route::delete('/cms/gallery/{galleryImage}', [CMSController::class, 'destroyGalleryImage'])->name('gallery.destroy');




Route::get('/gps-data', 'App\Http\Controllers\GpsTrackingController@fetchGpsFromProxy');
Route::get('/gps-data', [App\Http\Controllers\GpsTrackingController::class, 'fetchGpsFromProxy'])->name('fetch.gps.data');


// email otp
Route::post('/send-otp', [OTPController::class, 'sendOtp']);
Route::post('/verify-otp', [OTPController::class, 'verifyOtp']);
Route::post('/send-email-otp', [OTPController::class, 'sendEmailOtp']);

Route::post('/send-email-otp', [OTPController::class, 'sendEmailOtp'])->name('send.email.otp');



Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('verify.otp');

Route::post('/verify-otp', [\App\Http\Controllers\Auth\LoginController::class, 'verifyOtp'])->name('verify.otp');

Route::post('/resend-2fa', [LoginController::class, 'resend2FA'])->name('2fa.resend');
Route::post('/resend-2fa', [LoginController::class, 'resend2FA'])->name('resend.2fa');

// Paypal Route
// Route::post('paypal/create', [PaypalController::class, 'createOrder'])->name('paypal.create');
// Route::get('paypal/create', function() {
//     return 'This route only accepts POST requests for creating PayPal orders.';
// });

// Route::get('paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
// Route::get('paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');

// Route to create a PayPal order
// Route::post('/paypal/create-order', [PaypalController::class, 'createOrder'])->name('paypal.create');
// Route::get('/reservation/create', [PaypalController::class, 'createReservation'])->name('reservation.create');

// // Route to capture the PayPal order
// Route::post('/paypal/capture-order', [PaypalController::class, 'captureOrder'])->name('paypal.capture');

// // Route to handle successful payments
// Route::get('/paypal/success', [PaypalController::class, 'success'])->name('paypal.success');

// // Route to handle cancelled payments
// Route::get('/paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');



// Route::post('paypal/create-order', [PaypalController::class, 'createOrder'])->name('paypal.create');
// Route::post('/paypal/create', [PaypalController::class, 'createOrder'])->name('paypal.create');


Route::post('/paypal/create', [PaypalController::class, 'createOrder'])->name('paypal.create');
Route::post('/paypal/capture', [PaypalController::class, 'captureOrder'])->name('paypal.capture');

Route::get('/paypal/success', function() {
    return view('paypal.success');
})->name('paypal.success');

Route::get('/paypal/cancel', function() {
    return view('paypal.cancel');
})->name('paypal.cancel');



Route::get('/audit-trail', [AuditTrailController::class, 'showAuditTrail'])->name('audit-trail.index');



// Define routes for GPS Tracking options
Route::get('/gps/tracking1', [GpsTrackingController::class, 'tracking1'])->name('gps.tracking1');
Route::get('/gps/tracking2', [GpsTrackingController::class, 'tracking2'])->name('gps.tracking2');

// Fetch GPS data from the database
Route::get('/gps-data', [GpsTrackingController::class, 'fetchData'])->name('gps-data.fetch');

// Store GPS data (if required)
Route::get('/gps-data/store', [GpsTrackingController::class, 'storeData'])->name('gps-data.store');

// Calculate total distance (presumably for GPS logs)
Route::get('/gps/total-distance', [GpsLogController::class, 'calculateTotalDistance'])->name('gps.total-distance');

// Fetch GPS data from an external proxy
Route::get('/fetch-gps-from-proxy', [GpsTrackingController::class, 'fetchGpsFromProxy'])->name('fetchGpsData');


Route::get('/gps/fetch', [GpsTrackingController::class, 'fetchGpsFromProxy']);

