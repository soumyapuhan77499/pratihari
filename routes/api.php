<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PratihariProfileApiController;
use App\Http\Controllers\Api\PratihariFamilyApiController;
use App\Http\Controllers\Api\PratihariIdcardApiController;
use App\Http\Controllers\Api\PratihariAddressApiController;
use App\Http\Controllers\Api\PratihariOccupationApiController;
use App\Http\Controllers\Api\PratihariSocialMediaApiController;
use App\Http\Controllers\Api\PratihariSebaApiController;
use App\Http\Controllers\Api\PratihariNoticeController;

use App\Http\Controllers\Api\StatusController;

Route::post('/send-otp', [OtpController::class, 'sendOtp'])->withoutMiddleware('auth');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('admin.verifyOtp');

Route::middleware('auth:sanctum')->post('/userLogout', [OtpController::class, 'userLogout']);

Route::controller(PratihariProfileApiController::class)->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/save-profile', 'saveProfile');
        Route::get('/get-home-page', 'getHomePage');
        Route::get('/get-all-pratihari-profile', 'getAllData');
        Route::post('/application/save', 'saveApplication');
        Route::get('/get-application', 'getApplication');
    });

    Route::get('/designations', 'manageDesignation');
    Route::get('/get-profile-by-id/{pratihari_id}', 'getPofileDataByPratihariId');
    Route::get('/approved-pratihari-profiles',  'getApprovedProfiles');

});

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(PratihariFamilyApiController::class)->group(function () {
        Route::post('/save-family', 'saveFamily');
    });

    Route::controller(PratihariIdcardApiController::class)->group(function () {
        Route::post('/save-idcard', 'saveIdcard');
    });

    Route::controller(PratihariAddressApiController::class)->group(function () {
        Route::post('/save-address', 'saveAddress');
    });

    Route::controller(PratihariOccupationApiController::class)->group(function () {
        Route::post('/save-occupation', 'saveOccupation');
    });

    Route::controller(PratihariSocialMediaApiController::class)->group(function () {
        Route::post('/save-socialmedia', 'saveSocialMedia');
        Route::get('/get-socialmedia', 'getSocialMedia');
    });

});

Route::controller(PratihariSebaApiController::class)->group(function () {

    // Public (no auth)
    Route::get('/nijogas', 'getNijogas');
    Route::get('/sebas/{nijoga_id}', 'getSebaByNijoga');
    Route::get('/beddhas', 'getBeddha');
    Route::get('/today-beddha', 'todayBeddha');

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/save-seba', 'saveSeba');
        Route::post('/end-seba', 'endSeba');
        Route::post('/start-seba', 'startSeba');
    });

});

Route::controller(SebaApiController::class)->group(function () {
    Route::get('/seba-dates', 'sebaDate');
});

Route::controller(PratihariNoticeController::class)->group(function () {
    Route::get('/pratihari-notice', 'getNotice');
});

// Authenticated routes
Route::middleware('auth:sanctum')->controller(StatusController::class)->group(function () {
    Route::get('/pratihari/status', 'checkCompletionStatus');
});