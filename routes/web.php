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

    // Siapkan wadah variabel
    $totalBimbingan = 0;
    $bimbinganTerbaru = collect();
    $totalDosen = 0;
    $totalMahasiswa = 0;
    $menungguReview = 0;
    $selesaiReview = 0;
    $mahasiswas = collect(); // Wadah untuk daftar mahasiswa bimbingan dosen

    if ($user->role === 'admin') {
        $totalBimbingan = \App\Models\Bimbingan::count();
        $totalDosen = \App\Models\User::where('role', 'dosen')->count();
        $totalMahasiswa = \App\Models\User::where('role', 'mahasiswa')->count();
        $bimbinganTerbaru = \App\Models\Bimbingan::latest()->take(5)->get();
    }
    elseif ($user->role === 'dosen') {
        $totalBimbingan = \App\Models\Bimbingan::where('nama_dosen', $user->name)->count();
        $menungguReview = \App\Models\Bimbingan::where('nama_dosen', $user->name)->whereIn('status', ['Menunggu Review', 'Revisi'])->count();
        $selesaiReview = \App\Models\Bimbingan::where('nama_dosen', $user->name)->where('status', 'Selesai')->count();
        $bimbinganTerbaru = \App\Models\Bimbingan::where('nama_dosen', $user->name)->latest()->take(5)->get();

        // Ambil data mahasiswa yang dibimbing oleh dosen yang sedang login
        $mahasiswas = \App\Models\User::where('role', 'mahasiswa')
            ->where('dosen_id', $user->id)
            ->get();
    }
    elseif ($user->role === 'mahasiswa') {
        $totalBimbingan = \App\Models\Bimbingan::where('nama_mahasiswa', $user->name)->count();
        $menungguReview = \App\Models\Bimbingan::where('nama_mahasiswa', $user->name)->whereIn('status', ['Menunggu Review', 'Revisi'])->count();
        $selesaiReview = \App\Models\Bimbingan::where('nama_mahasiswa', $user->name)->where('status', 'Selesai')->count();
        $bimbinganTerbaru = \App\Models\Bimbingan::where('nama_mahasiswa', $user->name)->latest()->take(5)->get();
    }

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

// --- ROUTE ADMIN / KAPRODI ---
Route::middleware('can:admin')->group(function () {
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
