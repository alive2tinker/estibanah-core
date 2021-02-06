<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/downloadfile/{filename}','UploadController@download')->name('downloadFile');



Route::group(['prefix' => LaravelLocalization::setLocale()], function()
{
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Auth::routes();
    Route::view('/','welcome');
    Route::get('/home', 'HomeController@index')->name('home');


    Route::get('/forms/resource/{form}', 'FormController@resource');
    Route::post('/send_invitations', 'EmailController@sendInvitations')->name('sendInvitations');

    Route::get('/search',SearchController::class)->name('search');

    Route::view('settings','settings')->name('settings');

    Route::prefix('/forms')->group(function(){
        Route::get('/{form}/answer', 'FormResponseController@create');
        Route::view('/thanks','responses.thankyou')->name('thankyou');
        Route::post('/{form}','FormResponseController@store')->name('response.store')
            ->middleware(\App\Http\Middleware\MergeJSON::class);
    });


    Route::resource('/forms','FormController')->middleware(\App\Http\Middleware\MergeJSON::class);
    Route::resource('/questions','QuestionController');
    Route::resource('/answers','AnswerController');
});
