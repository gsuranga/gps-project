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

/** gps map routes */
Route::get('/tgmapso','Gmap\GmapController@index');
Route::get('/gmapView','Gmap\GmapController@gmapView');
Route::get('/gmapOutletsView','Gmap\GmapController@gmapOutletsView');

Route::get('/{any}', function () {
  return view('welcome');
})->where('any', '.*');

Route::get('test', 'TestController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
