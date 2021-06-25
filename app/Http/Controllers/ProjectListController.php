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
use App\Staff;
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

        return view("master.project_list", compact("client", "isApprove", "projectList", "clientList"));
    }

    public function store(Request $request) {
        $reqClient = $request->client;
        $reqProject = $request->project;
        $reqApproval = $request->status;

        $clientObj = Project::select("client.id as client_id", "project.id as project_id", "client.name as client_name", "project.project_name", "is_approval")
                ->leftJoin("client", "client.id", "=", "project.client_id");

        if ($reqClient != "blank") {
            $clientObj = $clientObj->where("client.id", "=", $reqClient);
        }

        if ($reqProject != "blank") {
            $clientObj = $clientObj->Where("project_name", "=", $reqProject);
        }

        if ($reqApproval != "blank") {
            $clientObj = $clientObj->Where("is_approval", "=", $reqApproval);
        }

        $clientData = $clientObj->orderBy("project.id","asc")->get();


        //権限
        $isApprove = 0;
        $staffData = Staff::where("email", "=", Auth::User()->email)->get();
        foreach ($staffData as $item) {
            $isApprove = $item->permission_approve;
        }

        $json = [];
        $json = [
            "listData" => $clientData,
            "isApprove" => $isApprove
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
        //$queryObj->update($updateItem);

        //harvest連携
        $ary = [];
        if($harvestProjectId == "blank"){           
            $projectDetail = $this->updateHarvest($projectId);
            $ary = $this->execHarvest(json_encode($projectDetail),"","post");            
            //harvest_projectへinsert
            $projectHarvestObj = new ProjectHarvest;
            $projectHarvestObj->id = $ary["id"];
            $projectHarvestObj->client_id = $projectDetail["client_id"];
            $projectHarvestObj->client_name = $ary["client"]["name"];
            $projectHarvestObj->project_name = $ary["name"];
            $projectHarvestObj->is_active = 0;
            $projectHarvestObj->budget = $ary["budget"];

            $projectHarvestObj->save();
        }else{
            $projectDetail = $this->updateHarvest($projectId);
            $ary = $this->execHarvest(json_encode($projectDetail),$harvestProjectId,"patch");    
        }

        $json = ["status" => "success","harvest_project" => $ary];

        return response()->json($json);

        //return redirect("master/project-list")->with("flash_message", "client updated!");
    }

    function updateHarvest($projectId)
    {
        //project data
        $projectData = Project::select("client.name", "start", "end", "project.note", "project.project_name")->leftJoin("client", "client.id", "=", "project.client_id")
        ->where("project.id", "=", $projectId)->first();

        //client id取得
        $clientTable = new ClientHarvest;
        $clientData = $clientTable->where("name", "=", $projectData["name"])->first();

        //total hour取得
        $assignTable = new Assign;
        $budgetHours = $assignTable->select(DB::raw("sum(budget_hour) as total_hours"))->where("project_id", "=", $projectId)->get();

        //harvestに登録
        //harvest client codeを取得
        $clientId = $clientData["id"];
        $projectName = $projectData["project_name"];
        //$hourlyRate = 321.0;
        $budget = $budgetHours[0]["total_hours"];
        $startsOn = $projectData["start"];
        $endsOn = $projectData["end"];
        $notes = $projectData["note"];

        //harvestにcreate用配列作成
        $projectDetail = [
            "client_id" => $clientId,
            "name" => $projectName,
            "is_billable" => true,
            "bill_by" => "People",
            "hourly_rate" => 0,
            "budget_by" => "project",
            "budget" => $budget,
            "notify_when_over_budget" => true,
            "show_budget_to_all" => true,
            "starts_on" => $startsOn,
            "ends_on" => $endsOn,
            "notes" => $notes,
        ];

        return $projectDetail;
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
                
        $projectListObj = Project::select("project_name")                
                        ->groupBy('project_name')
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
    
    