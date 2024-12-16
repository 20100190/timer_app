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

    public function indexLink(Request $request) {

        $reqClientId = $request->client;
        $reqProjectId = $request->project;
        $reqGroup = "";
        if(isset($request->group)){
            $reqGroup = $request->group;
        }

        //client
        $client = Client::orderBy("name", "asc")->get();
        //project
        $project = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();

        return view("master/work_list", compact("client", "project", "reqClientId", "reqProjectId", "reqGroup"));
    }

    public function getWorkList(Request $request) {
        //project type
        $projectType = explode(" - ", $request->project)[0];
        //phase
        $projectTypeId = ProjectType::where("project_type", $projectType)->first()->id;
        $phaseData = Phase::where("project_type", $projectTypeId)->get();

        $group = $request->group;
        if ($group == "blank") {
            $group = "";
        }

        $projectId = Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->id;

        $projectPhaseItemList = ProjectPhaseItem::join("phase items", "project phase item.phase_item_id", "=", "phase items.id")
                ->join("phase group", "phase group.id", "=", "phase items.phase_group_id")
                ->join("phase", "phase group.phase_id", "=", "phase.id")
                //->where([["phase group.project_id","=",$request->client],["project phase item.project_id","=",$projectId]]);
                ->where([["phase group.project_id", "=", $projectTypeId], ["project phase item.project_id", "=", $projectId]]);
        if ($group != "") {
            $projectPhaseItemList = $projectPhaseItemList->where([["phase group.group", "=", $request->group]]);
        }

        $phaseItemList = [];
        if ($projectPhaseItemList->exists()) {
            $phaseGroupObj = $projectPhaseItemList->select("phase group.id as id")->distinct()->get();
            foreach ($phaseGroupObj as $items) {
                array_push($phaseItemList, PhaseItems::select("phase items.id as id", "name", "description", "due_date", "preparer", "planed_prep", "prep_sign_off", "reviewer", "planned_review", "review_sign_off", "reviewer2", "planned_review2", "review_sign_off2", "is_standard", "memo", "col_memo")
                                ->leftJoin("project phase item", "project phase item.phase_item_id", "=", "phase items.id")
                                ->where([['phase_group_id', '=', $items->id], ["project phase item.project_id", "=", $projectId]])->orderBy("order")->get());
            }
        } else {
            //$phaseItemList = [];
            $phaseIdArray = Phase::select("id")->where([['project_type', '=', $projectTypeId]])->get();
            $phaseIdList = [];
            foreach ($phaseIdArray as $items) {
                array_push($phaseIdList, $items->id);
            }
            $phaseGroupList = PhaseGroup::whereIn("phase_id", $phaseIdList)->where("group", $group)->where([["project_id", "=", $projectTypeId]])->get();
            foreach ($phaseGroupList as $items) {
                //$phaseItemList = PhaseItems::where([['phase_group_id', '=', $items->id]])->get();            
                array_push($phaseItemList, PhaseItems::where([['phase_group_id', '=', $items->id], ["is_standard", "=", 1]])->get());
            }
        }

        /* $phaseGroupList = PhaseGroup::where([['project_id', '=', $projectId],["group","=",$group]])->get();
          $phaseItemList = [];
          foreach ($phaseGroupList as $items) {
          //$phaseItemList = PhaseItems::where([['phase_group_id', '=', $items->id]])->get();
          array_push($phaseItemList,
          PhaseItems::select("phase items.id as id","name","description","due_date","preparer","planed_prep","prep_sign_off","reviewer","planned_review","review_sign_off","reviewer2","planned_review2","review_sign_off2")
          ->leftJoin("project phase item","project phase item.phase_item_id","=","phase items.id")
          ->where([['phase_group_id', '=', $items->id]])->orderBy("order")->get());
          } */

        $staffData = Staff::ActiveStaffOrderByInitial();
        
        //Annualizeされているか
        $annualizeCount = ProjectPhaseItem::select("phase group.group")
                ->leftJoin("phase items","project phase item.phase_item_id","=","phase items.id")
                ->leftJoin("phase group","phase items.phase_group_id","=","phase group.id")
                ->where("project phase item.project_id","=",$projectId)
                ->groupBy("phase group.group")                
                ->get()->count();

        $json = [
            "phase" => $phaseData,
            "phase1Detail" => $phaseItemList,
            "staff" => $staffData,
            "annualize" => $annualizeCount,
        ];

        return response()->json($json);
    }

    public function save(Request $request) {

        //throw new Exception('ゼロによる除算。');
        $projectId = Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->id;
        $projectType = Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->project_type;
        $projectTypeId = ProjectType::where([["project_type", "=", $projectType]])->first()->id;
        $groupVal = "";
        if (isset($request->group)) {
            $groupVal = $request->group;
        }

        //この画面で追加された行の情報を登録
        for ($i = 1; $i <= 10; $i++) {  //phase
            if ($_POST["label_phase" . $i] == "") {
                continue;
            }
            
            //standard以外のitems,project phase itemsを削除
            //--------------------------
            $phaseGroupId = $this->getProjectGroupId($projectTypeId, $_POST["label_phase" . $i],$groupVal);
            $delTargetObj = PhaseItems::where([["phase_group_id","=",$phaseGroupId],["is_standard","=",0]]);
            $delItemArray = [];
            foreach($delTargetObj->get() as $delItemID){
                array_push($delItemArray,$delItemID->id);
            }
            $delProPhaseObj = ProjectPhaseItem::wherein("phase_item_id",$delItemArray)->where([["project_id","=",$projectId]])->delete();
            //$delTargetObj->delete();
            //----------------------------

            for ($j = 1; $j <= 50; $j++) { //明細数
                if (!isset($_POST["phase" . $i . "_id" . $j])) {
                    break;
                }

                if ($_POST["phase" . $i . "_task" . $j] == "" && $_POST["phase" . $i . "_description" . $j] == "") {
                    continue;
                }

                //phase group              
                $phaseGroupId = $this->getProjectGroupId($projectTypeId, $_POST["label_phase" . $i],$groupVal);

                //phase item                
                $targetPhaseItem = PhaseItems::where([["phase_group_id", "=", $phaseGroupId],["order","=",$j],["name","=",$_POST["phase" . $i . "_task" . $j]]]);
                
                //$targetPhaseItem = PhaseItems::where([["id", "=", $_POST["phase" . $i . "_id" . $j]]]);
                
                if ($targetPhaseItem->exists()) {
                    //update
                    $updateItem = [
                        "name" => $_POST["phase" . $i . "_task" . $j],
                        "description" => $_POST["phase" . $i . "_description" . $j],
                    ];
                    $targetPhaseItem->update($updateItem);
                } else {
                    //phase item
                    $table = new PhaseItems;
                    $table->phase_group_id = $phaseGroupId;
                    $table->name = $_POST["phase" . $i . "_task" . $j];
                    $table->is_standard = False;
                    $table->order = $j;
                    $table->description = $_POST["phase" . $i . "_description" . $j];

                    $table->save();
                }
            }
        }

        //-------------------------------------------------------------        
        //project phase item        
        for ($i = 1; $i <= 10; $i++) {  //phase
            if ($_POST["label_phase" . $i] == "") {
                continue;
            }
                        

            for ($j = 1; $j <= 50; $j++) { //明細数
                if (!isset($_POST["phase" . $i . "_comp" . $j])) {
                    break;
                }
                
                $reqPhaseItemId = $_POST["phase" . $i . "_id" . $j];
                if ($reqPhaseItemId == "") {
                    $phaseGroupId = $this->getProjectGroupId($projectTypeId, $_POST["label_phase" . $i],$groupVal);
                    $reqPhaseItemId = PhaseItems::where([["phase_group_id", "=", $phaseGroupId],["order","=",$j]])->first()->id;
                }

                $queryObj = ProjectPhaseItem::where([["phase_item_id", "=", $reqPhaseItemId], ["project_id", "=", $projectId]]);
                $projectPhaseItemId = "";
                if ($queryObj->exists()) {
                    $projectPhaseItemId = $queryObj->first()->id;
                }

                if ($projectPhaseItemId == "") {
                    $targetPhaseItem = "";                    
                    $phaseGroupObj = PhaseGroup::Join("phase", "phase.id", "=", "phase group.phase_id")
                            ->select("phase group.id as id")
                            ->where([["phase group.project_id", "=", $projectTypeId], ["phase.name", "=", $_POST["label_phase" . $i]]]);
                    if ($groupVal != "") {
                        $phaseGroupObj = $phaseGroupObj->where([["group", "=", $groupVal]]);
                    }
                    if ($phaseGroupObj->exists()) {
                        $phaseGroupId = $phaseGroupObj->first()->id;
                        $targetPhaseItem = PhaseItems::where([["phase_group_id", "=", $phaseGroupId], ["order", "=", $j], ["name", "=", $_POST["phase" . $i . "_task" . $j]], ["description", "=", $_POST["phase" . $i . "_description" . $j]]])->first()->id;
                    }

                    //insert
                    $table = new ProjectPhaseItem;
                    $table->project_id = $projectId;                    
                    $table->phase_item_id = $targetPhaseItem;
                    $table->memo = "";
                    $table->due_date = $this->convDateFormat($_POST["phase" . $i . "_comp" . $j]);

                    if ($_POST["phase" . $i . "_prep" . $j] == "") {
                        $table->preparer = 0;
                    } else {
                        $table->preparer = $_POST["phase" . $i . "_prep" . $j];
                    }
                    $table->planed_prep = $this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]);
                    $table->prep_sign_off = $this->convDateFormat($_POST["phase" . $i . "_prep_signoff" . $j]);
                    if ($_POST["phase" . $i . "_reviewer1" . $j] == "") {
                        $table->reviewer = 0;
                    } else {
                        $table->reviewer = $_POST["phase" . $i . "_reviewer1" . $j];
                    }
                    $table->planned_review = $this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]);
                    $table->review_sign_off = $this->convDateFormat($_POST["phase" . $i . "_review_signoff1" . $j]);
                    if ($_POST["phase" . $i . "_reviewer2" . $j] == "") {
                        $table->reviewer2 = 0;
                    } else {
                        $table->reviewer2 = $_POST["phase" . $i . "_reviewer2" . $j];
                    }
                    $table->planned_review2 = $this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]);
                    $table->review_sign_off2 = $this->convDateFormat($_POST["phase" . $i . "_review_signoff2" . $j]);
                    $table->memo = $_POST["phase" . $i . "_memo" . $j];
                    $table->col_memo = $_POST["phase" . $i . "_col_memo" . $j];

                    $table->save();
                } else {
                    //update
                    $prep = 0;
                    if ($_POST["phase" . $i . "_prep" . $j] != "") {
                        $prep = $_POST["phase" . $i . "_prep" . $j];
                    }
                    $reviewer = 0;
                    if ($_POST["phase" . $i . "_reviewer1" . $j] != "") {
                        $reviewer = $_POST["phase" . $i . "_reviewer1" . $j];
                    }
                    $reviewer2 = 0;
                    if ($_POST["phase" . $i . "_reviewer2" . $j] != "") {
                        $reviewer2 = $_POST["phase" . $i . "_reviewer2" . $j];
                    }

                    $updateItem = [
                        "due_date" => $this->convDateFormat($_POST["phase" . $i . "_comp" . $j]),
                        "preparer" => $prep, //$_POST["phase" . $i . "_prep" . $j],
                        "planed_prep" => $this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]),
                        "prep_sign_off" => $this->convDateFormat($_POST["phase" . $i . "_prep_signoff" . $j]),
                        "reviewer" => $reviewer, //$_POST["phase" . $i . "_reviewer" . $j],
                        "planned_review" => $this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]),
                        "review_sign_off" => $this->convDateFormat($_POST["phase" . $i . "_review_signoff1" . $j]),
                        "reviewer2" => $reviewer2, //$_POST["phase" . $i . "_reviewer2" . $j],
                        "planned_review2" => $this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]),
                        "review_sign_off2" => $this->convDateFormat($_POST["phase" . $i . "_review_signoff2" . $j]),
                        "memo" => $_POST["phase" . $i . "_memo" . $j],
                        "col_memo" => $_POST["phase" . $i . "_col_memo" . $j],
                    ];
                    $queryObj->update($updateItem);
                }
                //}                
            }
        }
       
        //Monthly Data Expand
        if ($_POST["clicked_button"] == "monthlyData") {
            $this->expandMonthlyData($request, "February",1);
            $this->expandMonthlyData($request, "March",2);
            $this->expandMonthlyData($request, "April",3);
            $this->expandMonthlyData($request, "May",4);
            $this->expandMonthlyData($request, "June",5);
            $this->expandMonthlyData($request, "July",6);
            $this->expandMonthlyData($request, "August",7);
            $this->expandMonthlyData($request, "September",8);
            $this->expandMonthlyData($request, "October",9);
            $this->expandMonthlyData($request, "November",10);
            $this->expandMonthlyData($request, "December",11);
        }

        $client = Client::orderBy("name", "asc")->get();
        //project
        $project = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();

        return view("master/work_list", compact("client", "project"));
    }

    public function insertPhaseGroupAndPhaseItems($projectId, $projectTypeId, $label_phase, $index, $group) {

        $phaseId = Phase::where([["project_type", "=", $projectTypeId], ["name", "=", $label_phase]])->first()->id;

        //phase        
        $queryObj = PhaseGroup::where([['project_id', '=', $projectId], ["phase_id", "=", $phaseId], ["group", "=", $group]]);
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

    public function convDateFormat($value) {
        $convedValue = NULL;
        if ($value != "") {
            $valueArray = explode("/", $value);
            $convedValue = $valueArray[2] . "-" . $valueArray[0] . "-" . $valueArray[1];
        }
        return $convedValue;
    }

    function expandMonthlyData($request, $group,$offsetMonth) {
        $projectId = Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->id;
        $projectType = Project::where([["client_id", "=", $request->client], ["project_name", "=", $request->project]])->first()->project_type;
        $projectTypeId = ProjectType::where([["project_type", "=", $projectType]])->first()->id;
        $groupVal = $group;
      
        for ($i = 1; $i <= 10; $i++) {  //phase
            if ($_POST["label_phase" . $i] == "") {
                continue;
            }

            for ($j = 1; $j <= 50; $j++) { //明細数
                if (!isset($_POST["phase" . $i . "_comp" . $j])) {
                    break;
                }

                $phaseItemId = PhaseGroup::select("phase items.id as id","phase group.id as phase_group_id")->leftJoin("phase items", "phase group.id", "=", "phase items.phase_group_id")
                                ->leftJoin("phase", "phase.id", "=", "phase group.phase_id")
                                ->where([["phase group.group", "=", $group], ["phase items.order", "=", $j], ["phase.name", "=", $_POST["label_phase" . $i]]]);

                if(!$phaseItemId->exists()){  
                    $pgID = PhaseGroup::select("phase group.id as id")->leftJoin("phase", "phase.id", "=", "phase group.phase_id")
                            ->where([["phase group.group", "=", $group],["project_id","=",$projectTypeId], ["phase.name", "=", $_POST["label_phase" . $i]]])->first()->id;
                    //continue;
                    //phase item 作成
                    //phase item
                    $table = new PhaseItems;
                    $table->phase_group_id = $pgID;
                    $table->name = $_POST["phase" . $i . "_task" . $j];
                    $table->is_standard = False;
                    $table->order = $j;
                    $table->description = $_POST["phase" . $i . "_description" . $j];

                    $table->save();
                    
                    $phaseItemId = $table->id;
                }else {
                    $phaseItemId = $phaseItemId->first()->id;
                }
                
                $phaseItemObj = ProjectPhaseItem::where([["phase_item_id", "=", $phaseItemId],["project_id","=",$projectId]]);

                if (!$phaseItemObj->exists()) {
                    //insert
                    $table = new ProjectPhaseItem;
                    $table->project_id = $projectId;
                    $table->phase_item_id = $phaseItemId;
                    $table->memo = "";
                    
                    $dueDateVal = $this->convDateFormat($_POST["phase" . $i . "_comp" . $j]);
                    if($dueDateVal != NULL){
                        $dueDateVal = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_comp" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    $table->due_date = $dueDateVal;

                    if ($_POST["phase" . $i . "_prep" . $j] == "") {
                        $table->preparer = 0;
                    } else {
                        $table->preparer = $_POST["phase" . $i . "_prep" . $j];
                    }
                    
                    $planedPrepVal = $this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]);
                    if($planedPrepVal != NULL){
                        $planedPrepVal = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    $table->planed_prep = $planedPrepVal;//$this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]);
                    $table->prep_sign_off = NULL;//$this->convDateFormat($_POST["phase" . $i . "_prep_signoff" . $j]);
                    if ($_POST["phase" . $i . "_reviewer1" . $j] == "") {
                        $table->reviewer = 0;
                    } else {
                        $table->reviewer = $_POST["phase" . $i . "_reviewer1" . $j];
                    }
                    $planedReviewVal = $this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]);
                    if($planedReviewVal != NULL){
                        $planedReviewVal = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    $table->planned_review = $planedReviewVal;//$this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]);
                    $table->review_sign_off = NULL;//$this->convDateFormat($_POST["phase" . $i . "_review_signoff1" . $j]);
                    if ($_POST["phase" . $i . "_reviewer2" . $j] == "") {
                        $table->reviewer2 = 0;
                    } else {
                        $table->reviewer2 = $_POST["phase" . $i . "_reviewer2" . $j];
                    }
                    $planedReview2Val = $this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]);
                    if($planedReview2Val != NULL){
                        $planedReview2Val = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    $table->planned_review2 = $planedReview2Val;//$this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]);
                    $table->review_sign_off2 = NULL;//$this->convDateFormat($_POST["phase" . $i . "_review_signoff2" . $j]);
                    $table->memo = $_POST["phase" . $i . "_memo" . $j];
                    $table->col_memo = $_POST["phase" . $i . "_col_memo" . $j];
                    
                    //Phase1 Monthly以外はブランクに
                    $xID = PhaseItems::leftJoin("phase group", "phase items.phase_group_id", "=", "phase group.id")->where([["phase items.id","=",$phaseItemId]])->first()->phase_id;
                    if($xID != 16){
                        $table->due_date = NULL;
                        $table->preparer = 0;
                        $table->planed_prep = NULL;
                        $table->prep_sign_off = NULL;
                        $table->reviewer = 0;
                        $table->planned_review = NULL;
                        $table->review_sign_off = NULL;
                        $table->reviewer2 = 0;
                        $table->planned_review2 =  NULL;
                        $table->review_sign_off2 = NULL;
                        $table->memo = "";
                        $table->col_memo = "";
                    }

                    $table->save();
                } else {
                    //update
                    $prep = 0;
                    if ($_POST["phase" . $i . "_prep" . $j] != "") {
                        $prep = $_POST["phase" . $i . "_prep" . $j];
                    }
                    $reviewer = 0;
                    if ($_POST["phase" . $i . "_reviewer1" . $j] != "") {
                        $reviewer = $_POST["phase" . $i . "_reviewer1" . $j];
                    }
                    $reviewer2 = 0;
                    if ($_POST["phase" . $i . "_reviewer2" . $j] != "") {
                        $reviewer2 = $_POST["phase" . $i . "_reviewer2" . $j];
                    }
                    
                    $dueDateVal = $this->convDateFormat($_POST["phase" . $i . "_comp" . $j]);
                    if($dueDateVal != NULL){
                        $dueDateVal = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_comp" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    
                    $planedPrepVal = $this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]);
                    if($planedPrepVal != NULL){
                        $planedPrepVal = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_planned_prep" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    
                    $planedReviewVal = $this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]);
                    if($planedReviewVal != NULL){
                        $planedReviewVal = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    
                    $planedReview2Val = $this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]);
                    if($planedReview2Val != NULL){
                        $planedReview2Val = date("Y-m-d",strtotime($this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]) . "+" . $offsetMonth . " month"));
                    }
                    
                    $memoVal = $_POST["phase" . $i . "_memo" . $j];
                    $colMemoVal = $_POST["phase" . $i . "_col_memo" . $j];
                    
                    //Phase1 Monthly以外はブランクに
                    $xID = PhaseItems::leftJoin("phase group", "phase items.phase_group_id", "=", "phase group.id")->where([["phase items.id","=",$phaseItemId]])->first()->phase_id;
                    if($xID != 16){
                        $dueDateVal = NULL;
                        $prep = 0;
                        $planedPrepVal = NULL;                        
                        $reviewer = 0;
                        $planedReviewVal = NULL;                        
                        $reviewer2 = 0;
                        $planedReview2Val =  NULL;                        
                        $memoVal = "";
                        $colMemoVal = "";
                    }


                    $updateItem = [
                        "due_date" => $dueDateVal,
                        "preparer" => $prep, //$_POST["phase" . $i . "_prep" . $j],
                        "planed_prep" => $planedPrepVal,
                        "prep_sign_off" => NULL,//$this->convDateFormat($_POST["phase" . $i . "_prep_signoff" . $j]),
                        "reviewer" => $reviewer, //$_POST["phase" . $i . "_reviewer" . $j],
                        "planned_review" => $planedReviewVal,//$this->convDateFormat($_POST["phase" . $i . "_planned_review1" . $j]),
                        "review_sign_off" => NULL,//$this->convDateFormat($_POST["phase" . $i . "_review_signoff1" . $j]),
                        "reviewer2" => $reviewer2, //$_POST["phase" . $i . "_reviewer2" . $j],
                        "planned_review2" => $planedReview2Val,//$this->convDateFormat($_POST["phase" . $i . "_planned_review2" . $j]),
                        "review_sign_off2" => NULL,//$this->convDateFormat($_POST["phase" . $i . "_review_signoff2" . $j]),
                        "memo" => $memoVal,//$_POST["phase" . $i . "_memo" . $j],
                        "col_memo" => $colMemoVal,//$_POST["phase" . $i . "_col_memo" . $j],
                    ];
                    $phaseItemObj->update($updateItem);
                }
            }
        }
    }
    
    function getProjectGroupId($projectTypeId,$labelPhase,$groupVal) {
        $phaseGroupObj = PhaseGroup::Join("phase", "phase.id", "=", "phase group.phase_id")
                ->select("phase group.id as id")
                ->where([["phase group.project_id", "=", $projectTypeId], ["phase.name", "=", $labelPhase]]);
        if($groupVal != ""){
            $phaseGroupObj = $phaseGroupObj->where([["group", "=", $groupVal]]);
        }
        $phaseGroupId = "";
        if ($phaseGroupObj->exists()) {
            //foreach ($phaseGroupObj->get() as $items) {
            //    $phaseGroupId = $items->id;
            //}
            $phaseGroupId = $phaseGroupObj->first()->id;
        } else {
            $phaseId = Phase::where([["project_type", "=", $projectTypeId], ["name", "=", $labelPhase]])->first()->id;

            $pTable = new PhaseGroup;
            $pTable->project_id = $request->client;
            $pTable->phase_id = $phaseId;
            $pTable->group = "";

            $pTable->save();

            $phaseGroupId = $pTable->id;
        }

        return $phaseGroupId;
    }

}

//=======================================================================
    
    
