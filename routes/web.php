<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BackendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::post('/', [FrontendController::class, 'saveApplicant'])->name('saveApplicant');
Route::get('/test/start', [FrontendController::class, 'testStart'])->name('test.start');
Route::get('/test/no', [FrontendController::class, 'testNo'])->name('test.no');
Route::get('/test/yes', [FrontendController::class, 'testYes'])->name('test.yes');
Route::get('/real/start', [FrontendController::class, 'realStart'])->name('real.start');
Route::post('/upload', [FrontendController::class, 'upload'])->name('real.upload');
Route::get('/thanks', [FrontendController::class, 'thanks'])->name('real.thanks');

Route::get('/login', [BackendController::class, 'loginShow'])->name('login')->middleware('guest');
Route::post('/login', [BackendController::class, 'authenticate'])->name('postLogin');

Route::middleware('auth')->group(function () {
    Route::get('/home', [BackendController::class, 'dashboard'])->name('dashboard');
    Route::delete('/deleteApplicant/{id}', [BackendController::class, 'deleteApplicant'])->name('deleteApplicant');

    Route::get('/interviews/{slug}', [BackendController::class, 'showVideos'])->name('interview.video');
    Route::post('/saveComment/{id}', [BackendController::class, 'saveComment'])->name('saveComment');
    Route::get('/settings', [BackendController::class, 'showSettings'])->name('settings');

    Route::get('/companies', [BackendController::class, 'companies'])->name('companies');
    Route::post('/createCompany', [BackendController::class, 'createCompany'])->name('createCompany');
    Route::delete('/createCompany/{id}', [BackendController::class, 'deleteCompany'])->name('deleteCompany');
    Route::get('/createCompany/{id}/edit', [BackendController::class, 'updateCompany'])->name('updateCompany');

    Route::get('/campaigns', [BackendController::class, 'campaigns'])->name('campaigns');
    Route::post('/createCampaign', [BackendController::class, 'createCampaign'])->name('createCampaign');
    Route::delete('/createCampaign/{id}', [BackendController::class, 'deleteCampaign'])->name('deleteCampaign');
    Route::get('/createCampaign/{id}/edit', [BackendController::class, 'updateCampaign'])->name('updateCampaign');

    Route::get('/questions', [BackendController::class, 'questions'])->name('questions');
    Route::post('/createQuestion', [BackendController::class, 'createQuestion'])->name('createQuestion');
    Route::delete('/createQuestion/{id}', [BackendController::class, 'deleteQuestion'])->name('deleteQuestion');
    Route::get('/createQuestion/{id}/edit', [BackendController::class, 'updateQuestion'])->name('updateQuestion');

    Route::get('/setting', [BackendController::class, 'setting'])->name('setting');
    Route::post('/saveSetting', [BackendController::class, 'saveSetting'])->name('saveSetting');

    Route::get('/logout', [BackendController::class, 'logout'])->name('logout');
});
