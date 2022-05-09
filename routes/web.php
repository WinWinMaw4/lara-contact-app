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
    Route::post('/contact-bulk-force-action',[ContactController::class,'bulkForceAction'])->name('contact.bulkForceAction');



});

