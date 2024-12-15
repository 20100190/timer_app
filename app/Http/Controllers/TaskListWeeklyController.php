<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validate;
use DB;
use App\Task;
use App\Client;
use App\Staff;
use App\Project;
use App\ProjectPhaseItem;
use App\ToDoList;
//=======================================================================
class TaskListWeeklyController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        //client
        $clientData = Client::orderBy("name", "asc")->get();

        //project
        $projectData = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();

        //pic
        $picData = Staff::ActiveStaffOrderByInitial(); //Staff::ActiveStaff();
        //staff
        $staffData = Staff::ActiveStaffOrderByInitial();

        //直近の日曜日
        $lastSunday = date("m/d/Y", strtotime("last sunday"));

        //Login User Initial
        $loginUserInitial = Staff::select("initial")->where([['email', '=', Auth::User()->email]])->first();



        return view("task_list_weekly")
                        ->with("client", $clientData)
                        ->with("project", $projectData)
                        ->with("pic", $picData)
                        ->with("staff", $staffData)
                        ->with("loginInitial", $loginUserInitial)
                        ->with("lastSunday", $lastSunday);
    }

    function getTaskScheduleData(Request $request) {

        ini_set('memory_limit', '256M');
        
        $status = $request->status;
        $dateFrom = $request->dateFrom;        
        //$project = $request->dateTo;
        $staff =  explode(",", $request->staff);
        
        $taskScheduleQuery = ProjectPhaseItem::select("client.id as client_id","project.id as project_id","due_date", "project phase item.name as task", "project phase item.description as description", "project_name", "client.name as client_name", "phase.name as phase_name","preparer","reviewer","reviewer2","planed_prep","planned_review","planned_review2","prep_sign_off","review_sign_off","review_sign_off2","prep.id as prep_user_id","rev1.id as rev1_user_id","rev2.id as rev2_user_id","prep.initial as prep_user","rev1.initial as review_user","rev2.initial as review2_user","col_memo","pic_info.initial as pic_user","pic_info.id as pic_user_id")                
                ->leftjoin("project", "project.id", "=", "project phase item.project_id")
                ->leftjoin("client", "project.client_id", "=", "client.id")
                ->leftjoin("phase group", "project phase item.phase_group_id", "=", "phase group.id")
                ->leftjoin("phase", "phase.id", "=", "phase group.phase_id")
                ->leftjoin("staff as prep", "prep.id", "=", "project phase item.preparer")
                ->leftjoin("staff as rev1", "rev1.id", "=", "project phase item.reviewer")
                ->leftjoin("staff as rev2", "rev2.id", "=", "project phase item.reviewer2")
                ->leftjoin("staff as pic_info", "pic_info.id", "=", "project.pic");
        
        if($request->client != "blank"){
            $taskScheduleQuery = $taskScheduleQuery->whereIn("client.id",explode(",", $request->client));
        }
        
        if($request->pic != "blank"){
            $taskScheduleQuery = $taskScheduleQuery->whereIn("project.pic",explode(",", $request->pic));
        }

        if($request->dateTo != "blank"){
            $taskScheduleQuery = $taskScheduleQuery->whereIn("project_name",explode(",", $request->dateTo));
        }
      
        
        $taskScheduleObj = $taskScheduleQuery->get();

        $dateFrom = str_replace("/","",$dateFrom);//"20210405"; 
        $dateTo1 = date("Ymd", strtotime($dateFrom . " 1 day"));
        $dateTo2 = date("Ymd", strtotime($dateTo1 . " 1 day"));
        $dateTo3 = date("Ymd", strtotime($dateTo2 . " 1 day"));
        $dateTo4 = date("Ymd", strtotime($dateTo3 . " 1 day"));
        $dateTo5 = date("Ymd", strtotime($dateTo4 . " 1 day"));
        $dateTo6 = date("Ymd", strtotime($dateTo5 . " 1 day"));            
        $dateTo = $dateTo6;

        //ymd
        $dateYmdArray = [];
        $dateYmdArray["1"] = $dateFrom;
        $dateYmdArray["2"] = $dateTo1;
        $dateYmdArray["3"] = $dateTo2;
        $dateYmdArray["4"] = $dateTo3;
        $dateYmdArray["5"] = $dateTo4;
        $dateYmdArray["6"] = $dateTo5;
        $dateYmdArray["7"] = $dateTo6;
        
        $headerWeek = [];            
        $headerWeek["1"] = $this->formatDateMDY($dateFrom);
        $headerWeek["2"] = $this->formatDateMDY($dateTo1);
        $headerWeek["3"] = $this->formatDateMDY($dateTo2);
        $headerWeek["4"] = $this->formatDateMDY($dateTo3);
        $headerWeek["5"] = $this->formatDateMDY($dateTo4);
        $headerWeek["6"] = $this->formatDateMDY($dateTo5);
        $headerWeek["7"] = $this->formatDateMDY($dateTo6);

        //week no
        $headerWeek["a"] = date("w", strtotime($dateFrom));
        $headerWeek["b"] = date("w", strtotime($dateTo1));
        $headerWeek["c"] = date("w", strtotime($dateTo2));
        $headerWeek["d"] = date("w", strtotime($dateTo3));
        $headerWeek["e"] = date("w", strtotime($dateTo4));
        $headerWeek["f"] = date("w", strtotime($dateTo5));
        $headerWeek["g"] = date("w", strtotime($dateTo6));

        /*
        $taskScheduleData = [];        
        foreach ($taskScheduleObj as $items) {   
            $taskScheduleDataItem = [];
            $dateymd = intval(str_replace("-", "", $items->due_date));            
            $dateFrom = str_replace("/","",$dateFrom);//"20210405"; 

            $dateTo1 = date("Ymd", strtotime($dateFrom . " 1 day"));
            $dateTo2 = date("Ymd", strtotime($dateTo1 . " 1 day"));
            $dateTo3 = date("Ymd", strtotime($dateTo2 . " 1 day"));
            $dateTo4 = date("Ymd", strtotime($dateTo3 . " 1 day"));
            $dateTo5 = date("Ymd", strtotime($dateTo4 . " 1 day"));
            $dateTo6 = date("Ymd", strtotime($dateTo5 . " 1 day"));            
            $dateTo = $dateTo6;

            $headerWeek = [];            
            $headerWeek["1"] = $this->formatDateMDY($dateFrom);
            $headerWeek["2"] = $this->formatDateMDY($dateTo1);
            $headerWeek["3"] = $this->formatDateMDY($dateTo2);
            $headerWeek["4"] = $this->formatDateMDY($dateTo3);
            $headerWeek["5"] = $this->formatDateMDY($dateTo4);
            $headerWeek["6"] = $this->formatDateMDY($dateTo5);
            $headerWeek["7"] = $this->formatDateMDY($dateTo6);
                        
            if ($this->isDateRange($dateFrom, $dateTo, $dateymd)) {
                if ($request->staff == "blank" || in_array($items->rev1_user_id, $staff)) {
                    $taskScheduleDataItem["user"] = $items->review_user;
                    $taskScheduleDataItem["due_date"] = $items->due_date;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;
                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateFrom == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateTo1 == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateTo2 == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateTo3 == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateTo4 == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateTo5 == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateTo6 == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }
                    
                }
            }
                
            if(isset($taskScheduleDataItem["user"])){
                array_push($taskScheduleData,$taskScheduleDataItem);
            }
        }*/        

        //due date mode
        if($status == "Due"){
            $taskScheduleData = $this->dueModeData($taskScheduleObj,$dateYmdArray,$request->staff,$request->compStatus);
        }
        
        //prep date mode
        if($status == "Prep"){
            $taskScheduleData = $this->prepModeData($taskScheduleObj,$dateYmdArray,$request->staff,$request->compStatus);
        }

        //sign off date mode
        if($status == "SignOff"){
            $taskScheduleData = $this->signOffModeData($taskScheduleObj,$dateYmdArray,$request->staff,$request->compStatus);
        }

        //to do listを追加
        $todoListQuery = ToDoList::select("to_do_list.preparer_id as preparer_id","to_do_list.id as id","to_do_list.end_time as end_time","to_do_list.client_id","to_do_list.project_id","to_do_list.project_task_id as task","to_do_list.memo as memo","project.project_name as project_name","to_do_list.requestor_id","D.initial as staff_name","client.name as client_name","progress")
                        ->leftjoin("project", "to_do_list.project_id", "=", "project.id")
                        ->leftjoin("client", "client.id", "=", "to_do_list.client_id")
                        ->leftjoin("staff as B", "B.id", "=", "project.pic")                        
                        ->leftjoin("assign as C", [["C.project_id", "=", "to_do_list.project_id"],["C.staff_id","=","to_do_list.requestor_id"]])
                        ->leftjoin("staff as D", "D.id", "=", "to_do_list.requestor_id");                        
                        //->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") BETWEEN "' . $startDateAll . '" and "' . $endDateAll . '"');  

        if($request->client != "blank"){
            $todoListQuery = $todoListQuery->whereIn("client.id",explode(",", $request->client));
        }
        
        if($request->pic != "blank"){
            $todoListQuery = $todoListQuery->whereIn("project.pic",explode(",", $request->pic));
        }

        if($request->dateTo != "blank"){
            $todoListQuery = $todoListQuery->whereIn("project_name",explode(",", $request->dateTo));
        }

        $todoListObj = $todoListQuery->get();

        $taskScheduleData = $this->toDoListData($taskScheduleData,$dateYmdArray,$request->staff,$request->compStatus,$todoListObj);
        
        $json = [
            "taskSchedule" => $taskScheduleData,
            "headerWeek" => $headerWeek,
        ];

        return response()->json($json);
    }


    function toDoListData($taskScheduleData,$dateYmdArray,$reqStaff,$reqCompStatus,$todoListObj){
        
        //$taskScheduleData = [];    

        $staff =  explode(",", $reqStaff);        
        
        foreach ($todoListObj as $items) {   
            $taskScheduleDataItem = [];

            $preparerIdArray = explode(",",$items->preparer_id);
            
            for($i=0; $i<count($preparerIdArray); $i++){
                $endTime = explode("/",substr($items->end_time,0,10));            
                $dateymd = intval($endTime[2] . $endTime[0] . $endTime[1]);
                if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                    //staffが検索条件にある場合、除外
                    if($reqStaff != "blank" && !in_array($preparerIdArray[$i], $staff)){
                        continue;
                    }

                    $endTimeYmdArray = explode("/",substr($items->end_time,0,10));                                        
                    $targetDate = $endTimeYmdArray[2] . "-" . $endTimeYmdArray[0] . "-" . $endTimeYmdArray[1];

                    $userData = Staff::where([["id","=",$preparerIdArray[$i]]])->first();

                    $taskScheduleDataItem["user"] = $userData->initial;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = "To Do List";
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->id;
                    $taskScheduleDataItem["memo"] = $items->memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";
                    //review2 complete
                    if($items->progress == 100){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }

                }
            }
         
/*            
            $endTime = explode("/",substr($items->end_time,0,10));            
            $dateymd = intval($endTime[2] . $endTime[0] . $endTime[1]);
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->requestor_id, $staff)) {
                    
                    $endTimeYmdArray = explode("/",substr($items->end_time,0,10));                                        
                    $targetDate = $endTimeYmdArray[2] . "-" . $endTimeYmdArray[0] . "-" . $endTimeYmdArray[1];

                    $taskScheduleDataItem["user"] = $items->staff_name;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = "To Do List";
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->id;
                    $taskScheduleDataItem["memo"] = $items->memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";
                    //review2 complete
                    if($items->progress == 100){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                }
            }*/

        }

        return $taskScheduleData;
    }



    function prepModeData($taskScheduleObj,$dateYmdArray,$reqStaff,$reqCompStatus){
        $taskScheduleData = [];    

        $staff =  explode(",", $reqStaff);
        
        foreach ($taskScheduleObj as $items) {   
            $taskScheduleDataItem = [];
                     
            //planed prep
            $dateymd = intval(str_replace("-", "", $items->planed_prep));  
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->prep_user_id, $staff)) {

                    $targetDate = $items->planed_prep;

                    $taskScheduleDataItem["user"] = $items->prep_user;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";                    
                    //prep complete                    
                    if($items->prep_sign_off != null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                }
            }

            //planned review
            $dateymd = intval(str_replace("-", "", $items->planned_review));  
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->rev1_user_id, $staff)) {

                    $targetDate = $items->planned_review;

                    $taskScheduleDataItem["user"] = $items->review_user;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";
                    //review1 complete
                    if($items->review_sign_off != null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                }
            }

            //planned review2
            $dateymd = intval(str_replace("-", "", $items->planned_review2));  
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->rev2_user_id, $staff)) {

                    $targetDate = $items->planned_review2;

                    $taskScheduleDataItem["user"] = $items->review2_user;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";
                    //review2 complete
                    if($items->review_sign_off2 != null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                }
            }

        }

        return $taskScheduleData;
    }

    function dueModeData($taskScheduleObj,$dateYmdArray,$reqStaff,$reqCompStatus){
        $taskScheduleData = [];    

        $staff =  explode(",", $reqStaff);
        
        foreach ($taskScheduleObj as $items) {   
            $taskScheduleDataItem = [];
            $dateymd = intval(str_replace("-", "", $items->due_date));  
         
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->pic_user_id, $staff)) {
                    $taskScheduleDataItem["user"] = $items->pic_user;
                    $taskScheduleDataItem["due_date"] = $items->due_date;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";

                    $taskScheduleDataItem["prep_sign_off"] = $items->prep_sign_off;
                    $taskScheduleDataItem["review_user"] = $items->review_user;
                    $taskScheduleDataItem["review_sign_off"] = $items->review_sign_off;
                    $taskScheduleDataItem["review2_user"] = $items->review2_user;
                    $taskScheduleDataItem["review_sign_off2"] = $items->review_sign_off2;
                    
                    //prep complete                    
                    if($items->prep_sign_off != null && $items->review_user == null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }
                    //review1 complete
                    if($items->prep_sign_off != null && $items->review_sign_off != null && $items->review2_user == null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }
                    //review2 complete
                    if($items->prep_sign_off != null && $items->review_sign_off != null && $items->review_sign_off2 != null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$items->due_date)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                    
                }
            }
        }

        return $taskScheduleData;
    }

    function SignOffModeData($taskScheduleObj,$dateYmdArray,$reqStaff,$reqCompStatus){
        $taskScheduleData = [];    

        $staff =  explode(",", $reqStaff);
        
        foreach ($taskScheduleObj as $items) {   
            $taskScheduleDataItem = [];
                     
            //sign off prep
            $dateymd = intval(str_replace("-", "", $items->prep_sign_off));  
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->prep_user_id, $staff)) {

                    $targetDate = $items->prep_sign_off;

                    $taskScheduleDataItem["user"] = $items->prep_user;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";                    
                    //prep complete                    
                    if($items->prep_sign_off != null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                }
            }

            //planned review
            $dateymd = intval(str_replace("-", "", $items->review_sign_off));  
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->rev1_user_id, $staff)) {

                    $targetDate = $items->review_sign_off;

                    $taskScheduleDataItem["user"] = $items->review_user;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";
                    //review1 complete
                    if($items->review_sign_off != null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                }
            }

            //planned review2
            $dateymd = intval(str_replace("-", "", $items->review_sign_off2));  
            if ($this->isDateRange($dateYmdArray["1"], $dateYmdArray["7"], $dateymd)) {
                if ($reqStaff == "blank" || in_array($items->rev2_user_id, $staff)) {

                    $targetDate = $items->review_sign_off2;

                    $taskScheduleDataItem["user"] = $items->review2_user;
                    $taskScheduleDataItem["due_date"] = $targetDate;
                    $taskScheduleDataItem["client_id"] = $items->client_id;
                    $taskScheduleDataItem["client_name"] = $items->client_name;
                    $taskScheduleDataItem["project_id"] = $items->project_id;
                    $taskScheduleDataItem["project_name"] = $items->project_name;
                    $taskScheduleDataItem["phase_name"] = $items->phase_name;
                    $taskScheduleDataItem["task"] = $items->task;
                    $taskScheduleDataItem["description"] = $items->description;
                    $taskScheduleDataItem["memo"] = $items->col_memo;

                    //status
                    //imcomplete
                    $taskScheduleDataItem["status"] = "Imcomplete";
                    //review2 complete
                    if($items->review_sign_off2 != null){
                        $taskScheduleDataItem["status"] = "Completed";
                    }

                    //sunからsatの度のカラムに表示するか指定
                    //1: sun, 2: mon, 3: tue, 4: wed, 5: thu, 6: fri, 7: sat
                    if($dateYmdArray["1"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "1";
                    }

                    if($dateYmdArray["2"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "2";
                    }

                    if($dateYmdArray["3"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "3";
                    }

                    if($dateYmdArray["4"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "4";
                    }

                    if($dateYmdArray["5"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "5";
                    }

                    if($dateYmdArray["6"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "6";
                    }

                    if($dateYmdArray["7"] == str_replace("-","",$targetDate)){
                        $taskScheduleDataItem["col_no"] = "7";
                    }

                    if(isset($taskScheduleDataItem["user"]) && ($reqCompStatus == "blank" || $reqCompStatus == $taskScheduleDataItem["status"])){
                        array_push($taskScheduleData,$taskScheduleDataItem);
                    }
                }
            }

        }

        return $taskScheduleData;
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

    private function formatDateMDY($yyyymmdd){
        $year = substr($yyyymmdd,0,4);
        $month = substr($yyyymmdd,4,2);
        $day = substr($yyyymmdd,6,2);

        return $month . "/" . $day . "/" . $year;

    }

}

//=======================================================================
    
    