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
class TodoListController extends Controller {

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {

        $clientList = Client::orderBy("name", "asc")->get();
        $projectList = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();
        $picData = Staff::ActiveStaffOrderByInitial();
        $staff = Staff::ActiveStaffOrderByInitial();
        return view("master.to_do_list", compact("clientList", "projectList", "picData", "staff"));
    }
}

//=======================================================================