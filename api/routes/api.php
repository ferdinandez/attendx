<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\SettingsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Health check endpoint
Route::get('/health', function() {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now(),
        'database' => \DB::connection()->getDatabaseName()
    ]);
});

// Create admin user (one-time setup)
Route::get('/create-admin', function() {
    $secret = request('secret');
    if ($secret !== 'attendx2026') {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    // Check if admin already exists
    $existingAdmin = \App\Models\User::where('email', 'admin@attendancex.com')->first();
    if ($existingAdmin) {
        return response()->json([
            'message' => 'Admin already exists',
            'email' => 'admin@attendancex.com',
            'hint' => 'Use this email to login'
        ]);
    }
    
    // Create admin
    $admin = \App\Models\User::create([
        'name' => 'Administrator',
        'email' => 'admin@attendancex.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'status' => 'active',
        'phone' => '08123456789',
        'department' => 'IT',
        'position' => 'System Administrator'
    ]);
    
    return response()->json([
        'message' => 'Admin user created successfully',
        'email' => 'admin@attendancex.com',
        'password' => 'admin123',
        'hint' => 'Please change password after first login'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Absensi karyawan
    Route::post('/absen', [AbsensiController::class, 'absen']);
    Route::get('/history', [AbsensiController::class, 'history']);
    Route::get('/karyawan/dashboard', [AbsensiController::class, 'karyawanDashboard']);
    Route::get('/karyawan/export', [AbsensiController::class, 'exportPersonal']);

    // Profil
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/profile/change-password', [AuthController::class, 'changePassword']);
    Route::post('/profile/avatar', [AuthController::class, 'uploadAvatar']);
    Route::delete('/profile/avatar', [AuthController::class, 'deleteAvatar']);

    // QR Code
    Route::post('/qr/generate', [QrController::class, 'generate']);
    Route::post('/absen/qr', [QrController::class, 'scanAbsen']);

    // Admin — Dashboard
    Route::get('/dashboard', [AbsensiController::class, 'dashboard']);
    Route::get('/statistics', [AbsensiController::class, 'statistics']);

    // Admin — Report
    Route::get('/report', [AbsensiController::class, 'report']);
    
    // Admin — CRUD Absensi
    Route::get('/absensi', [AbsensiController::class, 'index']);
    Route::post('/absensi', [AbsensiController::class, 'store']);
    Route::put('/absensi/{id}', [AbsensiController::class, 'update']);
    Route::delete('/absensi/{id}', [AbsensiController::class, 'destroy']);
    
    // Admin — Export Report
    Route::get('/report/export', [AbsensiController::class, 'exportReport']);

    // Admin — CRUD User
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/pending', [UserController::class, 'pending']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete']);
    Route::put('/users/{id}/change-password', [UserController::class, 'changePassword']);
    Route::put('/users/{id}/approve', [UserController::class, 'approve']);
    Route::put('/users/{id}/suspend', [UserController::class, 'suspend']);

    // Leave Management
    Route::get('/leave/types', [LeaveController::class, 'getLeaveTypes']);
    Route::get('/leave/balance', [LeaveController::class, 'getBalance']);
    Route::post('/leave/request', [LeaveController::class, 'submitRequest']);
    Route::get('/leave/history', [LeaveController::class, 'getHistory']);
    Route::delete('/leave/request/{id}', [LeaveController::class, 'cancelRequest']);
    
    // Admin — Leave Management
    Route::get('/leave/pending', [LeaveController::class, 'getPendingRequests']);
    Route::get('/leave/all', [LeaveController::class, 'getAllRequests']);
    Route::post('/leave/approve/{id}', [LeaveController::class, 'approveRequest']);
    Route::post('/leave/reject/{id}', [LeaveController::class, 'rejectRequest']);
    Route::get('/leave/statistics', [LeaveController::class, 'getStatistics']);
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
    Route::put('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [App\Http\Controllers\NotificationController::class, 'destroy']);
    
    // Settings (Admin only)
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/settings/{key}', [SettingsController::class, 'show']);
    Route::put('/settings', [SettingsController::class, 'update']);
    Route::put('/settings/{key}', [SettingsController::class, 'updateSingle']);
    Route::post('/settings/reset', [SettingsController::class, 'reset']);
});
