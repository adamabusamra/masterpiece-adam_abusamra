<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

##
# Layout Routes --> for testing
##

Route::get('/public', function () {
    return view('layouts.public');
});
Route::get('/dashboard', function () {
    return view('layouts.dashboard');
});
Route::get('/dashboard/teacher/home', function () {
    return "Teacher Home";
})->middleware('auth:teacher');
Route::get('/dashboard/student/home', function () {
    return "Student Home";
})->middleware('auth:student');

##
# Public Routes
##
Route::get('/', function () {
    return view('public.home');
});
Route::get('/about', function () {
    return view('public.about');
});
Route::get('/contact', function () {
    return view('public.contact');
});
Route::get('/admission', function () {
    return view('public.admission');
});



##
# Dashboard routes
##

Route::prefix('dashboard')->group(function () {

    # Admin Dashboard Routes
    Route::middleware('auth')->group(function () {
        # Admins Routes
        Route::resource('admins', AdminController::class);

        # Teacher Routes
        Route::resource('teachers', TeacherController::class);

        # Student Routes
        Route::resource('students', StudentController::class);

        # Field Routes
        Route::resource('fields', FieldController::class);
        //Route to return all specialities related to a certain field
        Route::get('/fields/{field}/specialities', [FieldController::class, 'field_specialities']);

        # Speciality Routes
        Route::resource('specialities', SpecialityController::class);
    });
    Route::prefix('admin')->group(function () {
        #Auth Routes for admin '/dashboard/admin/login'
        Auth::routes();
    });
    #These are Auth routes for Teachers & students
    Route::get('/login', [LoginController::class, 'showNonDefaultLoginForm'])->name('smart-learning.login');
    Route::post('/login', [LoginController::class, 'nonDefaultLogin'])->name('smart-learning.login.submit');
});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
