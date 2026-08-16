<?php

use App\Http\Controllers\Api\AdoptionApplicationController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\PetRecordController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VolunteerController;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/pets', [PetController::class, 'index']);
Route::get('/pets/{pet}', [PetController::class, 'show']);
Route::get('/pets/{pet}/records', [PetRecordController::class, 'index']);
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{news}', [NewsController::class, 'show']);

Route::get('/appointments/slots', [AppointmentController::class, 'slots']);
Route::get('/schedules', [ScheduleController::class, 'index']);

// Authenticated (any logged-in user)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'user']);

    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::put('/user/password', [UserController::class, 'changePassword']);

    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/my', [AppointmentController::class, 'myAppointments']);
    Route::get('/appointments/{appointment}/message', [AppointmentController::class, 'message']);

    Route::post('/adoption-applications', [AdoptionApplicationController::class, 'store']);
    Route::get('/adoption-applications/my', [AdoptionApplicationController::class, 'my']);
    Route::post('/adoption-applications/{application}/cancel', [AdoptionApplicationController::class, 'cancel']);

    Route::post('/volunteers/apply', [VolunteerController::class, 'apply']);
    Route::get('/volunteers/my', [VolunteerController::class, 'my']);
    Route::get('/volunteers/shifts', [ShiftController::class, 'my']);
    Route::put('/volunteers/shifts/{shift}/hours', [ShiftController::class, 'logHours']);

    Route::post('/favorites/{pet}', [FavoritesController::class, 'toggle']);
    Route::get('/favorites', [FavoritesController::class, 'index']);

    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationsController::class, 'markRead']);
    Route::post('/notifications/read-all', [NotificationsController::class, 'markAllRead']);
});

// Admin only
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/daily', [DashboardController::class, 'daily']);
    Route::get('/reports', [ReportController::class, 'index']);

    Route::get('/schedules', [ScheduleController::class, 'adminIndex']);
    Route::put('/schedules', [ScheduleController::class, 'update']);

    Route::post('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);

    Route::post('/pets', [PetController::class, 'store']);
    Route::put('/pets/{pet}', [PetController::class, 'update']);
    Route::delete('/pets/{pet}', [PetController::class, 'destroy']);
    Route::post('/pets/{pet}/status', [PetController::class, 'setStatus']);
    Route::post('/pets/featured', [PetController::class, 'setFeatured']);

    Route::get('/pets/{pet}/records', [PetRecordController::class, 'adminIndex']);
    Route::post('/pets/{pet}/records', [PetRecordController::class, 'store']);
    Route::delete('/pets/{pet}/records/{record}', [PetRecordController::class, 'destroy']);

    Route::post('/news', [NewsController::class, 'store']);
    Route::put('/news/{news}', [NewsController::class, 'update']);
    Route::delete('/news/{news}', [NewsController::class, 'destroy']);
    Route::post('/news/{news}/feature', [NewsController::class, 'setFeatured']);

    Route::get('/users', [UserController::class, 'index']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/users/{user}/role', [UserController::class, 'updateRole']);

    Route::get('/adoption-applications', [AdoptionApplicationController::class, 'index']);
    Route::put('/adoption-applications/{application}/status', [AdoptionApplicationController::class, 'updateStatus']);

    Route::get('/volunteers', [VolunteerController::class, 'index']);
    Route::put('/volunteers/{volunteer}/status', [VolunteerController::class, 'updateStatus']);
    Route::post('/volunteers/{volunteer}/shifts', [ShiftController::class, 'store']);
    Route::put('/volunteers/shifts/{shift}', [ShiftController::class, 'update']);
});
