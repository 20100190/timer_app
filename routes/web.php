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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//URL::forceScheme('https');

Route::get('/', function () {
  return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/timer', 'TimerController@index');
Route::get('/timer/weekly', 'TimerController@indexWeekly');
Route::get('/timer/get-clients', 'TimerController@getClients');
Route::get('/timer/get-projects/{clientId}', 'TimerController@getProjects');
Route::get('/timer/get-user', 'TimerController@getUser');
Route::post('/timer/init-timer', 'TimerController@initTimer')->name('startTimer');
Route::post('/timer/update-timer/{id}', 'TimerController@updateTimer')->name('updateTimer');
Route::post('/timer/init-week-timer', 'TimerController@initWeekTimer')->name('startWeekTimer');
Route::get('/timer/get-tasks/{date}/{userId?}', 'TimerController@getTasks');
Route::get('/timer/get-task/{taskId}/{userId?}', 'TimerController@getTask');

Route::post('/timer/start-timer/{taskId}', 'TimerController@startTimer');
Route::post('/timer/stop-timer/{taskId}', 'TimerController@stopTimer');
Route::get('/timer/week-summary/{date}/{userId?}', 'TimerController@getWeekSummary');
Route::get('/timer/get-week-tasks/{date}/{userId?}', 'TimerController@getWeeklyTasks');
Route::post('/timer/delete-week-data', 'TimerController@deleteWeekData');
Route::post('/save-tasks/{userId?}', 'TimerController@saveTasks');
Route::get('/timer/get-clients-and-projects', 'TimerController@getClientsNprojects');
Route::get('/timer/get-tasks-list', 'TimerController@getTaskList');
Route::get('/timer/get-running-tasks-list/{userId?}', 'TimerController@getRunningTaskList');
Route::post('/update-notes/{taskid}/{projectid}/{clientid}/{date}/{userId?}', 'TimerController@updateNotes')->name('updateNotes');
Route::post('/start-row-timer', 'TimerController@startRowTimer');
Route::post('/stop-row-timer', 'TimerController@stopRowTimer');



Route::get('/budget/enter', 'BudgetController@indexInput');
Route::get('/time/enter', 'TimeTrackController@index');
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
Route::get('/phase/entry/{client}/{project}/{vic}/{pic}/{staff}/{role}/{year}/{month}/{day}', 'PhaseEntryController@storeInput');
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
//approve
Route::post("master/client/approve/{id}/{type}", "ClientController@approve");


//work
Route::get("master/work/", "WorkController@index");
Route::get('/test3/getPhaseInfo/{client}/{project}/{group}', 'WorkController@getPhaseInfo');
Route::post('master/work/', 'WorkController@save');

//work list
Route::get("master/work-list/", "WorkListController@index");
Route::get('/test3/getWorkList/{client}/{project}/{group}', 'WorkListController@getWorkList');
Route::post('master/work-list/', 'WorkListController@save');

//task list
Route::get("/task-schedule", "TaskListController@index");
Route::get("/test3/getTaskScheduleData/{client}/{pic}/{staff}/{dateFrom}/{dateTo}/{status}/{phase}/{fye}/{group}", "TaskListController@getTaskScheduleData");
Route::get('master/work-list/{client}/{project}', 'WorkListController@indexLink');
Route::get('master/work-list/{client}/{project}/{group}', 'WorkListController@indexLink');
Route::post('master/work-list/{client}/{project}', 'WorkListController@save');

//project list
Route::get("/master/project-list", "ProjectListController@index");
Route::get("/master/project-list/save/{project}/{status}/{harvestProject}", "ProjectListController@save");
Route::get("/master/project-list/sync-archive/{project}/{harvestProject}", "ProjectListController@syncArchiveStatus");
Route::get('/master/project/{client_id}/{project}', 'ProjectController@indexLink');
Route::get("/master/project-list/{client}/{project}/{status}/{pic}/{fye}/{vic}/{active_status}/{regist_status}", "ProjectListController@store");

//to-do list by intern student
Route::get("/master/to-do-list", "TodoListController@index")->middleware('auth');
Route::get("/master/to-do-list-entry", "TodoListEntryController@index")->middleware('auth');
Route::get("/toDoList/getListData/{client}/{project}/{pic}/{staff}/{dateFrom}/{dateTo}/{asignee}/{status}", "TodoListController@getTodoListData");
Route::post("master/to-do-list-entry/test3", "TodoListEntryController@saveToDoListTable");
Route::get("master/to-do-list/{id}/edit-todo", "TodoListController@edit_todo");
Route::get("/project/data/task/{project}", "TodoListEntryController@taskDropdownStore");
Route::get("/toDoListEntry/getTodoListEntryData/{client_id}/{project_id}/{task_id}/{requestor_id}/{preparer_idList}/{optional_idList}/", "TodoListEntryController@getTodoListEntryData");
Route::get("/toDoListEntry/getToken", "TodoListEntryController@execPostCurl");
//destroy
Route::delete("master/to-do-list/{id}", "TodoListController@destroy");

Route::get("/master/access_code", "TodoListEntryController@getAccessCode");
Route::post("/master/save_access_code", "TodoListEntryController@saveAccessCode");

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
Route::get("/sync_tools/budget_time_month/{type}", "SyncToolController@syncBudgetTimeMonth");

Route::get("/sync_tools/budget_actual_amount", "SyncToolController@syncBudgetActualAmount");
Route::get("/sync_tools/budget_actual_budget", "SyncToolController@insertEngagementBudgetAmount");
Route::get("/sync_tools/budget_actual_variance", "SyncToolController@insertEngagementVarianceAmount");

Route::get("/sync_tools/budget_time_weekly/{type}/{from}/{to}", "SyncToolController@syncBudgetTimeWeekly");

//task schedule weekly
Route::get("/task-schedule-weekly", "TaskListWeeklyController@index");
Route::get("/test3/getTaskScheduleWeekData/{client}/{pic}/{staff}/{dateFrom}/{dateTo}/{status}/{compStatus}", "TaskListWeeklyController@getTaskScheduleData");

//Route::get("/project-compare", "ProjectCompareController@index");
//Route::get("/project-compare/getdata", "ProjectCompareController@getData");

Route::get('login/graph', 'Auth\LoginController@redirectToProvider');
Route::get('login/graph/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('/sms_varify', 'SMSVarifyController@index');
Route::post('/sms_varify', 'SMSVarifyController@sendSMS');

Route::get('/domain_list', 'DomainListController@index');
