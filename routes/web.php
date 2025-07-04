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
use App\Http\Controllers\Admin\PratihariNoticeController;

use App\Http\Controllers\SuperAdmin\SuperAdminController;


Route::prefix('super-admin')->controller(SuperAdminController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('superadmin.login');
    Route::post('/login/submit', 'loginSubmit')->name('superadmin.login.submit');
    Route::get('/dashboard', 'dashboard')->name('superadmin.dashboard');
    Route::get('/add-admin', 'addAdmin')->name('superadmin.addAdmin');
    Route::get('/manage-admin', 'manageAdmin')->name('superadmin.manageAdmin');
    Route::post('/saveAdminRegister', 'saveAdminRegister')->name('superadmin.saveAdminRegister');
    Route::post('/update/{id}', 'update')->name('admin.update');
    Route::post('/delete/{id}', 'softDelete')->name('admin.delete');
});

Route::controller(AdminController::class)->group(function() {
    Route::get('/', 'showOtpForm')->name('admin.AdminLogin');
    Route::post('/send-otp',  'sendOtp')->name('admin.sendOtp');
    Route::post('/verify-otp',  'verifyOtp')->name('admin.verifyOtp'); 
    Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
    Route::post('/logout',  'logout')->name('admin.logout');
    Route::get('admin/find-seba-date',  'sebaDate')->name('admin.sebaDate');
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
    Route::get('/admin/pratihari/users/{status}','filterUsers')->name('admin.pratihari.filterUsers');
    Route::post('/admin/designation/store', 'saveDesignation')->name('admin.designation.store');
    Route::get('/admin/add-designation', 'addDesignation')->name('admin.designation.add');
    Route::get('/admin/manage-designation', 'manageDesignation')->name('admin.designation.manage');
    Route::delete('/delete-designation/{id}', 'deleteDesignation')->name('deleteDesignation');
    Route::get('/admin/manage-application', 'manageApplication')->name('manageApplication');
    Route::get('/admin/today-application', 'filterApplication')->name('today.application.filterUsers');
    Route::delete('/admin/delete-application', 'deleteApplication')->name('deleteApplication');
    Route::put('/admin/application/update/{id}','updateApplication')->name('admin.application.update');
    Route::patch('/application/{id}/approve', 'approveApplication')->name('application.approve');
    Route::patch('/application/{id}/reject', 'rejectApplication')->name('application.reject');
});

Route::prefix('admin')->group(function () {

    // Family Controller Routes
    Route::controller(PratihariFamilyController::class)->group(function () {
        Route::get('/pratihari-family', 'pratihariFamily')->name('admin.pratihariFamily');
        Route::post('/pratihari-family-save', 'saveFamily')->name('admin.pratihari-family.store');
        Route::get('/family-update/{pratihari_id}', 'edit')->name('family.update');
        Route::put('/pratihari-family-update/{pratihari_id}', 'updateFamily')->name('admin.pratihari-family.update');
    });

    // ID Card Controller Routes
    Route::controller(PratihariIdcardController::class)->group(function () {
        Route::get('/pratihari-idcard', 'pratihariIdcard')->name('admin.pratihariIdcard');
        Route::post('/pratihari-idcard-save', 'saveIdcard')->name('admin.pratihari-idcard.store');
        Route::get('/idcard-update/{pratihari_id}', 'edit')->name('admin.editIdcard');
        Route::put('/idcard-update/{pratihari_id}', 'update')->name('idcard.update');
    });

     Route::controller(PratihariAddressController::class)->group(function () {
        Route::get('/pratihari-address', 'pratihariAddress')->name('admin.pratihariAddress');
        Route::post('/pratihari-address-save', 'saveAddress')->name('admin.pratihari-address.store');
        Route::get('/address-update/{pratihari_id}', 'edit')->name('address.update');
        Route::put('/pratihari-address-update/{pratihari_id}', 'updateAddress')->name('admin.pratihari-address.update');

        // 🏘️ Sahi management
        Route::get('/add-sahi', 'addSahi')->name('addSahi');
        Route::get('/manage-sahi', 'manageSahi')->name('manageSahi');
        Route::post('/save-sahi', 'saveSahi')->name('saveSahi');
        Route::post('/sahi/update/{id}', 'update')->name('sahi.update');
        Route::post('/sahi/delete/{id}', 'delete')->name('sahi.delete');
    });

    // 👷 Pratihari Occupation Routes
    Route::controller(PratihariOccupationController::class)->group(function () {
        Route::get('/pratihari-occupation', 'pratihariOccupation')->name('admin.pratihariOccupation');
        Route::post('/pratihari-occupation-save', 'saveOccupation')->name('admin.pratihari-occupation.store');
        Route::get('/occupation-update/{pratihari_id}', 'edit')->name('occupation.update');
        Route::put('/pratihari-occupation/update/{id}', 'update')->name('admin.pratihari-occupation.update');
    });

     Route::controller(MasterNijogaSebaController::class)->group(function () {
        Route::get('/pratihari-nijoga-seba', 'pratihariNijogaSeba')->name('admin.pratihariNijogaSeba');
        Route::post('/pratihari-nijoga-seba-save', 'saveNijogaSeba')->name('admin.saveNijogaSeba');
        Route::post('/store-seba', 'storeSeba')->name('admin.storeSeba');
        Route::post('/store-nijoga', 'storeNijoga')->name('admin.storeNijoga');

        Route::get('/pratihari-seba-beddha', 'pratihariSebaBeddha')->name('admin.pratihariSebaBeddha');
        Route::post('/store-seba-beddha', 'saveSebaBeddha')->name('admin.saveSebaBeddha');
        Route::post('/store-beddha', 'storeBeddha')->name('admin.storeBeddha');
    });

    // ⚙️ Pratihari Seba Management
    Route::controller(PratihariSebaController::class)->group(function () {
        Route::get('/pratihari-seba', 'pratihariSeba')->name('admin.pratihariSeba');
        Route::post('/pratihari-seba-save', 'saveSeba')->name('admin.pratihari-seba.store');

        Route::get('/get-seba/{nijoga_id}', 'getSebaByNijoga')->name('admin.getSebaByNijoga');
        Route::get('/get-beddha/{seba_id}', 'getBeddhaBySeba')->name('admin.getBeddhaBySeba');

        Route::get('/seba-edit/{pratihari_id}', 'edit')->name('seba.update');
        Route::put('/seba-update/{pratihari_id}', 'update')->name('admin.pratihari-seba.update');

        Route::get('/assign-pratihari-seba', 'PratihariSebaAssign')->name('admin.PratihariSebaAssign');
        Route::post('/save-pratihari-assign-seba', 'savePratihariAssignSeba')->name('admin.savePratihariAssignSeba');
    });

      // 🌐 Pratihari Social Media Routes
    Route::controller(PratihariSocialMediaController::class)->group(function () {
        Route::get('/pratihari-social-media', 'pratihariSocialMedia')->name('admin.pratihariSocialMedia');
        Route::post('/pratihari-social-media-save', 'saveSocialMedia')->name('admin.social-media.store');
        Route::get('/social-update/{pratihari_id}', 'edit')->name('social.update');
        Route::put('/social-media/{pratihari_id}', 'update')->name('admin.social-media.update');
    });

    // 📢 Pratihari Notice Routes
    Route::controller(PratihariNoticeController::class)->group(function () {
        Route::get('/add-notice', 'showNoticeForm')->name('admin.addNotice');
        Route::post('/save-notice', 'saveNotice')->name('saveNotice');
        Route::get('/manage-notice', 'manageNotice')->name('manageNotice');
        Route::delete('/delete-notice/{id}', 'deleteNotice')->name('deleteNotice');
        Route::put('/notice/update/{id}', 'updateNotice')->name('notice.update');
    });

});