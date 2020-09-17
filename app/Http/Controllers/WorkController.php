<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validate;
use DB;
use App\Client;
use App\ContactPerson;
use App\Shareholders;
use App\Officers;
use App\Staff;
use App\Project;
use App\Phase;
use App\ProjectType;
use App\PhaseItems;
use App\PhaseGroup;

//=======================================================================
class WorkController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        //client
        $client = Client::orderBy("name", "asc")->get();
        //project
        $project = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();

        return view("master/work", compact("client", "project"));
    }

    public function getPhaseInfo(Request $request) {
        //project type
        $projectType = explode(" - ", $request->project)[0];
        //phase
        $projectTypeId = ProjectType::where("project_type", $projectType)->first()->id;
        $phaseData = Phase::where("project_type", $projectTypeId)->get();
        
        $group = $request->group;
        if($group == "blank"){
            $group = "";
        }

        $projectId = Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->id;

        $phaseGroupList = PhaseGroup::where([['project_id', '=', $projectId],["group","=",$group]])->get();
        $phaseItemList = [];
        foreach ($phaseGroupList as $items) {
            //$phaseItemList = PhaseItems::where([['phase_group_id', '=', $items->id]])->get();            
            array_push($phaseItemList, PhaseItems::where([['phase_group_id', '=', $items->id]])->get());
        }

        $json = [
            "phase" => $phaseData,
            "phase1Detail" => $phaseItemList
        ];

        return response()->json($json);
    }

    public function save(Request $request) {
        
        //throw new Exception('ゼロによる除算。');
        
        $group = $request->group;
        if(is_null($group)){
            $group = "";
        }

        $projectId = Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->id;

        //project type
        $projectType = trim(explode(" - ", $request->project)[0]);
        //phase
        $projectTypeId = ProjectType::where("project_type", $projectType)->first()->id;       
        
        for ($i = 1; $i <= 10; $i++) {
            if ($_POST["label_phase" . $i] != "") {
                $this->insertPhaseGroupAndPhaseItems($projectId, $projectTypeId, $_POST["label_phase" . $i], $i,$group);
            }
        }

        $client = Client::orderBy("name", "asc")->get();
        //project
        $project = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();

        return view("master/work", compact("client", "project"));
    }

    public function insertPhaseGroupAndPhaseItems($projectId, $projectTypeId, $label_phase, $index,$group) {

        $phaseId = Phase::where([["project_type", "=", $projectTypeId], ["name", "=", $label_phase]])->first()->id;

        //phase        
        $queryObj = PhaseGroup::where([['project_id', '=', $projectId], ["phase_id", "=", $phaseId],["group","=",$group]]);
        if ($queryObj->exists()) {
            $phaseGroupId = $queryObj->first()->id;
            $queryObj->delete();

            $queryObj = PhaseItems::where([['phase_group_id', '=', $phaseGroupId]]);
            $queryObj->delete();
        }

        //phase group
        $pTable = new PhaseGroup;
        $pTable->project_id = $projectId;
        $pTable->phase_id = $phaseId;
        $pTable->group = $group;

        $pTable->save();

        $phaseGroupId = $pTable->id;

        //task save
        for ($taskCnt = 1; $taskCnt < 20; $taskCnt++) {
            if (!isset($_POST["phase" . $index . "_task" . $taskCnt])) {
                break;
            }

            //phase item
            $table = new PhaseItems;
            $table->phase_group_id = $phaseGroupId;
            $table->name = $_POST["phase" . $index . "_task" . $taskCnt];
            $table->order = $taskCnt;
            $table->description = $_POST["phase" . $index . "_description" . $taskCnt];

            $table->save();
        }
    }

}

//=======================================================================
    
    