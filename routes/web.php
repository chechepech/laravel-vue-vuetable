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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('courses','CourseController@index')->middleware('auth');
Route::get('objectives','ObjectiveController@index')->middleware('auth');
Route::group(['prefix'=>'courses','middleware' => ['auth']], function () {
    Route::post('/get', [
        'uses' => 'CourseController@get',
        'as' => 'courses.get'
    ]);
    Route::post('/save', [
        'uses' => 'CourseController@store',
        'as' => 'courses.save'
    ]);
    Route::post('/edit', [
        'uses' => 'CourseController@edit',
        'as' => 'courses.edit'
    ]);
    Route::post('/update', [
        'uses' => 'CourseController@update',
        'as' => 'courses.update'
    ]);
    Route::post('/delete', [
        'uses' => 'CourseController@destroy',
        'as' => 'courses.delete'
    ]);
});

Route::group(['prefix'=>'objectives','middleware' => ['auth']], function () {
    Route::post('/get', [
        'uses' => 'ObjectiveController@get',
        'as' => 'objectives.get'
    ]);
    Route::post('/save', [
        'uses' => 'ObjectiveController@store',
        'as' => 'objectives.save'
    ]);
    Route::post('/edit', [
        'uses' => 'ObjectiveController@edit',
        'as' => 'objectives.edit'
    ]);
    Route::post('/update', [
        'uses' => 'ObjectiveController@update',
        'as' => 'objectives.update'
    ]);
    Route::post('/delete', [
        'uses' => 'ObjectiveController@destroy',
        'as' => 'objectives.delete'
    ]);
});

Route::group(['prefix'=>'objectivecourses','middleware' => ['auth']], function () {
    Route::post('/get', [
        'uses' => 'CourseObjectiveController@get',
        'as' => 'objectivecourses.get'
    ]);
});