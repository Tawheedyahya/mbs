<?php

use App\Http\Controllers\Bookingcontroller;
use App\Http\Controllers\Doctorcontroller;
use App\Http\Controllers\Hospitaladmincontroller;
use App\Http\Controllers\Superadmincontroller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Auth::routes();

Route::middleware(['auth', 'role:super_admin,hospital_admin,doctor'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::middleware(['auth', 'role:super_admin'])->prefix('super_admin')->name('super_admin.')->group(function () {
    Route::controller(Superadmincontroller::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/hospitals_add', 'add_hospital_view')->name('hospitals_add_view');
        Route::get('/hospitals_add_form', 'add_hospital_form')->name('hospitals_add_form');
        Route::post('/hospitals_add', 'add_hospital')->name('hospital_add');
        Route::get('/hospitals_edit_view/{id}', 'edit_hospital_view')->name('hospitals_edit_view');
        Route::put('/hospital_edit/{id}', 'edit_hospital')->name('hospital_edit');
        Route::post('/hospital_delete', 'delete_hospital')->name('hospital_delete');
        Route::get('/delete_s3', 'delete_s3');
    });
});
Route::middleware(['auth', 'role:hospital_admin'])->prefix('hospital_admin')->name('hospital_admin.')->group(function () {
    Route::controller(Hospitaladmincontroller::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/doctors_add', 'add_doctor_view')->name('doctors_add_view');
        Route::get('/doctor_form', 'doctor_form')->name('doctors_form');
        Route::post('/add_doctor', 'doctor_add')->name('doctor_add');
        Route::post('/delete_doctor', 'doctor_delete')->name('doctor_delete');
        Route::get('/edit_doctor/{id}', 'edit_doctor_view')->name('doctors_edit_view');
        Route::post('/update_doctor/{id}', 'doctor_add')->name('doctors_update');
        Route::get('/specialization', 'specialization_view')->name('specialization');
        Route::post('/specialization_add', 'specialization_add')->name('specialization_add');
        Route::post('/specialization_delete', 'specialization_delete')->name('specialization_delete');
        Route::post('/specialization_edit', 'specialization_edit')->name('specialization_edit');
    });
});
Route::get('/hospital_booking/{hospital_code}', [Hospitaladmincontroller::class, 'hospital_show']);
Route::get('/doctor_booking/{id}', [Bookingcontroller::class, 'booking'])->name('patient.booking');
Route::post('/booking/{doctor}/ajax', [BookingController::class, 'ajaxStore'])
    ->name('booking.ajax.store');
Route::get('/booking/status/{code}', [BookingController::class, 'status'])
    ->name('booking.status');
Route::get('/booking/verify/{token}', [BookingController::class, 'verify'])
    ->name('booking.verify');

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::controller(Doctorcontroller::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::get('/schedule', 'schedule')->name('schedule');
        Route::post('/doctor/schedule/save', 'schedule_save')->name('schedule.save');
    });
});
Route::post('/test', [Superadmincontroller::class, 'test'])->name('test');
Route::view('/book', 'boooking_form');
