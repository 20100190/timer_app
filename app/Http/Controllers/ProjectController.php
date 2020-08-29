<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\Project;
use App\Staff;
use App\Task;
use App\Assign;
use App\ProjectTask;
use App\ProjectType;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //client
        $clientData = Client::orderBy("name", "asc")->get();

        //pic
        $picData = Staff::ActiveStaffOrderByInitial();//Staff::ActiveStaff();

        //task       
        $taskData = ProjectTask::select("task_id", "name")
                ->leftJoin("task", "project task.task_id", "=", "task.id")
                ->get();
       
        //project Type
        /*$projectTypeData = Task::select("project_type")
                ->groupBy("project_type")
                ->get();*/
        $projectTypeData = ProjectType::select("project_type")                
                ->get();

        return view('master/project')
                        ->with("client", $clientData)
                        ->with("pic", $picData)
                        ->with("task", $taskData)
                        ->with("projectType", $projectTypeData);
    }
    
    public function getTaskProjectInfo(Request $request) {
        //project taskにデータがあればそれを表示
        //無ければtaskマスタから表示
        $projectObj = Project::where([['client_id', '=', $request->client], ["project_type", "=", $request->type], ["project_year", "=", $request->year]]);
        
        $projectId = "";
        if ($projectObj->exists()) {
            $projectId = $projectObj->first()["id"];
        }
        $isExistTask = ProjectTask::where([['project_id', '=', $projectId]])->exists();

        if (!$isExistTask) {
            $data = Task::select("id as task_id", "name", DB::raw("0 as task_status"))                    
                    ->where([['project_type', '=', $request->type], ['is_standard', '=', 'True']])
                    ->get();
        } else {
            $data = ProjectTask::select("task_id", "name")
                    ->leftJoin("task", "project task.task_id", "=", "task.id")
                    ->where([['project_id', '=', $projectId]])
                    ->orderBy("order_no", "asc")
                    ->get();
        }
        
        //Project
        $projectData = $projectObj->first();
        
        //Staff
        $staffData = Staff::ActiveStaffOrderByInitial();
        
        $budgetData = Assign::where([['project_id', '=', $projectId]])->get();
        
        //All Task
        $allTask = Task::select(DB::raw("0 as id"),"name")->where([['is_standard', '=', 'True']])->groupBy("name")->get();//TaskName::get();
        
        //fye
        $fye = Client::select("fye")->where([['id', '=', $request->client]])->first();
        
        $json = [
            "task" => $data,
            "staff" => $staffData,
            "budget" => $budgetData,
            "project" => $projectData,
            "client" => $fye,
            "allTask" => $allTask,
                ];

        return response()->json($json);
    }
    
    public function saveProjectTaskBudget(Request $request) {

        //var_dump($_POST["xw;elkfjr"]);    
        //project
        $projectId = $this->saveProjectTable($request);
        //task
        $this->saveTaskTable($projectId, $request);
        
        //assign
        /*$table = new Assign;
        $queryObj = Assign::where([['project_id', '=', $projectId]]);
        $queryObj->delete();
        
        for ($assignCnt = 1; $assignCnt < 20; $assignCnt++) {
            if (!isset($_POST["assign" . $assignCnt])) {
                break;
            }

            $assignId = $_POST["assign" . $assignCnt];
            
            if ($assignId != "") {
                $table = new Assign;
                $table->project_id = $projectId;
                $table->staff_id = $_POST["assign" . $assignCnt];
                $table->role = $_POST["role" . $assignCnt];
                $table->budget_hour = $_POST["hours" . $assignCnt];
                
                $table->save();             
            }           
        }*/
        $table = new Assign;
        $queryObj = Assign::where([['project_id', '=', $projectId]]);
        
         //削除されたAssignを削除
        $assignData = $queryObj->get();
        foreach ($assignData as $x) {
            $isExist = false;
            for ($assignCnt = 1; $assignCnt < 20; $assignCnt++) {
                if (!isset($_POST["assign" . $assignCnt])) {
                    break;
                }
                
                if($x["project_id"] == $projectId && $x["staff_id"] == $_POST["assign" . $assignCnt]){
                    $isExist = true;
                    break;
                }                
            }
            
            if($isExist == false){
                $queryObj = Assign::where([['project_id', '=', $projectId],['staff_id', '=', $x["staff_id"]]]);
                $queryObj->delete();
            }
            
        }

        for ($assignCnt = 1; $assignCnt < 20; $assignCnt++) {
            if (!isset($_POST["assign" . $assignCnt])) {
                break;
            }
            
            $staffId = $_POST["assign" . $assignCnt];
            $queryObj = Assign::where([['project_id', '=', $projectId],['staff_id', '=', $staffId]]);                   
            
            $assignId = "";
            if ($queryObj->exists()) {
                $assignId = $queryObj->first()["id"];
            }
            
            if($assignId != ""){
                //update
                $updateAssignItem = [
                    "role" => $_POST["role" . $assignCnt],
                    "budget_hour" => $_POST["hours" . $assignCnt],
                ];
                $queryObj->update($updateAssignItem);
            }else{
                //insert
                $table->project_id = $projectId;
                $table->staff_id = $_POST["assign" . $assignCnt];
                $table->role = $_POST["role" . $assignCnt];
                $table->budget_hour = $_POST["hours" . $assignCnt];
                
                $table->save();             
            }
            
        }
        
        //client
        $clientData = Client::orderBy("name", "asc")->get();

        //pic
        $picData = Staff::get();

        //task       
        $taskData = ProjectTask::select("task_id", "name")
                ->leftJoin("task", "project task.task_id", "=", "task.id")
                ->get();
        
        $projectTypeData = ProjectType::select("project_type")                
                ->get();
        
        return view('master/project')
                        ->with("client", $clientData)
                        ->with("pic", $picData)
                        ->with("task", $taskData)
                        ->with("projectType", $projectTypeData);;
    }
    
    public function saveProjectTable($request) {
        $projectObj = Project::where([['client_id', '=', $request->input("client")], ["project_type", "=", $request->input("project_type")], ["project_year", "=", $request->input("project_year")]]);
        $isExistProject = $projectObj->exists();

        if (!$isExistProject) {
            $projectTable = new Project;
            $projectTable->client_id = $request->input("client");
            $projectTable->project_type = $request->input("project_type");
            $projectTable->project_year = $request->input("project_year");
            $projectTable->project_name = $request->input("harvest_project_name");
            $projectTable->pic = $request->input("pic");
            $projectTable->start = $this->formatDate($request->input("starts_on"));
            $projectTable->end = $this->formatDate($request->input("ends_on"));
            $projectTable->billable = $request->input("billable");
            $projectTable->note = $request->input("note");
            $projectTable->engagement_fee_unit = $request->input("engagement_fee");
            $projectTable->invoice_per_year = $request->input("engagement_monthly");
            $projectTable->adjustments = $request->input("adjustments");

            $projectTable->save();
        } else {
            $updateItem = [
                "client_id" => $request->input("client"),
                "project_type" => $request->input("project_type"),
                "project_year" => $request->input("project_year"),
                "project_name" => $request->input("harvest_project_name"),
                "pic" => $request->input("pic"),
                "billable" => $request->input("billable"),
                "note" => $request->input("note"),
                "engagement_fee_unit" => $request->input("engagement_fee"),
                "invoice_per_year" => $request->input("engagement_monthly"),
                "adjustments" => $request->input("adjustments"),
            ];

            if ($request->input("starts_on") != "") {
                $start = ["start" => $this->formatDate($request->input("starts_on"))];
                $updateItem = $updateItem + $start;
            }

            if ($request->input("ends_on") != "") {
                $end = ["end" => $this->formatDate($request->input("ends_on"))];
                $updateItem = $updateItem + $end;
            }

            $projectObj->update($updateItem);
        }
        
        $clientObj = Client::where([['id', '=', $request->input("client")]]);
        $isExistClient = $clientObj->exists();
        if ($isExistClient && $request->input("fye") != "") {
            $fyeStr = $request->input("fye");
            if(strlen($fyeStr) == 4){
                $fyeStr = "0" . $fyeStr;
            }
            $updateClientItem = [
                "fye" => $fyeStr,
            ];
            $clientObj->update($updateClientItem);
        }

        //project id
        $projectId = $projectObj->first()["id"];

        return $projectId;
    }

    public function saveTaskTable($projectId,$request) {
        $table = new ProjectTask;
        $queryObj = ProjectTask::where([['project_id', '=', $projectId]]);
        $queryObj->delete();

        //task save
        for ($taskCnt = 1; $taskCnt < 20; $taskCnt++) {
            if (!isset($_POST["task_name" . $taskCnt])) {
                break;
            }

            $taskId = $_POST["task_id" . $taskCnt];

            //task マスタ
            if ($taskId == "") {
                $pTable = new Task;
                $pTable->project_type = $request->input("project_type");
                $pTable->name = $_POST["task_name" . $taskCnt];
                $pTable->is_standard = "False";

                $pTable->save();

                $taskId = $pTable->id;
            }

            //project task
            $table = new ProjectTask;
            $table->project_id = $projectId;
            $table->task_id = $taskId;

            //$isChecked = "False";
            //if (isset($_POST["task_status" . $taskCnt])) {
            //    $isChecked = "True";
            //}
            //$table->is_checked = $isChecked;
            $table->order_no = $_POST["order" . $taskCnt];

            $table->save();
        }
    }
    
    public function formatDate($dateStr) {
        $dateJp = "";

        if ($dateStr != "") {
            $dateArray = explode("/", $dateStr);
            $dateJp = $dateArray[2] . "-" . $dateArray[0] . "-" . $dateArray[1];
        }
        return $dateJp;
    }
    
}
