<?php

use App\Http\Controllers\Doctorcontroller;
use App\Http\Controllers\Hospitaladmincontroller;
use App\Http\Controllers\Superadmincontroller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::middleware(['auth','role:super_admin,hospital_admin,doctor'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
Route::middleware(['auth','role:super_admin'])->prefix('super_admin')->name('super_admin.')->group(function(){
    Route::controller(Superadmincontroller::class)->group(function(){
        Route::get('/dashboard','index')->name('dashboard');
        Route::get('/hospitals_add','add_hospital_view')->name('hospitals_add_view');
        Route::get('/hospitals_add_form','add_hospital_form')->name('hospitals_add_form');
        Route::post('/hospitals_add','add_hospital')->name('hospital_add');
        Route::get('/hospitals_edit_view/{id}','edit_hospital_view')->name('hospitals_edit_view');
        Route::put('/hospital_edit/{id}','edit_hospital')->name('hospital_edit');
        Route::get('/hospital_delete/{id}','delete_hospital')->name('hospital_delete');
    });
});
Route::middleware(['auth','role:hospital_admin'])->prefix('hospital_admin')->name('hospital_admin.')->group(function(){
    Route::controller(Hospitaladmincontroller::class)->group(function(){
        Route::get('/dashboard','index')->name('dashboard');
        Route::get('/doctors_add','add_doctor_view')->name('doctors_add_view');
        Route::get('/specialization','specialization_view')->name('specialization');
        Route::post('/specialization_add','specialization_add')->name('specialization_add');
        Route::get('specialization_delete/{id}','specialization_delete')->name('specialization_delete');
    });
});
Route::middleware(['auth','role:doctor'])->prefix('doctor')->name('doctor.')->group(function(){
    Route::controller(Doctorcontroller::class)->group(function(){
        Route::get('/dashboard','index')->name('dashboard');
    });
});
