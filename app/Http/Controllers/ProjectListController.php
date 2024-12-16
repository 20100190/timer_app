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
use Illuminate\Support\Facades\DB;

//=======================================================================
class ProjectListController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $reqClient = $request->get("client");
        $reqProject = $request->get("project");
        $reqApproval = $request->get("status");
        //$perPage = 25;

        $clientObj = Project::select("client.id as client_id", "project.id as project_id", "client.name as client_name", "project.project_name", "is_approval")
                ->leftJoin("client", "client.id", "=", "project.client_id");

        if ($reqClient != "") {
            $clientObj = $clientObj->where("client.id", "=", $reqClient);
        }

        if ($reqProject != "") {
            $clientObj = $clientObj->Where("project_name", "=", $reqProject);
        }

        if ($reqApproval != "") {
            $clientObj = $clientObj->Where("is_approval", "=", $reqApproval);
        }

        $client = $clientObj->get();


        //権限
        $isApprove = 0;
        $staffData = Staff::where("email", "=", Auth::User()->email)->get();
        foreach ($staffData as $item) {
            $isApprove = $item->permission_approve;
        }

        //client
        $clientList = Client::orderBy("name", "asc")->get();
        //project
        $projectList = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();
        
        //pic
        $picData = Staff::ActiveStaffOrderByInitial();

        return view("master.project_list", compact("client", "isApprove", "projectList", "clientList","picData"));
    }

    public function store(Request $request) {
        $reqClient = $request->client;
        $reqProject = $request->project;
        $reqApproval = $request->status;

        $reqPic = $request->pic;
        $reqFye = $request->fye;
        $reqVic = $request->vic;
        $reqActive = $request->active_status;
        $reqRegist = $request->regist_status;

        $clientObj = Project::select("client.id as client_id", "project.id as project_id", "client.name as client_name", "project.project_name", "is_approval","staff.initial as pic","project.is_archive")
                ->leftJoin("client", "client.id", "=", "project.client_id")
                ->leftJoin("staff", "project.pic", "=", "staff.id");

        if ($reqClient != "blank") {
            $clientObj = $clientObj->where("client.id", "=", $reqClient);
        }

        if ($reqProject != "blank") {
            $clientObj = $clientObj->Where("project_name", "=", $reqProject);
        }

        if ($reqApproval != "blank") {
            $clientObj = $clientObj->Where("is_approval", "=", $reqApproval);
        }

        if ($request->pic != "blank") {
            $picArray = explode(",", $request->pic);

            $clientObj = $clientObj
                    ->wherein('project.pic', $picArray);
        }

        if ($request->fye != "blank") {
            $fye = "";

            $fyeArray = explode(",", $request->fye);
            $fyeFilter = [];
            for ($i = 0; $i < count($fyeArray); $i++) {
                if ($fyeArray[$i] == 1) {
                    array_push($fyeFilter, "01");
                }
                if ($fyeArray[$i] == 2) {
                    array_push($fyeFilter, "02");
                }
                if ($fyeArray[$i] == 3) {
                    array_push($fyeFilter, "03");
                }
                if ($fyeArray[$i] == 4) {
                    array_push($fyeFilter, "04");
                }
                if ($fyeArray[$i] == 5) {
                    array_push($fyeFilter, "05");
                }
                if ($fyeArray[$i] == 6) {
                    array_push($fyeFilter, "06");
                }
                if ($fyeArray[$i] == 7) {
                    array_push($fyeFilter, "07");
                }
                if ($fyeArray[$i] == 8) {
                    array_push($fyeFilter, "08");
                }
                if ($fyeArray[$i] == 9) {
                    array_push($fyeFilter, "09");
                }
                if ($fyeArray[$i] == 10) {
                    array_push($fyeFilter, "10");
                }
                if ($fyeArray[$i] == 11) {
                    array_push($fyeFilter, "11");
                }
                if ($fyeArray[$i] == 12) {
                    array_push($fyeFilter, "12");
                }
            }

            $clientObj = $clientObj
                    ->wherein(DB::raw('Substring(client.fye,1,2)'), $fyeFilter);
        }

        if ($request->vic != "blank") {
            $vic = "";
            $vicArray = explode(",", $request->vic);
            $vicFilter = [];
            for ($i = 0; $i < count($vicArray); $i++) {
                if ($vicArray[$i] == 1) {
                    array_push($vicFilter, "VIC");
                }
                if ($vicArray[$i] == 2) {
                    array_push($vicFilter, "IC");
                }
                if ($vicArray[$i] == 3) {
                    array_push($vicFilter, "C");
                }
            }

            $clientObj = $clientObj
                    ->wherein('client.vic_status', $vicFilter);
        }

        if ($request->active_status != "blank") {
            $activeStatus = "";
            $activeStatusArray = explode(",", $request->active_status);
            $activeStatusFilter = [];
            for ($i = 0; $i < count($activeStatusArray); $i++) {
                if ($activeStatusArray[$i] == 0) {
                    array_push($activeStatusFilter, 0);
                }
                if ($activeStatusArray[$i] == 1) {
                    array_push($activeStatusFilter, 1);
                }                
            }
            $clientObj = $clientObj
                    ->wherein('project.is_archive', $activeStatusFilter);
        }

        $clientData = $clientObj->orderBy("project.id","asc")->get();


        //権限
        $isApprove = 0;
        $staffData = Staff::where("email", "=", Auth::User()->email)->get();
        foreach ($staffData as $item) {
            $isApprove = $item->permission_approve;
        }

        //task registerd, not registered
        $projectTaskData = ProjectTask::select("project_id")->groupBy("project_id")->get();

        $json = [];
        $json = [
            "listData" => $clientData,
            "isApprove" => $isApprove,
            "existTask" => $projectTaskData
        ];
        
        return response()->json($json);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request) {        
        
        $projectId = $request->project;
        $status = $request->status;
        $harvestProjectId = $request->harvestProject;
        $approved = 1;
        if($status == "Unapprove"){
            $approved = 0;
        }
       
        $queryObj = Project::where("id", "=", $projectId);
        $updateItem = [
            "is_approval" => $approved,
        ];
        $queryObj->update($updateItem);

        //harvest連携
        $ary = [];
        if($harvestProjectId == "blank"){           
            $projectDetail = $this->updateHarvest($projectId);            
            $taskDetail = $this->getTaskData($projectId);            
            $ary = $this->execHarvest(json_encode($projectDetail),"","post");               
            $ary2 = $this->execHarvestTask($taskDetail, $ary["id"], "post");
            //harvest_projectへinsert
            $projectHarvestObj = new ProjectHarvest;
            $projectHarvestObj->id = $ary["id"];
            $projectHarvestObj->client_id = $projectDetail["client_id"];
            $projectHarvestObj->client_name = $ary["client"]["name"];
            $projectHarvestObj->project_name = $ary["name"];
            $projectHarvestObj->is_active = 0;
            $projectHarvestObj->budget = $ary["budget"];
            
            //target year
            $projectNameArray = explode(" - ",$ary["name"]);
            $targetYear = 9999;
            if(isset($projectNameArray[1]) && is_numeric($projectNameArray[1])){
                $targetYear = $projectNameArray[1];
            }
            $projectHarvestObj->target_year = $targetYear;

            $projectHarvestObj->save();
        }else{
            $projectDetail = $this->updateHarvest($projectId);
            $ary = $this->execHarvest(json_encode($projectDetail),$harvestProjectId,"patch");    

            $taskDetail = $this->getTaskData($projectId);
            $ary2 = $this->execHarvestTask($taskDetail, $ary["id"], "post");
        }

        $json = ["status" => "success","harvest_project" => $ary];

        return response()->json($json);

        //return redirect("master/project-list")->with("flash_message", "client updated!");
    }

    function updateHarvest($projectId)
    {
        //project data
        $projectData = Project::select("client.name", "start", "end", "project.note", "project.project_name","staff.initial")
            ->leftJoin("client", "client.id", "=", "project.client_id")
            ->leftJoin("staff", "project.pic", "=", "staff.id")
            ->where("project.id", "=", $projectId)->first();

        //client id取得
        //$clientTable = new ClientHarvest;
        //$clientData = $clientTable->where("name", "=", $projectData["name"])->first();        
        $clientData = DB::connection('mysql_itr')->table("harvest_client")->where("name", "=", $projectData["name"])->first();

        //total hour取得
        $assignTable = new Assign;
        $budgetHours = $assignTable->select(DB::raw("sum(budget_hour) as total_hours"))->where("project_id", "=", $projectId)->get();

        //harvestに登録
        //harvest client codeを取得
        $clientId = $clientData->id;
        $projectName = $projectData["project_name"];
        //$hourlyRate = 321.0;
        $budget = $budgetHours[0]["total_hours"];
        $startsOn = $projectData["start"];
        $endsOn = $projectData["end"];
        $notes = $projectData["note"];
        $projectCode = $projectData["initial"];

        //harvestにcreate用配列作成
        $projectDetail = [
            "client_id" => $clientId,
            "name" => $projectName,
            "is_billable" => true,
            "bill_by" => "People",
            "hourly_rate" => 0,
            "budget_by" => "project",
            "budget" => $budget,
            //"notify_when_over_budget" => true,
            "notify_when_over_budget" => false,
            "show_budget_to_all" => true,
            "starts_on" => $startsOn,
            "ends_on" => $endsOn,
            "notes" => $notes,
            "code" => $projectCode,
        ];

        return $projectDetail;
    }

    function syncArchiveStatus(Request $request){
        $projectId = $request->project;        
        $harvestProjectId = $request->harvestProject;
        
        $projectDetail = [
            "is_active" => false,
        ];

        $ary = $this->execHarvest(json_encode($projectDetail),$harvestProjectId,"patch");    

        $json = ["status" => "success","harvest_project" => $ary];

        return response()->json($json);
    }

    function getTaskData($projectId){
        $table = new ProjectTask;
        $taskData = $table->select("task_harvest.id")
            ->leftJoin("task","task.id","=","project task.task_id")            
            ->leftJoin("task_harvest","task_harvest.name","=","task.name")            
            ->where([['project_id',"=",$projectId]])->get();    
            
        return $taskData;
    }

    function execHarvestTask($taskDetail, $harvestProjectId, $execType){
        $url = "https://api.harvestapp.com/v2/projects/" . $harvestProjectId . "/task_assignments";
        $syncToolObj = new SyncToolController();
        foreach ($taskDetail as $data) {
            if ($data["id"] != "") {
                $taskDetailStr = [
                    "task_id" => $data["id"],
                ];

                $taskArray = $syncToolObj->execPostCurl($url, json_encode($taskDetailStr));
            }
        }
    }

    function execHarvest($projectDetail,$harvestProjectId,$execType){
        $url = "https://api.harvestapp.com/v2/projects";
        if($harvestProjectId != ""){
            $url .= "/" . $harvestProjectId;
        }

        $syncToolObj = new SyncToolController();
        if($execType == "patch"){
            $projectArray = $syncToolObj->execPatchCurl($url,$projectDetail);
        }else{
            $projectArray = $syncToolObj->execPostCurl($url,$projectDetail);
        }
        

        return $projectArray;
    }
    
    function projectDropdownStore(Request $request){
        $clientId = explode(",",$request->client);
                
        $projectListObj = Project::select("project_name","id")                
                        ->groupBy('project_name','id')
                        ->orderBy('project_name', 'asc');
        if($clientId != "blank"){
            $projectListObj  = $projectListObj->wherein("client_id",$clientId);
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
    
    