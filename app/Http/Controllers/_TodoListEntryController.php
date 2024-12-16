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
use Illuminate\Support\Facades\DB;

//=======================================================================
class TodoListEntryController extends Controller {

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $clientList = Client::orderBy("name", "asc")->get();
        $projectList = Project::select("project_name", "id")->groupBy('project_name', 'id')->orderBy("project_name", "id", "asc")->get();
        $taskList = Task::orderBy("name", "asc")->get();
        $requestorList = Staff::ActiveStaffOrderByInitial();
        $preparerList = Staff::ActiveStaffOrderByInitial();
        $optionalList = Staff::ActiveStaffOrderByInitial();
        return view("master.to_do_list_entry", compact("clientList", "projectList", "taskList", "requestorList", "preparerList", "optionalList"));
    }

    public function saveToDoListTable(Request $request) {

        $this->execPostCurl("","");

        /*$todoListObj = ToDoList::where([['client_id', '=', $request->input("client")], ["project_id", "=", $request->input("project")]]);
        $isExistTodoList = $todoListObj->exists();
        
        if (!$isExistTodoList) {
            $todoListTable = new ToDoList;
            $todoListTable->client_id = $request->input("client");
            $todoListTable->project_id = $request->input("project");
            $todoListTable->project_task_id = $request->input("task");
            $todoListTable->requestor_id = $request->input("requestor");
            $todoListTable->preparer_id = $request->input("preparer");
            $todoListTable->optional_personnel = $request->input("optional");
            $todoListTable->start_time = $this->formatDate($request->input("start_date"), $request->input("start_time"));
            $todoListTable->duration = $request->input("duration");
            $todoListTable->end_time = $this->formatDate(mb_substr($request->input("end_time"), 0, 10), substr($request->input("end_time"), -8));
            $todoListTable->progress = $request->input("progress");
            $todoListTable->location = $request->input("location");
            $todoListTable->memo = $request->input("memo");

            $todoListTable->save();
        } else {
            $updateItem = [
                "client_id" => $request->input("client"),
                "project_id" => $request->input("project"),
                "project_task_id" => $request->input("task"),
                "requestor_id" => $request->input("requestor"),
                "preparer_id" => $request->input("preparer"),
                "optional_personnel" => $request->input("optional"),
                "start_time" => $this->formatDate($request->input("start_date"), $request->input("start_time")),
                "duration" => $request->input("duration"),
                "end_time" => $this->formatDate(mb_substr($request->input("end_time"), 0, 10), substr($request->input("end_time"), -8)),
                "progress" => $request->input("progress"),
                "location" => $request->input("location"),
                "memo" => $request->input("memo")
            ];

            $todoListObj->update($updateItem);
        }
        $todoListId = $todoListObj->first()["id"];*/

        //return $todoListId;
        return 0;
    }

    public function execPostCurl($url,$targetJson){
       
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
        );
        $params = [
            "client_id" => "0c331368-cc2c-4c80-b73e-97f26f3adad7",
            "scope" => "https://graph.microsoft.com/.default",
            "client_secret" => "75bd0253-4c67-4c1a-b74a-16ab117dc9e5",
            "grant_type" => "client_credentials"
        ];

        $conn = curl_init(); #cURLセッションの初期化
        curl_setopt($conn,CURLOPT_POST, true);
        curl_setopt($conn, CURLOPT_URL, "https://login.microsoftonline.com/bdbb5cd5-dc5a-4c19-8206-0dfae28eca8e/oauth2/v2.0/token"); #取得するURLを指定
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true); #実行結果を文字列で返す。
        curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($params));
        //curl_setopt($conn, CURLOPT_POSTFIELDS, $targetJson);


        $res = curl_exec($conn);
        curl_close($conn); #セッションの終了
        
        $ary = json_decode($res, true);
    
        //$data = $ary[$arrayItemName];        

        var_dump($ary);
        return $ary;
    }

    public function formatDate($dateStr, $timeStr) {
        $dateJp = NULL;

        if ($dateStr != "") {
            $dateArray = explode('/', $dateStr);
            $dateJp = $dateArray[2] . "-" . $dateArray[0] . "-" . $dateArray[1] . "-" . $timeStr;
        }
        return $dateJp;
    }

    function taskDropdownStore(Request $request){
        $clientId = explode(",",$request->project);
                
        $projectListObj = ProjectTask::select("task.name","task.id")  
                        ->leftjoin("task", "task.id", "=", "project task.task_id")              
                        ->groupBy('task.name','task.id')
                        ->orderBy('task.name', 'asc');
        if($clientId != "blank"){
            $projectListObj  = $projectListObj->wherein("project_id",$clientId);
        }
        
        $projectList = $projectListObj->get();
                
        $json = [];
        $json = [
            "projectData" => $projectList,
            ];
        return response()->json($json);
    }


}

//=======================================================================