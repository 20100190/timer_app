<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\Project;
use App\Staff;
use App\Task;
use App\Assign;
use App\ProjectTask;
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
        $picData = Staff::get();

        //task       
        $taskData = ProjectTask::select("task_id", "name")
                ->leftJoin("task", "project task.task_id", "=", "task.id")
                ->get();
       

        return view('master/project')
                        ->with("client", $clientData)
                        ->with("pic", $picData)
                        ->with("task", $taskData);
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
            $data = ProjectTask::select("task_id", "name", "is_checked")
                    ->leftJoin("task", "project_task.task_id", "=", "task.id")
                    ->where([['project_id', '=', $projectId]])
                    ->orderBy("order_no", "asc")
                    ->get();
        }
        
        //Project
        $projectData = $projectObj->first();
        
        //Staff
        $staffData = Staff::select("id","initial","rate","billing_title")->get();
        
        $budgetData = Assign::where([['project_id', '=', $projectId]])->get();
        
        //fye
        $fye = Client::select("fye")->where([['id', '=', $request->client]])->first();
        
        $json = ["task" => $data, "staff" => $staffData, "budget" => $budgetData, "project" => $projectData, "client" => $fye];

        return response()->json($json);
    }
    
}
