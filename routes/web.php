<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BimbinganController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    $base = \App\Models\Bimbingan::visibleTo($user);

    $totalBimbingan   = (clone $base)->count();
    $menungguReview   = (clone $base)->whereIn('status', ['Menunggu Review', 'Revisi'])->count();
    $selesaiReview    = (clone $base)->where('status', 'Selesai')->count();
    $bimbinganTerbaru = (clone $base)->latest()->take(5)->get();

    // Statistik jumlah dosen/mahasiswa (admin = semua prodi, kaprodi = prodinya)
    $totalDosen = $totalMahasiswa = 0;
    if (in_array($user->role, ['admin', 'kaprodi'])) {
        $scope = fn ($role) => \App\Models\User::where('role', $role)
            ->when($user->role === 'kaprodi', fn ($q) => $q->where('prodi_id', $user->prodi_id));
        $totalDosen = $scope('dosen')->count();
        $totalMahasiswa = $scope('mahasiswa')->count();
    }

    // Daftar mahasiswa bimbingan milik dosen yang login
    $mahasiswas = $user->role === 'dosen'
        ? \App\Models\User::where('role', 'mahasiswa')->where('dosen_id', $user->id)->get()
        : collect();

    return view('dashboard', compact(
        'totalBimbingan', 'totalDosen', 'totalMahasiswa',
        'menungguReview', 'selesaiReview', 'bimbinganTerbaru', 'mahasiswas'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// --- ROUTE BIMBINGAN ---
Route::middleware('auth')->group(function () {
    Route::get('/bimbingan', [BimbinganController::class, 'index'])->name('bimbingan.index');
    Route::get('/bimbingan/create', [BimbinganController::class, 'create'])->name('bimbingan.create');
    Route::post('/bimbingan/simpan', [BimbinganController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan/cetak/{id}', [BimbinganController::class, 'cetak'])->name('bimbingan.cetak');
    Route::get('/bimbingan/edit/{id}', [BimbinganController::class, 'edit'])->name('bimbingan.edit');
    Route::post('/bimbingan/update/{id}', [BimbinganController::class, 'update'])->name('bimbingan.update');
    Route::delete('/bimbingan/hapus/{id}', [BimbinganController::class, 'destroy'])->name('bimbingan.destroy');

    // Edit & Update Khusus Mahasiswa
    Route::get('/bimbingan/mahasiswa/edit/{id}', [BimbinganController::class, 'editMahasiswa'])->name('bimbingan.mahasiswa.edit');
    Route::post('/bimbingan/mahasiswa/update/{id}', [BimbinganController::class, 'updateMahasiswa'])->name('bimbingan.mahasiswa.update');
});

// --- ROUTE PROFILE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- ROUTE ADMIN PUSAT / KAPRODI (data discope per prodi di AdminController) ---
Route::middleware(['auth', 'can:kelola'])->group(function () {
    Route::get('/admin/dosen', [AdminController::class, 'indexDosen'])->name('admin.dosen');
    Route::post('/admin/dosen/simpan', [AdminController::class, 'storeDosen'])->name('admin.dosen.store');
    Route::get('/admin/dosen/edit/{id}', [AdminController::class, 'editDosen'])->name('admin.dosen.edit');
    Route::post('/admin/dosen/update/{id}', [AdminController::class, 'updateDosen'])->name('admin.dosen.update');
    Route::delete('/admin/dosen/hapus/{id}', [AdminController::class, 'destroyDosen'])->name('admin.dosen.destroy');

    Route::get('/admin/mahasiswa', [AdminController::class, 'indexMahasiswa'])->name('admin.mahasiswa');
    Route::post('/admin/mahasiswa/simpan', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
    Route::get('/admin/mahasiswa/edit/{id}', [AdminController::class, 'editMahasiswa'])->name('admin.mahasiswa.edit');
    Route::post('/admin/mahasiswa/update/{id}', [AdminController::class, 'updateMahasiswa'])->name('admin.mahasiswa.update');

    Route::get('/admin/dosen/template', [AdminController::class, 'templateDosen'])->name('admin.dosen.template');
    Route::post('/admin/dosen/import', [AdminController::class, 'importDosen'])->name('admin.dosen.import');

    Route::get('/admin/mahasiswa/template', [AdminController::class, 'templateMahasiswa'])->name('admin.mahasiswa.template');
    Route::post('/admin/mahasiswa/import', [AdminController::class, 'importMahasiswa'])->name('admin.mahasiswa.import');

    Route::delete('/admin/mahasiswa/hapus/{id}', [AdminController::class, 'destroyMahasiswa'])->name('admin.mahasiswa.destroy');
});

require __DIR__.'/auth.php';
