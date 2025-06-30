<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;

// Redirect root ke login user
Route::get('/', function () {
    return redirect('/login');
});

/*
|--------------------------------------------------------------------------
| Testing Routes
|--------------------------------------------------------------------------
*/
Route::get('/rst', function () {
    $user = User::where('email', 'abcintaah19@gmail.com')->first();

    if ($user) {
        $user->password = bcrypt('12345678');
        $user->save();
        return 'Password berhasil diubah';
    }

    return 'User tidak ditemukan';
})->name('test.reset.password');

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('force.logout');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Laravel Breeze - User Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['web'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengajuan Cuti
    Route::get('/pengajuan-surat', [LeaveRequestController::class, 'create'])->name('leave.request.create');
    Route::post('/pengajuan-surat', [LeaveRequestController::class, 'store'])->name('leave.request.store');

    // Riwayat Pengajuan
    Route::get('/riwayat', [LeaveRequestController::class, 'index'])->name('riwayat.pengajuan');
    Route::get('/riwayat/{id}', [LeaveRequestController::class, 'show'])->name('leave.detail');
    Route::delete('/riwayat/delete/{id}', [LeaveRequestController::class, 'destroy'])->name('riwayat.delete');
    Route::post('/riwayat/clear', [LeaveRequestController::class, 'clearAll'])->name('riwayat.clear');
    Route::get('/leave/{id}/download', [LeaveRequestController::class, 'download'])->name('leave.download');

    // Notifikasi
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/clear', [NotificationController::class, 'clear'])->name('clear');
        Route::delete('/delete/{id}', [NotificationController::class, 'delete'])->name('delete');
        Route::post('/read/{id}', [NotificationController::class, 'markAsRead'])->name('read');
        Route::get('/poll/{userId}', [NotificationController::class, 'poll'])->name('poll');
    });

    // Resource route (untuk CRUD)
    Route::resource('leave', LeaveRequestController::class)->except(['create', 'store']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Opsional - Jika kamu buat admin manual selain Filament)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Debugging session
Route::get('/check-session', function () {
    session(['test' => 'working']);
    return session('test'); // Harus return "working"
});

Route::get('/debug-admin', function () {
    return auth('admin')->check() ? ' Admin is logged in' : ' Admin is NOT logged in';
});


require __DIR__.'/auth.php';


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



