<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Client;
use App\Project;
use App\Staff;
use App\Task;
use App\Assign;
use App\ProjectTask;
use App\ProjectType;
use App\Week;
use App\RoleOrder;
use App\Phase;
use App\ProjectPhase;
use Illuminate\Support\Facades\DB;


class PhaseEntryController extends Controller
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
    
    public function initArray() {
        $data = [];
        for ($s = 0; $s < 65; $s++) {
            $data[$s] = "";
        }

        return $data;
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
        
        //project
        $projectData = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();
        //staff
        $staffData = Staff::ActiveStaffOrderByInitial();
        //pic
        $picData = Staff::ActiveStaffOrderByInitial();
        
        //role
        $roleData = RoleOrder::GetRoleData();
        
        return view('phase_entry')
                        ->with("client", $clientData)
                        ->with("project", $projectData)
                        ->with("staff", $staffData)
                        ->with("role", $roleData)
                        ->with("pic", $picData);
    }
    
    public function storeInput(Request $request) {  
        $BudgetController = new BudgetController;
        
        //出力対象期間
        $requestYear = $request->year;
        $requestMonth = $request->month;
        $requestDay = $request->day;
        $weekArray = $this->getWeek($requestYear, $requestMonth, $requestDay);
        $startDate = $weekArray[0]["year"] . sprintf('%02d', $weekArray[0]["month"]) . sprintf('%02d', $weekArray[0]["day"]);
        $endDate = $weekArray[51]["year"] . sprintf('%02d', $weekArray[51]["month"]) . sprintf('%02d', $weekArray[51]["day"]);
        
        $colWeek = 4;
        
        //$comments = Project::select("client.name as client", "project.project_name as project", "assign.role as role", "staff.initial as initial", "client.fye", "client.vic_status", "B.initial as pic","assign.budget_hour","role_order.order")
        $comments = Project::select("client.name as client", "project.project_name as project","project.id as project_id", "B.initial as pic")
                ->join("client", "project.client_id", "=", "client.id")
                ->join("assign", "assign.project_id", "=", "project.id")
                ->leftjoin("staff", "staff.id", "=", "assign.staff_id")
                ->leftjoin("staff as B", "B.id", "=", "project.pic")
                ->leftjoin("role_order", "role_order.role", "=", "assign.role");
        
        if ($request->client != "blank") {
            $comments = $comments
                    ->wherein('client.id', explode(",", $request->client));
        }
        
        if ($request->project != "blank") {
            $comments = $comments
                    ->wherein('project.project_name', explode(",", $request->project));
        }
        
        if ($request->vic != "blank") {
            $vic = "";
            $vicArray = explode(",", $request->vic);
            $vicFilter = [];
            for ($i = 0; $i < count($vicArray); $i++) {
                if ($vicArray[$i] == 1) {
                    array_push($vicFilter, "VIC");
                }
                if ($vicArray[$i] == 2) {
                    array_push($vicFilter, "IC");
                }
                if ($vicArray[$i] == 3) {
                    array_push($vicFilter, "C");
                }
            }

            $comments = $comments
                    ->wherein('client.vic_status', $vicFilter);
        }

        if ($request->pic != "blank") {
            $picArray = explode(",", $request->pic);

            $comments = $comments
                    ->wherein('project.pic', $picArray);
        }

        if ($request->staff != "blank") {
            $staffArray = explode(",", $request->staff);

            $comments = $comments
                    ->wherein('assign.staff_id', $staffArray);
        }

        if ($request->role != "blank") {            
            $role = "";
            $roleArray = explode(",", $request->role);
            $comments = $comments
                    ->wherein('role_order.id', $roleArray);           
        }
        
        $comments = $comments
                ->orderBy("client", "asc")
                ->orderBy("project", "asc")
                ->groupBy("client","project","project.id","pic")
                ->get();
        
        $res = [];
        $resColor = [];
        foreach ($comments as $xxx) {
            $data = $this->initArray();
            $data2 = $this->initArray();
            
            $data[0] = $xxx->project_id;
            $data[1] = $xxx->client;
            $data[2] = $xxx->project;
            $data[3] = $xxx->pic;
            
            $data2[0] = $xxx->project_id;
            $data2[1] = $xxx->client;
            $data2[2] = $xxx->project;
            $data2[3] = $xxx->pic;
            
            //phase string                        
            $phaseObj = ProjectPhase::select("year","month","day","name","color")
                    ->leftjoin("phase","phase.id","=","project phase.phase_id")
                    ->where([["project_id","=",$xxx->project_id],['project phase.ymd', '<=', $endDate], ['project phase.ymd', '>=', $startDate]])->get();   
            foreach($phaseObj as $y){
                if($data[$colWeek - 1 + $BudgetController->getWeekNo($weekArray, $y->year, $y->month, $y->day)] != ""){
                    $data[$colWeek - 1 + $BudgetController->getWeekNo($weekArray, $y->year, $y->month, $y->day)] .= ";";   
                    $data2[$colWeek - 1 + $BudgetController->getWeekNo($weekArray, $y->year, $y->month, $y->day)] .= ";";   
                }
                $data[$colWeek - 1 + $BudgetController->getWeekNo($weekArray, $y->year, $y->month, $y->day)] .= $y->name;
                $data2[$colWeek - 1 + $BudgetController->getWeekNo($weekArray, $y->year, $y->month, $y->day)] .= $y->color;
            }
            array_push($res, $data);
            array_push($resColor, $data2);
        }
       
        $week = $this->getWeek($requestYear, $requestMonth, $requestDay);
        $weekArray = [];
        foreach ($week as $xxx) {            
            array_push($weekArray, $xxx["month"] . "/" . $xxx["day"] . "/" . $xxx["year"]);
        }
        
        //phase
        $phaseCtrStr = "";
        $phaseCTR = Phase::select("name","color")->where([["project_type","=","9"]])->orderBy("order")->get();
        foreach($phaseCTR as $x){
            $phaseCtrStr .= $x["name"] . ",";                        
        }
        $phaseCtrStr = substr($phaseCtrStr, 0, -1);

        $json = ["budget" => $res, "week" => $weekArray,"phaseCTR" => $phaseCtrStr,"color" => $resColor,"phaseCTRColor" => $phaseCTR];
        
        return response()->json($json);
    }
    
    public function save(Request $request) {
        $value = $request->value;
        $projectId = $request->projectId;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;
        //project idとyear,month,dayでdelete insert      
        //delete
        $phaseIdObj = ProjectPhase::where([["project_id","=",$projectId],["year","=",$year],["month","=",$month],["day","=",$day]])->delete();
        
        if($value == "blank"){
            return;
        }
      
        //insert
        $valueArray = explode(";",$value);
        $valueCnt = count($valueArray);
        for($i=0; $i<$valueCnt; $i++){            
            //phase id
            $phaseId = 0;
            $phaseIdObj = Phase::select("id")->where([["name","=",$valueArray[$i]]])->first();
            $phaseId = $phaseIdObj["id"];
            
            $table = new ProjectPhase;
            $table->project_id = $projectId;
            $table->phase_id = $phaseId;
            $table->year = $request->year;
            $table->month = $request->month;
            $table->day = $request->day;        
            $table->ymd = $request->year . sprintf('%02d', $request->month) . sprintf('%02d', $request->day);
            
            $table->save();
        }

    }
    
    public function getWeek($year, $month, $day) {

        $week = Week::orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->orderBy('day', 'asc')
                ->get();

        $offset = 0;
        $requestYmd = $year . sprintf('%02d', $month) . sprintf('%02d', $day);
        foreach ($week as $s) {
            $ymd = $s->year . sprintf('%02d', $s->month) . sprintf('%02d', $s->day);
            if ($requestYmd < $ymd) {
                //var_dump($offset);
                break;
            }
            $offset += 1;
        }

        $retWeek = Week::orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->orderBy('day', 'asc')
                ->limit(52)
                ->offset($offset - 1)
                ->get();

        return $retWeek;
    }
    
}
