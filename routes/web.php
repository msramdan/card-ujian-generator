<?php

use App\Http\Controllers\{
    ProfileController,
    SiswaController,
    UserController,
    RoleAndPermissionController,
    JurusanController,
    KelasController
};
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/kartu-peserta/{id}/kartu', [SiswaController::class, 'kartu'])->name('kartu-peserta.kartu');
Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/profile', ProfileController::class)->name('profile');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleAndPermissionController::class);

    // Routes untuk Siswa
    Route::prefix('siswa')->name('siswa.')->group(function () {
        Route::get('export', [SiswaController::class, 'exportSiswa'])->name('export');
        Route::post('import', [SiswaController::class, 'importSiswa'])->name('import');
        Route::get('format-import', [SiswaController::class, 'formatImportSiswa'])->name('format_import');
        Route::resource('/', SiswaController::class)->parameters(['' => 'siswa']); // Pindahkan setelah route spesifik
    });
});
Route::middleware('auth')->group(function () {
    Route::resource('jurusan', JurusanController::class);
    Route::resource('kelas', KelasController::class);
});
