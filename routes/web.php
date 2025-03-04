<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PratihariProfileController;
use App\Http\Controllers\Admin\PratihariFamilyController;
use App\Http\Controllers\Admin\PratihariIdcardController;
use App\Http\Controllers\Admin\PratihariAddressController;
use App\Http\Controllers\Admin\PratihariOccupationController;
use App\Http\Controllers\Admin\MasterNijogaSebaController;
use App\Http\Controllers\Admin\PratihariSebaController;
use App\Http\Controllers\Admin\PratihariSocialMediaController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;

Route::prefix('super-admin')->group(function() {
    Route::get('/login', [SuperAdminController::class, 'showLoginForm'])->name('superadmin.login');
    Route::post('/login/submit', [SuperAdminController::class, 'loginSubmit'])->name('superadmin.login.submit');
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
    Route::get('/add-admin', [SuperAdminController::class, 'addAdmin'])->name('superadmin.addAdmin');
    Route::get('/manage-admin', [SuperAdminController::class, 'manageAdmin'])->name('superadmin.manageAdmin');
    Route::post('/saveAdminRegister', [SuperAdminController::class, 'saveAdminRegister'])->name('superadmin.saveAdminRegister');
    Route::post('/update/{id}', [SuperAdminController::class, 'update'])->name('admin.update');
    Route::post('/delete/{id}', [SuperAdminController::class, 'softDelete'])->name('admin.delete');
});

Route::controller(AdminController::class)->group(function() {
    Route::get('/', 'showOtpForm')->name('admin.AdminLogin');
    Route::post('/send-otp',  'sendOtp')->name('admin.sendOtp');
    Route::post('/verify-otp',  'verifyOtp')->name('admin.verifyOtp'); 
    Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
    Route::post('/logout',  'logout')->name('admin.logout');

});

Route::controller(PratihariProfileController::class)->group(function() {
    Route::get('/admin/pratihari-profile', 'pratihariProfile')->name('admin.pratihariProfile');
    Route::post('/admin/pratihari-profile-save', 'saveProfile')->name('admin.pratihari-profile.store');
    Route::get('/admin/pratihari-manage-profile', 'pratihariManageProfile')->name('admin.pratihariManageProfile');
    Route::get('/get-pratihari-address','getPratihariAddress')->name('getPratihariAddress');
    Route::get('/get-profile-details/{pratihari_id}','viewProfile')->name('admin.viewProfile');
    Route::post('/admin/pratihari/approve/{id}', 'approve');
    Route::post('/admin/pratihari/reject/{id}', 'reject');
    Route::get('/profile-update/{pratihari_id}','edit')->name('profile.update');
    Route::put('/admin/pratihari-profile-update/{pratihari_id}', 'updateProfile')->name('admin.pratihari-profile.update');
});

Route::prefix('admin')->group(function() {
    Route::get('/pratihari-family', [PratihariFamilyController::class, 'pratihariFamily'])->name('admin.pratihariFamily');
    Route::post('/pratihari-family-save', [PratihariFamilyController::class, 'saveFamily'])->name('admin.pratihari-family.store');
    Route::get('/family-update/{pratihari_id}', [PratihariFamilyController::class, 'edit'])->name('family.update');
    Route::put('/pratihari-family-update/{pratihari_id}', [PratihariFamilyController::class, 'updateFamily'])->name('admin.pratihari-family.update');
});

Route::prefix('admin')->group(function() {
    Route::get('/pratihari-idcard', [PratihariIdcardController::class, 'pratihariIdcard'])->name('admin.pratihariIdcard');
    Route::post('/pratihari-idcard-save', [PratihariIdcardController::class, 'saveIdcard'])->name('admin.pratihari-idcard.store');
    Route::get('/idcard-update/{pratihari_id}', [PratihariIdcardController::class, 'edit'])->name('admin.editIdcard');
    Route::put('/idcard-update/{pratihari_id}', [PratihariIdcardController::class, 'update'])->name('idcard.update');
});

Route::prefix('admin')->group(function() {
    Route::get('/pratihari-address', [PratihariAddressController::class, 'pratihariAddress'])->name('admin.pratihariAddress');
    Route::post('/pratihari-address-save', [PratihariAddressController::class, 'saveAddress'])->name('admin.pratihari-address.store');
    Route::post('/save-sahi', [PratihariAddressController::class, 'saveSahi'])->name('saveSahi');
    Route::get('/add-sahi', [PratihariAddressController::class, 'addSahi'])->name('addSahi');
    Route::get('/manage-sahi', [PratihariAddressController::class, 'manageSahi'])->name('manageSahi');
    Route::post('/sahi/update/{id}', [PratihariAddressController::class, 'update'])->name('sahi.update');
    Route::post('/sahi/delete/{id}', [PratihariAddressController::class, 'delete'])->name('sahi.delete');
    Route::get('/address-update/{pratihari_id}', [PratihariAddressController::class, 'edit'])->name('address.update');
    Route::put('/pratihari-address-update/{pratihari_id}', [PratihariAddressController::class, 'updateAddress'])->name('admin.pratihari-address.update');
});

Route::prefix('admin')->group(function() {
    Route::get('/pratihari-occupation', [PratihariOccupationController::class, 'pratihariOccupation'])->name('admin.pratihariOccupation');
    Route::post('/pratihari-occupation-save', [PratihariOccupationController::class, 'saveOccupation'])->name('admin.pratihari-occupation.store');
    Route::get('/occupation-update/{pratihari_id}', [PratihariOccupationController::class, 'edit'])->name('occupation.update');
    Route::put('/pratihari-occupation/update/{id}', [PratihariOccupationController::class, 'update'])->name('admin.pratihari-occupation.update');
});

Route::prefix('admin')->group(function() {
    Route::get('/pratihari-nijoga-seba', [MasterNijogaSebaController::class, 'pratihariNijogaSeba'])->name('admin.pratihariNijogaSeba');
    Route::post('/pratihari-nijoga-seba-save', [MasterNijogaSebaController::class, 'saveNijogaSeba'])->name('admin.saveNijogaSeba');
    Route::post('/store-seba', [MasterNijogaSebaController::class, 'storeSeba'])->name('admin.storeSeba');
    Route::post('/store-nijoga', [MasterNijogaSebaController::class, 'storeNijoga'])->name('admin.storeNijoga');
    Route::get('/pratihari-seba-beddha', [MasterNijogaSebaController::class, 'pratihariSebaBeddha'])->name('admin.pratihariSebaBeddha');
    Route::post('/store-seba-beddha', [MasterNijogaSebaController::class, 'saveSebaBeddha'])->name('admin.saveSebaBeddha');
    Route::post('/store-beddha', [MasterNijogaSebaController::class, 'storeBeddha'])->name('admin.storeBeddha');
});

Route::prefix('admin')->group(function() {
    Route::get('/pratihari-seba', [PratihariSebaController::class, 'pratihariSeba'])->name('admin.pratihariSeba');
    Route::post('/pratihari-seba-save', [PratihariSebaController::class, 'saveSeba'])->name('admin.pratihari-seba.store');
    Route::get('/get-seba/{nijoga_id}', [PratihariSebaController::class, 'getSebaByNijoga'])->name('admin.getSebaByNijoga');
    Route::get('/get-beddha/{seba_id}', [PratihariSebaController::class, 'getBeddhaBySeba'])->name('admin.getBeddhaBySeba');
    Route::get('/seba-edit/{pratihari_id}', [PratihariSebaController::class, 'edit'])->name('seba.update');
    Route::put('/seba-update/{pratihari_id}', [PratihariSebaController::class, 'update'])->name('admin.pratihari-seba.update');
});

Route::prefix('admin')->group(function() {
    Route::get('/pratihari-social-media', [PratihariSocialMediaController::class, 'pratihariSocialMedia'])->name('admin.pratihariSocialMedia');
    Route::post('/pratihari-social-media-save', [PratihariSocialMediaController::class, 'saveSocialMedia'])->name('admin.social-media.store');
    Route::get('/social-update/{pratihari_id}', [PratihariSocialMediaController::class, 'edit'])->name('social.update');
    Route::put('/social-media/{pratihari_id}', [PratihariSocialMediaController::class, 'update'])->name('admin.social-media.update');
});
