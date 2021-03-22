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
        return $this->showWork();
    }
    
    public function showWork() {
        //client
        $client = Client::orderBy("name", "asc")->get();
        //project type
        $project = ProjectType::select("id", "project_type")
                        ->groupBy('project_type', "id")
                        ->orderBy('project_type', 'asc')->get();

        return view("master/work", compact("client", "project"));
    }

    public function getPhaseInfo(Request $request) {
        //project type
        $projectType = $request->project;
        
        //phase
        $projectTypeId = $request->project;//ProjectType::where("project_type", $projectType)->first()->id;
        $phaseData = Phase::where("project_type", $projectTypeId)->get();
        
        //$group = $request->group;
        //if($group == "blank"){
        //    $group = "";
       // }

        /*$projectId = "999";//Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->id;

        $phaseGroupList = PhaseGroup::where([['project_id', '=', $projectId],["group","=",$group]])->get();
        $phaseItemList = [];
        foreach ($phaseGroupList as $items) {
            //$phaseItemList = PhaseItems::where([['phase_group_id', '=', $items->id]])->get();            
            array_push($phaseItemList, PhaseItems::where([['phase_group_id', '=', $items->id]])->get());
        }*/
        
        
        $phaseItemList = [];
        $phaseIdArray = Phase::select("id")->where([['project_type', '=', $projectTypeId]])->get();
        $phaseIdList = [];
        foreach($phaseIdArray as $items){
            array_push($phaseIdList,$items->id);
        }
        $phaseGroupList = PhaseGroup::whereIn("phase_id",$phaseIdList)->whereIn("group",["","January"])->get();
        foreach ($phaseGroupList as $items) {
            //$phaseItemList = PhaseItems::where([['phase_group_id', '=', $items->id]])->get();            
            array_push($phaseItemList, PhaseItems::where([['phase_group_id', '=', $items->id],["is_deleted","=","0"]])->get());
        }

        $json = [
            "phase" => $phaseData,
            "phase1Detail" => $phaseItemList
        ];

        return response()->json($json);
    }

    public function save(Request $request) {
        
        //throw new Exception('ゼロによる除算。');
            
        //BMの場合、自動で12ヶ月分生成する為IDを取得
        $BMProjectTypeId = 5;        
        if ($request->project != $BMProjectTypeId) {
            $this->savePhaseGroupAndItems($request, "");
        } else {
            $monthArray = $this->monthArray();
            for ($i = 0; $i < count($monthArray); $i++) {
                $this->savePhaseGroupAndItems($request, $monthArray[$i]);
            }
        }

        return $this->showWork();
    }
    
    public function savePhaseGroupAndItems($request,$group) {
        //phase group登録　存在していない場合登録するのみ   
        for ($i = 1; $i <= 10; $i++) {
            $label_phase = $_POST["label_phase" . $i];
            //$phaseId = Phase::where([["project_type", "=", $request->project], ["name", "=", $label_phase]])->first()->id;
            $phaseGroupId = "";
            if ($label_phase != "") {
                $phaseGroupId = $this->savePhaseGroup($request, $label_phase, $group);
                
                //phase items削除
                //$delObj = PhaseItems::where([['phase_group_id', '=', $phaseGroupId]]);
                //$delObj->delete();

                $this->savePhaseItems($phaseGroupId, $i);
            }
        }
    }

    public function savePhaseGroup($request,$label_phase,$group) {
        $phaseGroupObj = PhaseGroup::Join("phase", "phase.id", "=", "phase group.phase_id")
                ->leftJoin("project_type", "project_type.id", "=", "phase.project_type")
                ->select("phase group.id as id")
                ->where([["phase.project_type", "=", $request->project], ["phase.name", "=", $label_phase]]);
        if ($group != "") {
            $phaseGroupObj = $phaseGroupObj->where([["group", "=", $group]]);
        }

        if ($phaseGroupObj->exists()) {
            foreach ($phaseGroupObj->get() as $items) {
                $phaseGroupId = $items->id;
            }
        }


        /*if($group != ""){
            $phaseGroupObj = $phaseGroupObj->where([["group","=",$group]]);
        }
        if ($phaseGroupObj->exists()) {
            foreach ($phaseGroupObj->get() as $items) {
                $phaseGroupId = $items->id;
            }
        } else {
            $phaseId = Phase::where([["project_type", "=", $request->project], ["name", "=", $label_phase]])->first()->id;

            $pTable = new PhaseGroup;
            $pTable->project_id = $request->project;//$request->client;
            $pTable->phase_id = $phaseId;
            $pTable->group = $group;

            $pTable->save();

            $phaseGroupId = $pTable->id;
        }*/
        
        return $phaseGroupId;
    }
    
    public function savePhaseItems($phaseGroupId,$index) {
        for ($taskCnt = 1; $taskCnt < 50; $taskCnt++) {
            if (!isset($_POST["phase" . $index . "_task" . $taskCnt])) {
                break;
            }
            
            if($_POST["phase" . $index . "_task" . $taskCnt] == "" && $_POST["phase" . $index . "_description" . $taskCnt] == ""){
                continue;
            }

            //$targetPhaseItem = PhaseItems::where([["phase_group_id", "=", $phaseGroupId], ["order", "=", $taskCnt]]);
            $targetPhaseItem = PhaseItems::where([["id", "=", $_POST["phase" . $index . "_phase_item_id" . $taskCnt]]]);
            if ($targetPhaseItem->exists()) {
                //update
                $updateItem = [
                    "name" => $_POST["phase" . $index . "_task" . $taskCnt],
                    "description" => $_POST["phase" . $index . "_description" . $taskCnt],
                ];
                $targetPhaseItem->update($updateItem);
            } else {
                //phase item
                $table = new PhaseItems;
                $table->phase_group_id = $phaseGroupId;
                $table->name = $_POST["phase" . $index . "_task" . $taskCnt];
                $table->is_standard = True;
                $table->order = $taskCnt;
                $table->is_deleted = "0";
                $table->description = $_POST["phase" . $index . "_description" . $taskCnt];

                $table->save();
            }
        }
    }

    public function deleteWorkRow(Request $request) {
        $phaseItemId = $request->phaseItemId;
        $targetPhaseItem = PhaseItems::where([["id", "=", $phaseItemId]]);
        if ($targetPhaseItem->exists()) {
            //update
            $updateItem = [
                "is_deleted" => "1",                
            ];
            $targetPhaseItem->update($updateItem);
        }
    }
    
    public function monthArray(){
        $monthArray = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];
        return $monthArray;
    }

}

//=======================================================================
    
    