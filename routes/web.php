<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontends\PageController;
use App\Http\Controllers\MailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/admin');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('image/{scr}/{w}/{h}', function($src, $w=100, $h=100){
	$caheimage = Image::cache(function($image) use ($src, $w, $h){ return $image->make(public_path('uploads/').$src)->fit($w, $h);}, 10, true);
	$extention = explode(".", $src);
	return $caheimage->response($extention[1]);
});
Route::get('/', [PageController::class, 'index'])->name('home')->middleware('guest');
// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

//Clear route cache
Route::get('/route-cache', function() {
    \Artisan::call('route:cache');
    return 'Routes cache cleared';
});

//Clear config cache
Route::get('/config-cache', function() {
    \Artisan::call('config:cache');
    return 'Config cache cleared';
});

//Clear config cache
Route::get('/route-clear', function() {
    \Artisan::call('route:clear');
    return 'Route cache cleared';
});
// Clear application cache
Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    return 'Application cache cleared';
});

// Clear application cache
Route::get('/storage-link', function() {
    \Artisan::call('storage:link');
    return 'Storage linked';
});

// Clear view cache
Route::get('/view-clear', function() {
    \Artisan::call('view:clear');
    return 'View cache cleared';
});

// Clear cache using reoptimized class
Route::get('/optimize-clear', function() {
    \Artisan::call('optimize:clear');
    return 'View cache cleared';
});
Route::view('login2', 'frontends.login');

Route::get('/check_mail/{equitment_id}', [MailController::class, 'show']);
Route::get('/check', [MailController::class, 'check']);
