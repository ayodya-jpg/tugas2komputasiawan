<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Redirect halaman utama ke halaman login
Route::get('/', function () {
    return redirect('login');
});

//Route All 
Auth::routes();

//Route untuk pengguna yang sudah terautentikasi
Route::middleware('auth')->group(function () {
    //Route untuk halaman home
    Route::get('home', [HomeController::class, 'index'])->name('home');

    //Route resource untuk karyawan
    Route::resource('employees', EmployeeController::class);

    //Route untuk halaman profil
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
});