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

Route::get('/master/project', 'ProjectController@index');
Route::post('/master/project/test3', 'ProjectController@saveProjectTaskBudget');
Route::get('/budget/test3/save/{client}/{project}/{staff}/{year}/{month}/{day}/{value}', 'BudgetController@save');
Route::get('/budget/test3/data/{client}/{project}/{fye}/{vic}/{pic}/{staff}/{role}/{from}/{to}/{orValue}/{clientAS}/{projectAS}/{picAS}/{staffAS}', 'BudgetController@getDetailData');
Route::get('/test3/role', 'BudgetController@storeRole');
Route::get('/budget/test3/project/{id}', 'BudgetController@storeProject');
Route::get('/budget/test3/input/{client}/{project}/{fye}/{vic}/{pic}/{staff}/{role}/{year}/{month}/{day}/{clientAS}/{projectAS}/{picAS}/{staffAS}', 'BudgetController@storeInput');
Route::get('/test3/input2/{client}', 'BudgetController@storeInput2');

Route::get('/test3/getProjectInfo/{client}/{type}/{year}', 'ProjectController@getTaskProjectInfo');

//phase entry
Route::get('/phase/enter', 'PhaseEntryController@index');
Route::get('/phase/entry/{client}/{project}/{vic}/{pic}/{staff}/{role}/{year}/{month}/{day}/{archive}', 'PhaseEntryController@storeInput');
Route::get('/phase/entry/save/{projectId}/{year}/{month}/{day}/{value}/{projectTypeId}', 'PhaseEntryController@save');

//Staff
//index
Route::get("master/staff/", "StaffController@index");
//create
Route::get("master/staff/create", "StaffController@create");
//show
Route::get("master/staff/{id}", "StaffController@show");
//store
Route::post("master/staff/store", "StaffController@store");
//edit
Route::get("master/staff/{id}/edit", "StaffController@edit");
//update
Route::put("master/staff/{id}", "StaffController@update");
//destroy
Route::delete("master/staff/{id}", "StaffController@destroy");

//Task
//index
Route::get("master/task/", "TaskController@index");
//create
Route::get("master/task/create", "TaskController@create");
//show
Route::get("master/task/{id}", "TaskController@show");
//store
Route::post("master/task/store", "TaskController@store");
//edit
Route::get("master/task/{id}/edit", "TaskController@edit");
//update
Route::put("master/task/{id}", "TaskController@update");
//destroy
Route::delete("master/task/{id}", "TaskController@destroy");

//Client
//index
Route::get("master/client/", "ClientController@index");
//create
Route::get("master/client/create", "ClientController@create");
//show
Route::get("master/client/{id}", "ClientController@show");
//store
Route::post("master/client/store", "ClientController@store");
//edit
Route::get("master/client/{id}/edit", "ClientController@edit");
//update
Route::put("master/client/{id}", "ClientController@update");
//destroy
Route::delete("master/client/{id}", "ClientController@destroy");

//work
Route::get("master/work/", "WorkController@index");
Route::get('/test3/getPhaseInfo/{client}/{project}/{group}', 'WorkController@getPhaseInfo');
Route::post('master/work/', 'WorkController@save');
Route::get('/test3/deleteWorkRow/{phaseItemId}', 'WorkController@deleteWorkRow');

//work list
Route::get("master/work-list/", "WorkListController@index");
Route::get('/test3/getWorkList/{client}/{project}/{group}', 'WorkListController@getWorkList');
Route::post('master/work-list/', 'WorkListController@save');
Route::get('/test3/delRowWorkList/{projectPhaseItemId}', 'WorkListController@delRowWorkList');

//task list
Route::get("/task-schedule", "TaskListController@index");
Route::get("/test3/getTaskScheduleData/{client}/{pic}/{staff}/{dateFrom}/{dateTo}/{status}", "TaskListController@getTaskScheduleData");
Route::get('master/work-list/{client}/{project}', 'WorkListController@indexLink');
Route::get('master/work-list/{client}/{project}/{group}', 'WorkListController@indexLink');
Route::post('master/work-list/{client}/{project}', 'WorkListController@save');

//project list
Route::get("/master/project-list", "ProjectListController@index");
Route::get("/master/project-list/save/{project}/{status}/{harvestProject}", "ProjectListController@save");
Route::get('/master/project/{client_id}/{project}', 'ProjectController@indexLink');
Route::get("/master/project-list/{client}/{project}/{status}", "ProjectListController@store");

//dropdown project data
Route::get("/project/data/{client}", "ProjectListController@projectDropdownStore");

//sync tools
Route::get("/sync_tools", "SyncToolController@index");
Route::get("/sync_tools/project", "SyncToolController@syncProject");
Route::get("/sync_tools/user", "SyncToolController@syncUser");
Route::get("/sync_tools/client", "SyncToolController@syncClient");
Route::get("/sync_tools/time_entry/{from}/{to}", "SyncToolController@syncTimeEntry");
Route::get("/sync_tools/create_project", "SyncToolController@createProjectToHarvest");
Route::get("/sync_tools/invoice", "SyncToolController@syncInvoice");
Route::get("/sync_tools/expense", "SyncToolController@syncExpense");
Route::get("/sync_tools/engagement_fee", "SyncToolController@createEngagementFee");
Route::get("/sync_tools/task", "SyncToolController@syncTask");


//Route::get("/project-compare", "ProjectCompareController@index");
//Route::get("/project-compare/getdata", "ProjectCompareController@getData");