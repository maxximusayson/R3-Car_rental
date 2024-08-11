<?php

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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
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

    

    Route::get('/cms', [App\Http\Controllers\CMSController::class, 'index'])->name('cms');

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

    Route::get('/userDetails/{user}', [usersController::class, 'show'])->name('userDetails');

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

// Route for showing client details
Route::get('/client-details', [UsersController::class, 'show'])->name('client.details');



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

Route::get('/clients/{client}', [usersController::class, 'show'])->name('clients.show');
Route::get('/clients/{client}', [usersController::class, 'show'])->name('clients.show');


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



// Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'login']);
// Route::post('2fa/verify', [LoginController::class, 'verify2fa'])->name('2fa.verify');

// Route::post('/request-otp', [OTPController::class, 'requestOtp'])->name('request.otp');
// Route::post('/verify-otp', [OTPController::class, 'verifyOtp'])->name('verify.otp');
// Route::get('/registration-success', function () {
//     return view('registration-success');
// })->name('registration.success');















