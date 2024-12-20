<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisUserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuUserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\AuthCheckMiddleware;
use App\Http\Middleware\LoadNavbarMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\ReturController;

Route::get('/', [AuthController::class, 'indexlogin'])->name('indexlogin');
Route::get('/register', [AuthController::class, 'indexregister'])->name('indexregister');

Route::prefix('admin')->name('admin.')->middleware([AuthCheckMiddleware::class, LoadNavbarMiddleware::class])->group(function () {

    // dashboard
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Akses Menu
    Route::get('AksesMenu', [MenuUserController::class, 'index'])->name('aksesMenu.index');
    Route::post('AksesMenu/store', [MenuUserController::class, 'store'])->name('aksesMenu.store');
    Route::post('AksesMenu/update/{id}', [MenuUserController::class, 'update'])->name('aksesMenu.update');
    Route::post('AksesMenu/delete/{id}', [MenuUserController::class, 'delete'])->name('aksesMenu.delete');

    Route::get('/{id}/{menu_name}', [MenuController::class, 'showMenu'])->name('aksesMenu.show')
        ->where('id', '[0-9]+');

    // User
    Route::get('user-all', [UsersController::class, 'index'])->name('user.index');
    Route::get('user-edit/{id}', [UsersController::class, 'edit'])->name('user.edit');
    Route::post('user-update/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::get('user-delete/{id}', [UsersController::class, 'delete'])->name('user.delete');

//     // role
    Route::get('role/create', [JenisUserController::class, 'create'])->name('role.create');
    Route::post('role/store', [JenisUserController::class, 'store'])->name('role.store');
    Route::get('role/edit/{id}', [JenisUserController::class, 'edit'])->name('role.edit');
    Route::post('role/update/{id}', [JenisUserController::class, 'update'])->name('role.update');
    Route::get('role/delete/{id}', [JenisUserController::class, 'delete'])->name('role.delete');
});

Route::prefix('user')->name('user.')->group(function () {});

Route::prefix('auth')->name('auth.')->group(function () {

    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

// add role
Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
Route::get('/roles/{idrole}/edit', [RoleController::class, 'edit'])->name('roles.edit');
Route::put('/roles/{idrole}', [RoleController::class, 'update'])->name('roles.update');
Route::delete('/roles/{idrole}', [RoleController::class, 'destroy'])->name('roles.destroy');

// add user
Route::get('/user', [UserController::class, 'index']);
Route::get('/user/create', [UserController::class, 'create']);
Route::post('/user', [UserController::class, 'store']);
Route::get('/user/{id}/edit', [UserController::class, 'edit']);
Route::put('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);

// add satuan
Route::patch('/satuan/{id}/update-status', [SatuanController::class, 'updateStatus'])->name('satuan.updateStatus');
Route::resource('satuan', SatuanController::class);

// add barang
Route::patch('/barang/{id}/update-status', [BarangController::class, 'updateStatus'])->name('barang.updateStatus');
Route::resource('barang', BarangController::class);

// add vendor
Route::patch('/vendor/{id}/update-statusBH', [VendorController::class, 'updateStatusBH'])->name('vendor.updateStatusBH');
Route::patch('/vendor/{id}/update-status', [VendorController::class, 'updateStatus'])->name('vendor.updateStatus');
Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');
Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');
Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');
Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');
Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

// add pengadaan
Route::patch('/pengadaan/{id}/update-status', [PengadaanController::class, 'updateStatus'])->name('pengadaan.updateStatus');
Route::resource('pengadaan', PengadaanController::class);
Route::get('/pengadaan/cancel/{id}', [PengadaanController::class, 'cancel'])->name('pengadaan.cancel');

//add penerimaan
Route::resource('penerimaan', PenerimaanController::class);

Route::resource('retur', ReturController::class);

Route::get('/margin-penjualan/create', [MarginPenjualanController::class, 'create'])->name('margin_penjualan.create');
Route::post('/margin-penjualan/store', [MarginPenjualanController::class, 'store'])->name('margin_penjualan.store');

// add penjualan
Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::post('/penjualan/simpan', [PenjualanController::class, 'save']);
Route::post('/penjualan/checkout', [PenjualanController::class, 'checkout'])->name('penjualan.checkout');
