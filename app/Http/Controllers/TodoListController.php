<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validate;
//use DB;
use App\Project;
use App\Client;
use App\ContactPerson;
use App\Shareholders;
use App\Officers;
use App\ClientHarvest;
use App\ProjectHarvest;
use App\Assign;
use App\ProjectTask;
use App\Staff;
use App\TaskHarvest;
use App\Task;
use App\ToDoList;
use App\Http\Controllers\TodoListEntryController;
use Illuminate\Support\Facades\DB;

//=======================================================================
class TodoListController extends Controller {

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {

        $clientList = Client::orderBy("name", "asc")->get();
        $projectList = Project::select("project_name", "id")
                        ->groupBy('project_name', "id")
                        ->orderBy('project_name', "id", 'asc')->get();
        $picData = Staff::ActiveStaffOrderByInitial();
        $staff = Staff::ActiveStaffOrderByInitial();
        return view("master.to_do_list", compact("clientList", "projectList", "picData", "staff"));
    }

    public function getTodoListData(Request $request) {
        ini_set('memory_limit', '256M');
        $client = $request->client;
        $project = $request->project;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $staff = explode(",", $request->staff);
        $asignee = explode(",", $request->asignee);
        $status = $request->status;

        $todoListQuery = ToDoList::select("to_do_list.project_task_id", "to_do_list.id as id", "client.id as client_id", "project.id as project_id", "project.project_name as project_name", "client.name as client_name", "task.name as task_name", "requestor.id as requestor_id", "requestor.initial as requestor_initial", "to_do_list.preparer_id as preparer_id", "to_do_list.optional_personnel as optional_id", "start_time", "duration", "end_time", "progress", "location", "memo","pic.initial as pic")
                ->leftjoin("client", "client.id", "=", "to_do_list.client_id")
                ->leftjoin("project", "project.id", "=", "to_do_list.project_id")
                ->leftjoin("task", "task.id", "=", "to_do_list.project_task_id")
                ->leftjoin("staff as requestor", "requestor.id", "=", "to_do_list.requestor_id")
                ->leftjoin("staff as pic", "pic.id", "=", "project.pic");
    
        if ($request->pic != "blank") {
            $todoListQuery = $todoListQuery->whereIn("project.pic", explode(",", $request->pic));
        }
        
        $asigneeStr = "";
        if($request->asignee != "blank"){
            $assignArray = explode(",",$request->asignee);
            for($i=0; $i<count($assignArray); $i++){
                //$todoListQuery = $todoListQuery->whereRaw("FIND_IN_SET(" . $assignArray[$i] . ",to_do_list.preparer_id)");                    
                if($asigneeStr != ""){
                    $asigneeStr .= " or ";    
                }
                $asigneeStr .= "FIND_IN_SET(" . $assignArray[$i] . ",to_do_list.preparer_id)";                
            }            
            $todoListQuery = $todoListQuery->whereRaw("(" . $asigneeStr . ")");
        }

        if($status != "blank"){
            if($status == "completed"){
                $todoListQuery = $todoListQuery->where([["to_do_list.progress","=","100"]]);
            }else{
                $todoListQuery = $todoListQuery->where([["to_do_list.progress","<>","100"]]);
            }            
        }

        //date from to
        if($dateFrom != "blank" && $dateTo != "blank"){
            $convDateFrom = substr($dateFrom,0,4) . "-" . substr($dateFrom,4,2) . "-" . substr($dateFrom,6,2);
            $convDateTo = substr($dateTo,0,4) . "-" . substr($dateTo,4,2) . "-" . substr($dateTo,6,2);
            $todoListQuery = $todoListQuery->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") BETWEEN "' . $convDateFrom . '" and "' . $convDateTo . '"');   
        }

        if($dateFrom != "blank" && $dateTo == "blank"){
            $convDateFrom = substr($dateFrom,0,4) . "-" . substr($dateFrom,4,2) . "-" . substr($dateFrom,6,2);
            $todoListQuery = $todoListQuery->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") >= "' . $convDateFrom . '"');   
        }

        if($dateFrom == "blank" && $dateTo != "blank"){
            $convDateTo = substr($dateTo,0,4) . "-" . substr($dateTo,4,2) . "-" . substr($dateTo,6,2);
            $todoListQuery = $todoListQuery->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") <= "' . $convDateTo . '"');   
        }

        $todoListObj = $todoListQuery->get();
        $todoListData = [];        

        foreach ($todoListObj as $items) {
            $todoListDataItem = [];
            $preparer_id_list = explode(",", $items->preparer_id);
            $optional_id_list = explode(",", $items->optional_id);
            //requestor
            if ($items->client_id == $client || $client == "blank") {
                if ($items->project_id == $project || $project == "blank") {
                    if ($request->staff == "blank" || in_array($items->requestor_id, $staff)){// || $this->is_inArray($preparer_id_list, $staff) || $this->is_inArray($optional_id_list, $staff)) {
                        $todoListDataItem["id"] = $items->id;
                        $todoListDataItem["client"] = $items->client_name;
                        $todoListDataItem["project"] = $items->project_name;
                        $todoListDataItem["task"] = $items->project_task_id;                            
                        $todoListDataItem["requestor"] = $items->requestor_initial;
                        $todoListDataItem["preparer"] = $this->getInitial($preparer_id_list);
                        $todoListDataItem["optional"] = $this->getInitial($optional_id_list);
                        $todoListDataItem["start_time"] = $items->start_time;
                        $todoListDataItem["duration"] = $items->duration;
                        $todoListDataItem["end_time"] = $items->end_time;
                        $todoListDataItem["progress"] = $items->progress;
                        $todoListDataItem["location"] = $items->location;
                        $todoListDataItem["memo"] = $items->memo;
                        $todoListDataItem["pic"] = $items->pic;
                    }                    
                }
            }

            
            if (isset($todoListDataItem["client"])) {
                array_push($todoListData, $todoListDataItem);
            }
        }
        $json = [
            "todoList" => $todoListData,
        ];
        return response() -> json($json);
    }

    function is_inArray($list, $staffList) {
        $flag = false;
        foreach ($list as $item) {
            if (in_array($item, $staffList)) {
                $flag = true;
            }
        }
        return $flag;
    }

    function getInitial($id_list) {
        $str = "";
        if ($id_list != [null]) {
            for ($i = 0; $i < count($id_list); $i++) {
                $staffList = Staff::where("id", "=",$id_list[$i])->get();
                foreach ($staffList as $staff) {
                    $str = $str . $staff->initial;
                    if ($i != count($id_list) - 1){
                        $str = $str . ", ";
                    }
                }
            }
        } else {
            $str = null;
        }
        return $str;
    }


    public function isDateRange($dateFrom,$dateTo,$targetDate){
        $retVal = false;
        if($dateFrom != "blank" && $dateTo == "blank"){
            if($targetDate >= $dateFrom){
                $retVal = true;
            }
        }
        
        if($dateFrom == "blank" && $dateTo != "blank"){
            if($targetDate <= $dateTo){
                $retVal = true;
            }
        }
        
        if($dateFrom != "blank" && $dateTo != "blank"){
            if($targetDate >= $dateFrom && $targetDate <= $dateTo){
                $retVal = true;
            }
        }
        
        if($dateFrom == "blank" && $dateTo == "blank"){
             $retVal = true;
        }
        
        return $retVal;
    }

    /** 
     * Show the form for editing the specified resource.
     * @param int $id
     * 
     * @return \Illuminate\View\View
     * 
     */ 
    public function edit_todo($id) {
        $todoList = ToDoList::where("id", "=", $id)->get();
        $todo = null;
        foreach ($todoList as $to) {
            $todo = $to;
        }
        
        $client_id = $todo->client_id;
        $project_id = $todo->project_id;
        $selected_task_id = $todo->project_task_id;
        $requestor_id = $todo->requestor_id;
        $preparer_id_list = explode(",", $todo->preparer_id);
        $optional_id_list = explode(",", $todo->optional_personnel);
        $start_date = null;
        $start_time = null;
        $duration = $todo->duration;
        $end_time = null;
        $progress = $todo->progress;
        $location = $todo->location;
        $memo = $todo->memo;
        $addBudgetHours = $todo->add_budget_hours;

        if($todo->start_time != ""){
            $start_date = substr($todo->start_time,0,10);
            $start_time = substr($todo->start_time,-8);
        }

        $end_time = $todo->end_time;

        /*if($todo->start_time != ""){
            $bArray = explode("-",$todo->start_time);
            $start_date = $bArray[1] . "/" . $bArray[2] . "/" . $bArray[0];
            $start_time = $bArray[3];
        }
        
        if($todo->end_time != ""){
            $bArray = explode("-",$todo->end_time);
            $end_time = $bArray[0] . "-" . $bArray[1] . "-" . $bArray[2]. "T". $bArray[3];
        }*/
        

        //client
        $clientList = Client::where("id", "=", $client_id)->get();
        $client = null;
        foreach ($clientList as $c) {
            $client = $c;
        }
        
        //project
        $projectList = Project::where("id", "=", $project_id)->get();
        $project = "";
        foreach ($projectList as $p) {
            $project = $p;
        }
        //task
        $taskListObj = ProjectTask::select("task.name", "task.id")
                ->leftjoin("task", "task.id", "=", "project task.task_id")
                ->groupBy("task.name", 'task.id')
                ->orderBy('task.name', 'task.id', 'asc');
        
        $taskList = $taskListObj->where("project_id", "=", $project->id)->get();

        //requestor
        $reqList = Staff::where("id", "=", $requestor_id)->get();
        $requestor = null;
        foreach ($reqList as $r) {
            $requestor = $r;
        }
        
        //staff list
        $requestorList = Staff::ActiveStaffOrderByInitial();
        $preparerList = Staff::ActiveStaffOrderByInitial();
        $optionalList = Staff::ActiveStaffOrderByInitial();

        return view("master.edit_todo", compact("todo", "client", "project", "selected_task_id", "taskList", "requestor", "preparer_id_list", "optional_id_list", "requestorList", "preparerList", "optionalList", "start_date", "start_time", "duration", "end_time", "progress", "location", "memo", "addBudgetHours"));
    }
    
    function console_log($text) {
        echo '<script>';
        echo 'console.log('.json_encode($text).')';
        echo '</script>';
    }

    public function destroy($id) {        
        
        $toDoListEntryObj = new TodoListEntryController();
        $toDoListEntryObj->deleteCalendar($id);

        $delContactPerson = ToDoList::where("id","=",$id)->delete();
        
        return redirect("master/to-do-list")->with("flash_message", "client deleted!");
    }

}


//=======================================================================