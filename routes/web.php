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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/forms/resource/{form}', 'FormController@resource');

Route::prefix('/forms')->group(function(){
    Route::get('/{form}/answer', 'FormResponseController@create');
    Route::view('/thanks','responses.thankyou')->name('thankyou');
    Route::post('/{form}','FormResponseController@store')->name('response.store')
        ->middleware(\App\Http\Middleware\MergeJSON::class);
});


Route::resource('/forms','FormController')->middleware(\App\Http\Middleware\MergeJSON::class);
Route::resource('/questions','QuestionController');
Route::resource('/answers','AnswerController');
