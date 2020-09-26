<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validate;
use DB;
use App\Project;
use App\Client;
use App\ContactPerson;
use App\Shareholders;
use App\Officers;
use App\Staff;

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
        
        $clientObj = Project::select("client.id as client_id","project.id as project_id","client.name as client_name","project.project_name","is_approval")
                    ->leftJoin("client","client.id","=","project.client_id");

        if($reqClient != ""){
            $clientObj = $clientObj->where("client.id", "=", $reqClient);
        }
        
        if($reqProject != ""){
            $clientObj = $clientObj->Where("project_name", "=", $reqProject);
        }
        
        if ($reqApproval != "") {
            $clientObj = $clientObj->Where("is_approval", "=", $reqApproval);
        }

        $client = $clientObj->get();
       
        
        //権限
        $isApprove = 0;        
        $staffData = Staff::where("email","=",Auth::User()->email)->get();
        foreach($staffData as $item){
            $isApprove = $item->permission_approve;            
        }
        
        //client
        $clientList = Client::orderBy("name", "asc")->get();
        //project
        $projectList = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();
        
        return view("master.project_list",compact("client","isApprove","projectList","clientList"));
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
        
        $queryObj = Project::where("id", "=", $projectId);
        $updateItem = [
            "is_approval" => 1,            
        ];
        $queryObj->update($updateItem);
        
        $json = ["status" => "success"];
        
        return response()->json($json);

        //return redirect("master/project-list")->with("flash_message", "client updated!");
    }

  

}

//=======================================================================
    
    