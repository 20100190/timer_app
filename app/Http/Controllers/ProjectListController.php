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
        $keyword = $request->get("search");
        //$perPage = 25;

        if (!empty($keyword)) {
            $client = Project::select("client.id as client_id","project.id as project_id","client.name as client_name","project.project_name","is_approval")->leftJoin("client","client.id","=","project.client_id")->where("client.name", "LIKE", "%$keyword%")->orWhere("project_name", "LIKE", "%$keyword%")->Where("client.id","<>","0")->get();//paginate($perPage);
        } else {
            $client = Project::select("client.id as client_id","project.id as project_id","client.name as client_name","project.project_name","is_approval")->leftJoin("client","client.id","=","project.client_id")->Where("client.id","<>","0")->get();
        }
        
        //権限
        $isApprove = 0;        
        $staffData = Staff::where("email","=",Auth::User()->email)->get();
        foreach($staffData as $item){
            $isApprove = $item->permission_approve;            
        }
        
        return view("master.project_list",compact("client","isApprove"));
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
    
    