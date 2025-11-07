<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PratihariProfileApiController;
use App\Http\Controllers\Api\PratihariFamilyApiController;
use App\Http\Controllers\Api\PratihariIdcardApiController;
use App\Http\Controllers\Api\PratihariAddressApiController;
use App\Http\Controllers\Api\PratihariOccupationApiController;
use App\Http\Controllers\Api\PratihariSocialMediaApiController;
use App\Http\Controllers\Api\PratihariSebaApiController;
use App\Http\Controllers\Api\SebaApiController;
use App\Http\Controllers\Api\PratihariNoticeController;
use App\Http\Controllers\Api\StatusController;

/*
|--------------------------------------------------------------------------
| Authentication & OTP Routes
|--------------------------------------------------------------------------
*/
// Route::post('/send-otp', [OtpController::class, 'sendOtp']);
// Route::post('/pratihari/verify-otp', [OtpController::class, 'verifyOtp'])
//     ->name('admin.verifyOtp');

Route::match(['POST', 'OPTIONS'], '/send-otp', [OtpController::class, 'sendOtp']);
Route::match(['POST', 'OPTIONS'], '/pratihari/verify-otp', [OtpController::class, 'verifyOtp'])
->name('admin.verifyOtp');

/*
|--------------------------------------------------------------------------
| Pratihari Profile Routes
|--------------------------------------------------------------------------
*/
Route::controller(PratihariProfileApiController::class)->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/save-profile', 'saveProfile');
        Route::get('/get-home-page', 'getHomePage');
        Route::get('/get-all-pratihari-profile', 'getAllData');
        Route::post('/application/save', 'saveApplication');
        Route::get('/get-application', 'getApplication');
    });

    Route::post('/update-profile/{pratihari_id}', 'updateProfile');
    Route::get('/designations', 'manageDesignation');
    Route::get('/get-profile-by-id/{pratihari_id}', 'getPofileDataByPratihariId');
    Route::get('/approved-pratihari-profiles', 'getApprovedProfiles');
});

/*
|--------------------------------------------------------------------------
| Pratihari Family, ID Card, Address, Occupation, Social Media
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(PratihariFamilyApiController::class)->group(function () {
        Route::post('/save-family', 'saveFamily');
        Route::get('/pratihari/family', 'show');
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

/*
|--------------------------------------------------------------------------
| Pratihari Seba Routes
|--------------------------------------------------------------------------
*/
Route::controller(PratihariSebaApiController::class)->group(function () {
    // Public (no auth)
    Route::get('/nijogas', 'getNijogas');
    Route::get('/sebas/{nijoga_id}', 'getSebaByNijoga');
    Route::get('/beddhas', 'getBeddha');
    Route::get('/today-beddha', 'todayBeddha');
    Route::post('/store-beddha-map', 'storeDateBeddhaMapping');

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/seba-history', 'sebaHistory');
        Route::post('/save-seba', 'saveSeba');
        Route::post('/end-seba', 'endSeba');
        Route::post('/start-seba', 'startSeba');
        Route::get('/pratihari-seba-dates', 'sebaDateList');
    });
});

/*
|--------------------------------------------------------------------------
| Seba Routes (General)
|--------------------------------------------------------------------------
*/
Route::controller(SebaApiController::class)->group(function () {
    Route::get('/seba-dates', 'sebaDate');
});

/*
|--------------------------------------------------------------------------
| Notice Routes
|--------------------------------------------------------------------------
*/
Route::controller(PratihariNoticeController::class)->group(function () {
    Route::get('/pratihari-notice', 'getNotice');
});

/*
|--------------------------------------------------------------------------
| Status Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')
    ->controller(StatusController::class)
    ->group(function () {
        Route::get('/pratihari/status', 'checkCompletionStatus');
    });
