<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/budget/enter', 'BudgetController@indexInput');
Route::post('/test', 'BudgetController@submit');

Route::get('/budget/show', 'BudgetController@indexShow');
Route::post('/test2', 'BudgetController@submit2');

Route::get('/test3', 'BudgetController@index3');
Route::get('/budget/test3/save/{client}/{project}/{staff}/{year}/{month}/{day}/{value}', 'BudgetController@save');
Route::get('/budget/test3/data/{client}/{project}/{fye}/{vic}/{pic}/{staff}/{role}/{from}/{to}', 'BudgetController@getDetailData');
Route::get('/test3/role', 'BudgetController@storeRole');
Route::get('/budget/test3/project/{id}', 'BudgetController@storeProject');
Route::get('/budget/test3/input/{client}/{project}/{fye}/{vic}/{pic}/{staff}/{role}/{year}/{month}/{day}', 'BudgetController@storeInput');
Route::get('/test3/input2/{client}', 'BudgetController@storeInput2');
