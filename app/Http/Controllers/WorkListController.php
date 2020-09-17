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
use App\ProjectPhaseItem;

//=======================================================================
class WorkListController extends Controller {

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

        return view("master/work_list", compact("client", "project"));
    }

    public function getWorkList(Request $request) {
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
            array_push($phaseItemList, 
                    PhaseItems::select("phase items.id as id","name","description","due_date","preparer","planed_prep","prep_sign_off","reviewer","planned_review","review_sign_off","reviewer2","planned_review2","review_sign_off2")
                    ->leftJoin("project phase item","project phase item.phase_item_id","=","phase items.id")
                    ->where([['phase_group_id', '=', $items->id]])->orderBy("order")->get());
        }
        
        $staffData = Staff::ActiveStaffOrderByInitial();

        $json = [
            "phase" => $phaseData,
            "phase1Detail" => $phaseItemList,
            "staff" => $staffData,                
        ];

        return response()->json($json);
    }

    public function save(Request $request) {
        
        //throw new Exception('ゼロによる除算。');
       
        for ($i = 1; $i <= 10; $i++) {  //phase
            if ($_POST["label_phase" . $i] == "") {
                continue;
            }   
            
            for($j = 1; $j <= 20; $j++){ //明細数
                if(!isset($_POST["phase" . $i . "_comp" . $j])){
                    break;
                }
                if ($_POST["phase" . $i . "_comp" . $j] != "" || $_POST["phase" . $i . "_prep" . $j] != "" 
                        || $_POST["phase" . $i . "_planned_prep" . $j] != "" || $_POST["phase" . $i . "_prep_signoff" . $j] != ""
                        || $_POST["phase" . $i . "_reviewer" . $j] != "" || $_POST["phase" . $i . "_planned_review" . $j] != "" 
                        || $_POST["phase" . $i . "_review_signoff" . $j] != "" || $_POST["phase" . $i . "_reviewer2" . $j] != ""
                        || $_POST["phase" . $i . "_planned_review2" . $j] != "" || $_POST["phase" . $i . "_review_signoff2" . $j] != "") {
                    
                    $queryObj = ProjectPhaseItem::where("phase_item_id","=",$_POST["phase" . $i . "_id" . $j]);
                    $projectPhaseItemId = "";
                    if($queryObj->exists()){
                        $projectPhaseItemId = $queryObj->first()->id;
                    }
                    
                    if($projectPhaseItemId == ""){
                        //insert
                        $table = new ProjectPhaseItem;
                        $table->phase_item_id = $_POST["phase" . $i . "_id" . $j];
                        $table->memo = "";
                        $table->due_date = $this->convDateFormat($_POST["phase" . $i . "_comp" . $j]);
                        
                        if($_POST["phase" . $i . "_prep" . $j] == ""){
                            $table->preparer = 0;                            
                        }else{
                            $table->preparer = $_POST["phase" . $i . "_prep" . $j];
                        }                        
                        $table->planed_prep = $this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]);
                        $table->prep_sign_off = $this->convDateFormat($_POST["phase" . $i . "_prep_signoff" . $j]);   
                        if($_POST["phase" . $i . "_reviewer" . $j] == ""){
                            $table->reviewer = 0;                            
                        }else{
                            $table->reviewer = $_POST["phase" . $i . "_reviewer" . $j];
                        }                        
                        $table->planned_review = $this->convDateFormat($_POST["phase" . $i . "_planned_review" . $j]);
                        $table->review_sign_off = $this->convDateFormat($_POST["phase" . $i . "_review_signoff" . $j]);
                        if($_POST["phase" . $i . "_reviewer2" . $j] == ""){
                            $table->reviewer2 = 0;                            
                        }else{
                            $table->reviewer2 = $_POST["phase" . $i . "_reviewer2" . $j];
                        }                         
                        $table->planned_review2 = $this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]);
                        $table->review_sign_off2 = $this->convDateFormat($_POST["phase" . $i . "_review_signoff2" . $j]);
                                                
                        $table->save();            
                        
                    }else{
                        //update
                        $prep = 0;
                        if($_POST["phase" . $i . "_prep" . $j] != ""){
                            $prep = $_POST["phase" . $i . "_prep" . $j];                            
                        }
                        $reviewer = 0;
                        if($_POST["phase" . $i . "_reviewer" . $j] != ""){
                            $reviewer = $_POST["phase" . $i . "_reviewer" . $j];                            
                        }
                        $reviewer2 = 0;
                        if($_POST["phase" . $i . "_reviewer2" . $j] != ""){
                            $reviewer2 = $_POST["phase" . $i . "_reviewer2" . $j];                            
                        }
                        
                        $updateItem = [
                            "due_date" => $this->convDateFormat($_POST["phase" . $i . "_comp" . $j]),
                            "preparer" => $prep,//$_POST["phase" . $i . "_prep" . $j],
                            "planed_prep" => $this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]),
                            "prep_sign_off" => $this->convDateFormat($_POST["phase" . $i . "_prep_signoff" . $j]),
                            "reviewer" => $reviewer,//$_POST["phase" . $i . "_reviewer" . $j],
                            "planned_review" => $this->convDateFormat($_POST["phase" . $i . "_planned_review" . $j]),
                            "review_sign_off" => $this->convDateFormat($_POST["phase" . $i . "_review_signoff" . $j]),
                            "reviewer2" => $reviewer2,//$_POST["phase" . $i . "_reviewer2" . $j],
                            "planned_review2" => $this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]),
                            "review_sign_off2" => $this->convDateFormat($_POST["phase" . $i . "_review_signoff2" . $j]),
                        ];
                        $queryObj->update($updateItem);
                    }                    
                }                
            }            
        }

        $client = Client::orderBy("name", "asc")->get();
        //project
        $project = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();

        return view("master/work_list", compact("client", "project"));
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
    
    public function convDateFormat($value){
        $convedValue = NULL;
        if($value != ""){
            $valueArray = explode("/",$value);
            $convedValue = $valueArray[2] . "-" . $valueArray[0] . "-" . $valueArray[1];
        }
        return $convedValue;
    }

}

//=======================================================================
    
    