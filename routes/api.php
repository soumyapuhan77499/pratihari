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

Route::post('/send-otp', [OtpController::class, 'sendOtp'])->withoutMiddleware('auth');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('admin.verifyOtp');

Route::middleware('auth:sanctum')->post('/userLogout', [OtpController::class, 'userLogout']);
Route::middleware('auth:sanctum')->post('/save-profile', [PratihariProfileApiController::class, 'saveProfile']);
Route::middleware('auth:sanctum')->post('/save-family', [PratihariFamilyApiController::class, 'saveFamily']);
Route::middleware('auth:sanctum')->post('/save-idcard', [PratihariIdcardApiController::class, 'saveIdcard']);
Route::middleware('auth:sanctum')->post('/save-address', [PratihariAddressApiController::class, 'saveAddress']);
Route::middleware('auth:sanctum')->post('/save-occupation', [PratihariOccupationApiController::class, 'saveOccupation']);
Route::middleware('auth:sanctum')->post('/save-socialmedia', [PratihariSocialMediaApiController::class, 'saveSocialMedia']);
Route::middleware('auth:sanctum')->post('/save-seba', [PratihariSebaApiController::class, 'saveSeba']);

Route::get('/nijogas', [PratihariSebaApiController::class, 'getNijogas']);
Route::get('/sebas/{nijoga_id}', [PratihariSebaApiController::class, 'getSebaByNijoga']);
Route::get('/beddhas', [PratihariSebaApiController::class, 'getSebaWithBeddha']);

