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
        
        $status = $request->status;
        $dateFrom = $request->dateFrom;
        $dateTo = $request->dateTo;
        $staff =  explode(",", $request->staff);
        
        $taskScheduleQuery = ProjectPhaseItem::select("client.id as client_id","project.id as project_id","due_date", "phase items.name as task", "phase items.description as description", "project_name", "client.name as client_name", "phase.name as phase_name","preparer","reviewer","reviewer2","planed_prep","planned_review","planned_review2","prep_sign_off","review_sign_off","review_sign_off2","prep.id as prep_user_id","rev1.id as rev1_user_id","rev2.id as rev2_user_id","prep.initial as prep_user","rev1.initial as review_user","rev2.initial as review2_user")
                ->leftjoin("phase items", "phase items.id", "=", "project phase item.phase_item_id")
                ->leftJoin("project", "project.id", "=", "project phase item.project_id")
                ->leftJoin("client", "project.client_id", "=", "client.id")
                ->leftJoin("phase group", "phase items.phase_group_id", "=", "phase group.id")
                ->leftJoin("phase", "phase.id", "=", "phase group.phase_id")
                ->leftJoin("staff as prep", "prep.id", "=", "project phase item.preparer")
                ->leftJoin("staff as rev1", "rev1.id", "=", "project phase item.reviewer")
                ->leftJoin("staff as rev2", "rev2.id", "=", "project phase item.reviewer2");
        
        if($request->client != "blank"){
            $taskScheduleQuery = $taskScheduleQuery->whereIn("client.id",explode(",", $request->client));
        }
        
        if($request->pic != "blank"){
            $taskScheduleQuery = $taskScheduleQuery->whereIn("project.pic",explode(",", $request->pic));
        }
      
        
        $taskScheduleObj = $taskScheduleQuery->get();
        $taskScheduleData = [];
        
        foreach ($taskScheduleObj as $items) {    
           
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
                        $taskScheduleDataItem["status"] = "Imcomplete";
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
                        $taskScheduleDataItem["status"] = "Imcomplete";
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
                        $taskScheduleDataItem["status"] = "Imcomplete";
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
                        $taskScheduleDataItem["status"] = "Complete";
                    }
                }
            }


            if(isset($taskScheduleDataItem["user"])){
                array_push($taskScheduleData,$taskScheduleDataItem);
            }
            
        }
        
        $json = [
            "taskSchedule" => $taskScheduleData,
        ];

        return response()->json($json);
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
    
    