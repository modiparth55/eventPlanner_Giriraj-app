<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::resource('event', EventsController::class);
        Route::get('calendar', [EventsController::class, 'calendar'])->name('calendar');
        // Route::get('all-event', [EventsController::class, 'all_event'])->name('all-event');
    }
);
