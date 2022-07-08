<?php

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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ShareContactController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware("auth")->group(function (){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/trash/contact',[ContactController::class,'goToTrash'])->name('trash.contact');
    Route::resource("/contact",ContactController::class);
    Route::get('contact-search',[ContactController::class,'search'])->name('contact.search');
    Route::get('/contact/restore/{id}',[ContactController::class,'restore'])->name('contact.restore');
    Route::get('/contact/force-delete/{id}',[ContactController::class,'forceDelete'])->name('contact.forceDelete');
    Route::post('/contact-bulk-action',[ContactController::class,'bulkAction'])->name('contact.bulkAction');
    Route::post('/contact-bulk-action-once',[ContactController::class,'bulkActionOnce'])->name('contact.bulkActionOnce');
    Route::post('/contact-bulk-force-action',[ContactController::class,'bulkForceAction'])->name('contact.bulkForceAction');
    Route::resource('/shared-contact',ShareContactController::class);
    Route::get('/mark-as-read/{notificationId}',[NotificationController::class,'markAsRead'])->name('markAsRead');
    Route::get('/all-mark-as-read',[NotificationController::class,'AllMarkAsRead'])->name('AllMarkAsRead');
    Route::get('/mark-as-read',[NotificationController::class,'deleteAllNotification'])->name('deleteAllNotification');

});

