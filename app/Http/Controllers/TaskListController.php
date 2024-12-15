<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validate;
use DB;
use App\Task;
use App\Client;
use App\Staff;
use App\ProjectPhaseItem;
use App\ToDoList;

//=======================================================================
class TaskListController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        //client
        $clientData = Client::orderBy("name", "asc")->get();

        //pic
        $picData = Staff::ActiveStaffOrderByInitial(); //Staff::ActiveStaff();
        //staff
        $staffData = Staff::ActiveStaffOrderByInitial();

        return view("task_list")
                        ->with("client", $clientData)
                        ->with("pic", $picData)
                        ->with("staff", $staffData);
    }

    function getTaskScheduleData(Request $request) {

        ini_set('memory_limit', '256M');
        
        $status = $request->status;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $staff =  explode(",", $request->staff);
        
        $taskScheduleQuery = ProjectPhaseItem::select("client.id as client_id","project.id as project_id","due_date", "phase items.name as task", "phase items.description as description", "project_name", "client.name as client_name", "phase.name as phase_name","preparer","reviewer","reviewer2","planed_prep","planned_review","planned_review2","prep_sign_off","review_sign_off","review_sign_off2","prep.id as prep_user_id","rev1.id as rev1_user_id","rev2.id as rev2_user_id","prep.initial as prep_user","rev1.initial as review_user","rev2.initial as review2_user","col_memo","client.fye")
                ->leftjoin("phase items", "phase items.id", "=", "project phase item.phase_item_id")
                ->leftjoin("project", "project.id", "=", "project phase item.project_id")
                ->leftjoin("client", "project.client_id", "=", "client.id")
                ->leftjoin("phase group", "phase items.phase_group_id", "=", "phase group.id")
                ->leftjoin("phase", "phase.id", "=", "phase group.phase_id")
                ->leftjoin("staff as prep", "prep.id", "=", "project phase item.preparer")
                ->leftjoin("staff as rev1", "rev1.id", "=", "project phase item.reviewer")
                ->leftjoin("staff as rev2", "rev2.id", "=", "project phase item.reviewer2");
        
        if($request->client != "blank"){
            $taskScheduleQuery = $taskScheduleQuery->whereIn("client.id",explode(",", $request->client));
        }
        
        if($request->pic != "blank"){
            $taskScheduleQuery = $taskScheduleQuery->whereIn("project.pic",explode(",", $request->pic));
        }

        //fye
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

            $taskScheduleQuery = $taskScheduleQuery
                    ->wherein(DB::raw('Substring(client.fye,1,2)'), $fyeFilter);
        }
              
        $taskScheduleObj = $taskScheduleQuery->get();        
        if($request->group == "Phase"){            
            $taskScheduleObj = $taskScheduleQuery->orderBy("client_id")->orderBy("project_id")->orderBy("phase_name")->get();            
        }else if($request->group == "Project"){
            $taskScheduleObj = $taskScheduleQuery->orderBy("client_id")->orderBy("project_id")->get();
        }

        $taskScheduleData = [];
        
        foreach ($taskScheduleObj as $items) {    
        
        	//to do listのみの検索であれば出さない。
            if($request->phase == "ToDo"){
                break;
            }
           
            $taskScheduleDataItem = [];
            //review2
            if ($items->planned_review2 != null && $items->review_sign_off2 == null && $status != "Completed") {
                $dateymd = intval(str_replace("-", "", $items->planned_review2));
                if ($this->isDateRange($dateFrom, $dateTo, $dateymd)) {
                    if($request->staff == "blank" || in_array($items->rev2_user_id,$staff)) {
                        $taskScheduleDataItem["user"] = $items->review2_user;
                        $taskScheduleDataItem["due_date"] = $items->planned_review2;
                        $taskScheduleDataItem["client_id"] = $items->client_id;
                        $taskScheduleDataItem["client_name"] = $items->client_name;
                        $taskScheduleDataItem["project_id"] = $items->project_id;
                        $taskScheduleDataItem["project_name"] = $items->project_name;
                        $taskScheduleDataItem["phase_name"] = $items->phase_name;
                        $taskScheduleDataItem["task"] = $items->task;
                        $taskScheduleDataItem["description"] = $items->description;
                        $taskScheduleDataItem["memo"] = $items->col_memo;
                        $taskScheduleDataItem["status"] = "Imcomplete";
                        $taskScheduleDataItem["fye"] = $items->fye;
                    }
                }
            }

            //review1
            if ($items->planned_review != null && $items->review_sign_off == null && $status != "Completed") {
                $dateymd = intval(str_replace("-", "", $items->planned_review));
                if ($this->isDateRange($dateFrom, $dateTo, $dateymd)) {
                    if ($request->staff == "blank" || in_array($items->rev1_user_id, $staff)) {
                        $taskScheduleDataItem["user"] = $items->review_user;
                        $taskScheduleDataItem["due_date"] = $items->planned_review;
                        $taskScheduleDataItem["client_id"] = $items->client_id;
                        $taskScheduleDataItem["client_name"] = $items->client_name;
                        $taskScheduleDataItem["project_id"] = $items->project_id;
                        $taskScheduleDataItem["project_name"] = $items->project_name;
                        $taskScheduleDataItem["phase_name"] = $items->phase_name;
                        $taskScheduleDataItem["task"] = $items->task;
                        $taskScheduleDataItem["description"] = $items->description;
                        $taskScheduleDataItem["memo"] = $items->col_memo;
                        $taskScheduleDataItem["status"] = "Imcomplete";
                        $taskScheduleDataItem["fye"] = $items->fye;
                    }
                }
            }

            //preparer
            if ($items->planed_prep != null && $items->prep_sign_off == null && $status != "Completed") {
                $dateymd = intval(str_replace("-", "", $items->planed_prep));
                if ($this->isDateRange($dateFrom, $dateTo, $dateymd)) {
                    if ($request->staff == "blank" || in_array($items->prep_user_id, $staff)) {
                        $taskScheduleDataItem["user"] = $items->prep_user;
                        $taskScheduleDataItem["due_date"] = $items->planed_prep;
                        $taskScheduleDataItem["client_id"] = $items->client_id;
                        $taskScheduleDataItem["client_name"] = $items->client_name;
                        $taskScheduleDataItem["project_id"] = $items->project_id;
                        $taskScheduleDataItem["project_name"] = $items->project_name;
                        $taskScheduleDataItem["phase_name"] = $items->phase_name;
                        $taskScheduleDataItem["task"] = $items->task;
                        $taskScheduleDataItem["description"] = $items->description;
                        $taskScheduleDataItem["memo"] = $items->col_memo;
                        $taskScheduleDataItem["status"] = "Imcomplete";
                        $taskScheduleDataItem["fye"] = $items->fye;
                    }
                }
            }

            //complete
            if ($items->prep_sign_off != null && $items->review_sign_off != null && $items->review_sign_off2 != null && $status != "Imcomplete") {
                $dateymd = intval(str_replace("-", "", $items->planned_review2));
                if ($this->isDateRange($dateFrom, $dateTo, $dateymd)) {
                    if ($request->staff == "blank" || in_array($items->rev2_user_id, $staff)) {
                        $taskScheduleDataItem["user"] = $items->review2_user;
                        $taskScheduleDataItem["due_date"] = $items->planned_review2;
                        $taskScheduleDataItem["client_id"] = $items->client_id;
                        $taskScheduleDataItem["client_name"] = $items->client_name;
                        $taskScheduleDataItem["project_id"] = $items->project_id;
                        $taskScheduleDataItem["project_name"] = $items->project_name;
                        $taskScheduleDataItem["phase_name"] = $items->phase_name;
                        $taskScheduleDataItem["task"] = $items->task;
                        $taskScheduleDataItem["description"] = $items->description;
                        $taskScheduleDataItem["memo"] = $items->col_memo;
                        $taskScheduleDataItem["status"] = "Complete";
                        $taskScheduleDataItem["fye"] = $items->fye;
                    }
                }
            }


            if(isset($taskScheduleDataItem["user"])){
                array_push($taskScheduleData,$taskScheduleDataItem);
            }
            
        }
        
        //To Do Listのrecord追加
        $toDoListQuery = ToDoList::select("to_do_list.*","client.name as client_name","staff.initial as staff_initial","project.project_name as project_name","fye")
                            ->leftJoin("client","to_do_list.client_id","=","client.id")
                            ->leftJoin("staff","to_do_list.requestor_id","=","staff.id")
                            ->leftJoin("project","to_do_list.project_id","=","project.id");
        
        if($request->client != "blank"){
            $toDoListQuery = $toDoListQuery->whereIn("client.id",explode(",", $request->client));
        }
                            
        if($request->pic != "blank"){
            $toDoListQuery = $toDoListQuery->whereIn("project.pic",explode(",", $request->pic));
        }

        //fye
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

            $toDoListQuery = $toDoListQuery
                    ->wherein(DB::raw('Substring(client.fye,1,2)'), $fyeFilter);
        }

        //staff
        $asigneeStr = "";
        $assignArray = "";
        if($request->staff != "blank"){
            $assignArray = explode(",",$request->staff);
            for($i=0; $i<count($assignArray); $i++){                
                if($asigneeStr != ""){
                    $asigneeStr .= " or ";    
                }
                $asigneeStr .= "FIND_IN_SET(" . $assignArray[$i] . ",to_do_list.preparer_id)";                
            }            
            $toDoListQuery = $toDoListQuery->whereRaw("(" . $asigneeStr . ")");
        }

        //status
        if($request->status != "blank"){
            if($request->status == "Completed"){
                $toDoListQuery = $toDoListQuery->where([["to_do_list.progress","=","100"]]);
            }else{
                $toDoListQuery = $toDoListQuery->where([["to_do_list.progress","<>","100"]]);                        
            }
        }

        //date from to
        if($dateFrom != "blank" && $dateTo != "blank"){
            $convDateFrom = substr($dateFrom,0,4) . "-" . substr($dateFrom,4,2) . "-" . substr($dateFrom,6,2);
            $convDateTo = substr($dateTo,0,4) . "-" . substr($dateTo,4,2) . "-" . substr($dateTo,6,2);
            $toDoListQuery = $toDoListQuery->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") BETWEEN "' . $convDateFrom . '" and "' . $convDateTo . '"');   
        }

        if($dateFrom != "blank" && $dateTo == "blank"){
            $convDateFrom = substr($dateFrom,0,4) . "-" . substr($dateFrom,4,2) . "-" . substr($dateFrom,6,2);
            $toDoListQuery = $toDoListQuery->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") >= "' . $convDateFrom . '"');   
        }

        if($dateFrom == "blank" && $dateTo != "blank"){
            $convDateTo = substr($dateTo,0,4) . "-" . substr($dateTo,4,2) . "-" . substr($dateTo,6,2);
            $toDoListQuery = $toDoListQuery->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") <= "' . $convDateTo . '"');   
        }

        $toDoListData = $toDoListQuery->get();

        foreach($toDoListData as $items){

            //phaseの検索であれば出さない
            if($request->phase == "Phase"){
                break;
            }

            //preparer idが複数あれば複数行作成
            $taskScheduleDataItem = [];            
            $preparerIdArray = explode(",",$items->preparer_id);
            for($i=0; $i<count($preparerIdArray); $i++){
                //staffが検索条件にある場合、除外
                if($assignArray != "" && !in_array($preparerIdArray[$i], $assignArray)){
                    continue;
                }
                $userData = Staff::where([["id","=",$preparerIdArray[$i]]])->first();
                $taskScheduleDataItem["user"] = $userData->initial;

                //end time
                $endTime = explode("/", explode(" ",$items->end_time)[0]);
                $endTime = $endTime[2] . "-" . $endTime[0] . "-" . $endTime[1];
                $taskScheduleDataItem["due_date"] = $endTime;

                $taskScheduleDataItem["client_id"] = $items->client_id;
                $taskScheduleDataItem["client_name"] = $items->client_name;
                $taskScheduleDataItem["project_id"] = $items->project_id;
                $taskScheduleDataItem["project_name"] = $items->project_name;
                $taskScheduleDataItem["phase_name"] = "To Do List";
                $taskScheduleDataItem["task"] = $items->project_task_id;
                $taskScheduleDataItem["description"] = $items->memo;
                $taskScheduleDataItem["fye"] = $items->fye;

                //edit link
                $editLink = "/master/to-do-list/" . $items->id . "/edit-todo";

                $taskScheduleDataItem["memo"] = '<a href="' . $editLink . '" target="_blank">';
                $progress = "Imcomplete";
                if($items->progress == "100"){
                    $progress = "Completed";
                }
                $taskScheduleDataItem["status"] = $progress;

                array_push($taskScheduleData,$taskScheduleDataItem);

            }
/*            
            $taskScheduleDataItem = [];            
            $taskScheduleDataItem["user"] = $items->staff_initial;

            //end time
            $endTime = explode("/", explode(" ",$items->end_time)[0]);
            $endTime = $endTime[2] . "-" . $endTime[0] . "-" . $endTime[1];
            $taskScheduleDataItem["due_date"] = $endTime;

            $taskScheduleDataItem["client_id"] = $items->client_id;
            $taskScheduleDataItem["client_name"] = $items->client_name;
            $taskScheduleDataItem["project_id"] = $items->project_id;
            $taskScheduleDataItem["project_name"] = $items->project_name;
            $taskScheduleDataItem["phase_name"] = "To Do List";
            $taskScheduleDataItem["task"] = $items->project_task_id;
            $taskScheduleDataItem["description"] = $items->memo;

            //edit link
            $editLink = "/master/to-do-list/" . $items->id . "/edit-todo";

            $taskScheduleDataItem["memo"] = '<a href="' . $editLink . '" target="_blank">';
            $progress = "Imcomplete";
            if($items->progress == "100"){
                $progress = "Completed";
            }
            $taskScheduleDataItem["status"] = $progress;

            array_push($taskScheduleData,$taskScheduleDataItem);
*/
        }

        //phaseでgroup化
        $groupingArray = [];
        if($request->group == "Phase" && count($taskScheduleData) > 0){            
            $groupingArray = $this->getGroupPhaseData($taskScheduleData);
            $taskScheduleData = $groupingArray;
        }
        if($request->group == "Project" && count($taskScheduleData) > 0){
            $groupingArray = $this->getGroupProjectData($taskScheduleData);
            $taskScheduleData = $groupingArray;
        }        
        
        $json = [
            "taskSchedule" => $taskScheduleData,
            "grouping" => $groupingArray
        ];

        return response()->json($json);
    }

    function getGroupPhaseData($taskScheduleData){
        $groupingArray = [];
        $rowCnt = 0;
        $oldClientKey = -1;
        $newClientKey = $taskScheduleData[0]["client_id"];

        $oldProjectKey = -1;
        $newProjectKey = $taskScheduleData[0]["project_id"];

        $oldPhaseKey = -1;
        $newPhaseKey = $taskScheduleData[0]["phase_name"];
       
       
        do {            
            if($oldClientKey != $newClientKey || $oldProjectKey != $newProjectKey || $oldPhaseKey != $newPhaseKey){
                $groupingArrayItems = [];
                $groupingArrayItems["client_id"] = $taskScheduleData[$rowCnt]["client_id"];
                $groupingArrayItems["client_name"] = $taskScheduleData[$rowCnt]["client_name"];    
                $groupingArrayItems["project_id"] = $taskScheduleData[$rowCnt]["project_id"];   
                $groupingArrayItems["project_name"] = $taskScheduleData[$rowCnt]["project_name"];
                $groupingArrayItems["phase_name"] = $taskScheduleData[$rowCnt]["phase_name"];                
                $groupingArrayItems["user"] = $taskScheduleData[$rowCnt]["user"];                
                $groupingArrayItems["description"] = "";               
                $groupingArrayItems["memo"] = "";               
                $groupingArrayItems["task"] = "";               
                $groupingArrayItems["fye"] = $taskScheduleData[$rowCnt]["fye"];               
                $groupingArrayItems["status"] = $taskScheduleData[$rowCnt]["status"];               
                array_push($groupingArray,$groupingArrayItems);
            }else{                
            }

            $rowCnt += 1;            

            //key 更新
            if($rowCnt < count($taskScheduleData)){
                $oldClientKey = $newClientKey;
                $newClientKey = $taskScheduleData[$rowCnt]["client_id"];

                $oldProjectKey = $newProjectKey;
                $newProjectKey = $taskScheduleData[$rowCnt]["project_id"];

                $oldPhaseKey = $newPhaseKey;
                $newPhaseKey = $taskScheduleData[$rowCnt]["phase_name"];
            }            

        } while($rowCnt < count($taskScheduleData));

        return $groupingArray;
    }

    function getGroupProjectData($taskScheduleData){
        $groupingArray = [];
        $rowCnt = 0;
        $oldClientKey = -1;
        $newClientKey = $taskScheduleData[0]["client_id"];

        $oldProjectKey = -1;
        $newProjectKey = $taskScheduleData[0]["project_id"];
       
        do {            
            if($oldClientKey != $newClientKey || $oldProjectKey != $newProjectKey){
                $groupingArrayItems = [];
                $groupingArrayItems["client_id"] = $taskScheduleData[$rowCnt]["client_id"];
                $groupingArrayItems["client_name"] = $taskScheduleData[$rowCnt]["client_name"];    
                $groupingArrayItems["project_id"] = $taskScheduleData[$rowCnt]["project_id"];   
                $groupingArrayItems["project_name"] = $taskScheduleData[$rowCnt]["project_name"];
                $groupingArrayItems["phase_name"] = "";                
                $groupingArrayItems["user"] = $taskScheduleData[$rowCnt]["user"];                
                $groupingArrayItems["description"] = "";               
                $groupingArrayItems["memo"] = "";               
                $groupingArrayItems["task"] = "";               
                $groupingArrayItems["fye"] = $taskScheduleData[$rowCnt]["fye"];               
                $groupingArrayItems["status"] = $taskScheduleData[$rowCnt]["status"];               
                array_push($groupingArray,$groupingArrayItems);
            }else{                
            }

            $rowCnt += 1;            

            //key 更新
            if($rowCnt < count($taskScheduleData)){
                $oldClientKey = $newClientKey;
                $newClientKey = $taskScheduleData[$rowCnt]["client_id"];

                $oldProjectKey = $newProjectKey;
                $newProjectKey = $taskScheduleData[$rowCnt]["project_id"];
            }            
       
        } while($rowCnt < count($taskScheduleData));

        return $groupingArray;
    }
    
    function isDateRange($dateFrom,$dateTo,$targetDate){
        $retVal = false;
        if($dateFrom != "blank" && $dateTo == "blank"){
            if($targetDate >= $dateFrom){
                $retVal = true;
            }
        }
        
        if($dateFrom == "blank" && $dateTo != "blank"){
            if($targetDate <= $dateTo){
                $retVal = true;
            }
        }
        
        if($dateFrom != "blank" && $dateTo != "blank"){
            if($targetDate >= $dateFrom && $targetDate <= $dateTo){
                $retVal = true;
            }
        }
        
        if($dateFrom == "blank" && $dateTo == "blank"){
             $retVal = true;
        }
        
        return $retVal;
    }

}

//=======================================================================
    
    