<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Project;
use App\Assign;
use App\Budget;
use App\Staff;
use App\Week;
use App\RoleOrder;
use App\ProjectPhase;
use App\ProjectPhaseItem;
use App\ToDoList;
use Illuminate\Support\Facades\DB;
use Auth;

class BudgetController extends Controller
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

    function indexInput() {
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
        
        //Login User Initial
        $loginUserInitial = Staff::select("initial")->where([['email', '=', Auth::User()->email]])->first();
                 
        return view('budget_input')
                        ->with("client", $clientData)
                        ->with("project", $projectData)
                        ->with("staff", $staffData)
                        ->with("pic", $picData)
                        ->with("role", $roleData)
                        ->with("loginInitial", $loginUserInitial);
    }
    
    function indexShow() {
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

        //staff cnt
        $staffCnt = Staff::ActiveStaffCount();
        
        //role
        $roleData = RoleOrder::GetRoleData();
        
        return view('budget_show')
                ->with("client", $clientData)
                ->with("project", $projectData)
                ->with("staff", $staffData)
                ->with("role", $roleData)
                ->with("pic", $picData)
                ->with("staff_cnt", $staffCnt);
    }
    
    public function storeInput(Request $request) {

        //出力対象期間
        $requestYear = $request->year;
        $requestMonth = $request->month;
        $requestDay = $request->day;
        $weekArray = $this->getWeek($requestYear, $requestMonth, $requestDay);
        $startDate = $weekArray[0]["year"] . sprintf('%02d', $weekArray[0]["month"]) . sprintf('%02d', $weekArray[0]["day"]);
        $endDate = $weekArray[51]["year"] . sprintf('%02d', $weekArray[51]["month"]) . sprintf('%02d', $weekArray[51]["day"]);


        //row setting
        $colClient = 0;
        $colProject = 1;
        $colFye = 2;
        $colVic = 3;
        $colPic = 4;
        $colRole = 5;
        $colAssign = 6;
        $colBudget = 7;
        $colAssignedHours = 8;
        $colDiff = 9;
        $colWeek = 10;

        $columnArray = $this->columnArray();


        $data = $this->initArray();
        $data[$colBudget] = "0";
        $data[$colAssignedHours] = "=SUM(K1:BH1)";
        $data[$colDiff] = "=H1-I1";

        $res = [];
        array_push($res, $data);
      
        //project取得
        $comments = Project::select("client.name as client", "project.project_name as project", "assign.role as role", "staff.initial as initial", "client.fye", "client.vic_status", "B.initial as pic","assign.budget_hour","role_order.order")
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

            $comments = $comments
                    ->wherein(DB::raw('Substring(client.fye,1,2)'), $fyeFilter);
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
        
        //Adminアカウント以外の条件
        $loginUserInitialObj = Staff::select("initial")->where([['email', '=', Auth::User()->email]])->first();
        $loginUserInitial = "";        
        if (!is_null($loginUserInitialObj)) {
            $loginUserInitial = $loginUserInitialObj["initial"];
        }
        $requestPIC = "";
        if(isset($picArray)){            
            $requestPICObj = Staff::select("initial")->where([['id', '=',$picArray[0]]])->first();
            if(!is_null($requestPICObj)){
                $requestPIC = $requestPICObj["initial"];
            }
        }
        
        if($request->clientAS == "true"){
            $comments = $comments->where([["client.is_archive","<>",1]]);
        }
        
        if($request->projectAS == "true"){
            $comments = $comments->where([["project.is_archive","<>",1]]);
        }
        
        if($request->picAS == "true"){
            $comments = $comments->where([["B.status","=","Active"]]);
        }
        
        if($request->staffAS == "true"){
            $comments = $comments->where([["staff.status","=","Active"]]);
        }
        
        if ($loginUserInitial != "" && $loginUserInitial == $requestPIC && ($request->client == "blank" || in_array("0",explode(",", $request->client)))) {
            //clientがブランクまたはTOPCが指定されていれば
            //$comments = $comments
            //        ->orWhere('project.client_id', "=", 0);
            $comments = $comments->orwhere(function($query) use($request) {
                if ($request->staff != "blank") {
                    $staffArray = explode(",", $request->staff);
                    $query->where(function($query) use($staffArray) {
                        $query->wherein('assign.staff_id', $staffArray);
                    });
                }

                $query->where(function($query) {
                    $query->Where('project.client_id', "=", 0);
                });
                
                if ($request->staffAS == "true") {
                    $query->where([["staff.status", "=", "Active"]]);
                }

                if($request->clientAS == "true"){
                    $query->where([["client.is_archive","<>",1]]);
                }

                if($request->projectAS == "true"){
                    $query->where([["project.is_archive","<>",1]]);
                }

                if ($request->project != "blank") {
                    $query->wherein('project.project_name', explode(",", $request->project));
                }

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
        
                    $query->wherein(DB::raw('Substring(client.fye,1,2)'), $fyeFilter);
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
        
                    $query->wherein('client.vic_status', $vicFilter);
                }
            });
        }
           
        $comments = $comments
                ->orderBy("client", "asc")
                ->orderBy("project", "asc")
                ->orderBy("order", "asc")
                ->orderBy("initial", "asc")                
                ->get();
                
        
        $index = 2;

        $oldClient = "";
        $oldProject = "";
        $oldRole = "";
        $oldAssign = "";
        $newClient = "";
        $newProject = "";
        $newRole = "";
        $newAssign = "";

        foreach ($comments as $xxx) {
            $data = $this->initArray();

            //検索条件に該当するbudget data 取得            
            $budgetDetail = Assign::select("client.name as client_id", "project.project_name as project_id", "assign.role as role_id", "staff.initial", "budget.year", "budget.month", "budget.day", "budget.working_days as working_days")//,"B.initial as pic")
                    ->leftjoin("project", "assign.project_id", "=", "project.id")
                    ->leftjoin("client", "client.id", "=", "project.client_id")
                    ->leftjoin("staff", "staff.id", "=", "assign.staff_id")
                    ->leftjoin("budget", "budget.assign_id", "=", "assign.id")
                    //->leftjoin("A_staff as B", "B.id", "=", "A_client.pic")
                    ->where([['client.name', '=', $xxx->client], ['project.project_name', '=', $xxx->project], ['assign.role', '=', $xxx->role], ['staff.initial', '=', $xxx->initial], ['budget.ymd', '<=', $endDate], ['budget.ymd', '>=', $startDate]])                                               
                    ->get();
         
            //プロジェクト単位の行数取得                        
            $detailRowCnt = Assign::select()
                    ->leftjoin("project", "assign.project_id", "=", "project.id")
                    ->leftjoin("client", "client.id", "=", "project.client_id")
                    ->leftjoin("role_order", "role_order.role", "=", "assign.role")
                    ->where([['client.name', '=', $xxx->client], ['project.project_name', '=', $xxx->project]]);
            if ($request->staff != "blank") {
                $staffArray = explode(",", $request->staff);

                $detailRowCnt = $detailRowCnt
                        ->wherein('staff_id', $staffArray);
            }
            
            if ($request->role != "blank") {
                $role = "";
                $roleArray = explode(",", $request->role);
                $detailRowCnt = $detailRowCnt
                    ->wherein('role_order.id', $roleArray);                
                
            }

            $detailRowCnt = $detailRowCnt->count();
            
            
            //Assignが存在しない場合は、集計の計算式がズレてしまうため
            if ($detailRowCnt == 0) {
                $detailRowCnt = 1;
            }

            if ($oldClient == "") {
                $oldClient = $xxx->client;
                $oldProject = $xxx->project;

                $data1 = $this->initArray();
                $data1[$colClient] = $xxx->client;
                $data1[$colProject] = $xxx->project . " Total";
                $data1[$colBudget] = "=SUM(H" . ($index + 1) . ":H" . ($index + $detailRowCnt) . ")";
                $data1[$colAssignedHours] = "=SUM(K" . $index . ":BJ" . $index . ")";
                $data1[$colDiff] = "=H" . $index . "-I" . $index;
                for ($i = $colWeek; $i < count($columnArray); $i++) {
                    $data1[$i] = "=SUM(" . $columnArray[$i] . ($index + 1) . ":" . $columnArray[$i] . ($index + $detailRowCnt) . ")";
                }
                array_push($res, $data1);

                for ($i = $colWeek; $i < count($columnArray); $i++) {
                    if ($res[0][$i] == "") {
                        $res[0][$i] .= "=";
                    } else {
                        $res[0][$i] .= "+";
                    }
                    $res[0][$i] .= "SUM(" . $columnArray[$i] . ($index + 1) . ":" . $columnArray[$i] . ($index + $detailRowCnt) . ")";
                }

                $index += 1;
            }

            $newClient = $xxx->client;
            $newProject = $xxx->project;

            if ($oldClient != $newClient || $oldProject != $newProject) {
                $data1 = $this->initArray();
                $data1[$colClient] = $xxx->client;
                $data1[$colProject] = $xxx->project . " Total";
                $data1[$colBudget] = "=SUM(H" . ($index + 1) . ":H" . ($index + $detailRowCnt) . ")";
                $data1[$colAssignedHours] = "=SUM(K" . $index . ":BJ" . $index . ")";
                $data1[$colDiff] = "=H" . $index . "-I" . $index;
                for ($i = $colWeek; $i < count($columnArray); $i++) {
                    $data1[$i] = "=SUM(" . $columnArray[$i] . ($index + 1) . ":" . $columnArray[$i] . ($index + $detailRowCnt) . ")";
                }
                array_push($res, $data1);

                for ($i = $colWeek; $i < count($columnArray); $i++) {
                    if ($res[0][$i] == "") {
                        $res[0][$i] .= "=";
                    } else {
                        $res[0][$i] .= "+";
                    }
                    $res[0][$i] .= "SUM(" . $columnArray[$i] . ($index + 1) . ":" . $columnArray[$i] . ($index + $detailRowCnt) . ")";
                }

                $index += 1;
            }

            $data[$colClient] = $xxx->client;
            $data[$colProject] = $xxx->project;
            $data[$colFye] = $xxx->fye;
            $data[$colVic] = $xxx->vic_status;
            $data[$colPic] = $xxx->pic;
            $data[$colRole] = $xxx->role;
            $data[$colAssign] = $xxx->initial;
            $data[$colBudget] = $xxx->budget_hour;
            $data[$colAssignedHours] = "=SUM(K" . $index . ":BJ" . $index . ")";
            $data[$colDiff] = "=H" . $index . "-I" . $index;

            foreach ($budgetDetail as $yyy) {
                $data[$colWeek - 1 + $this->getWeekNo($weekArray, $yyy->year, $yyy->month, $yyy->day)] = $yyy->working_days;
            }

            array_push($res, $data);
            $index += 1;

            $oldClient = $newClient;
            $oldProject = $newProject;
            $oldRole = $newRole;
            $oldAssign = $newAssign;
        }
        
        //TotalBudget書き換え(K列の計算式を書き換え)
        $totalFormula = $res[0][10];
        $totalBudgetFormula = str_replace("K", "H", $totalFormula);
        $res[0][$colBudget] = $totalBudgetFormula;

        //date
        /* $week = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', "2020"]])->get();
          $weekArray = [];
          foreach ($week as $xxx) {
          array_push($weekArray, $xxx["month"] . "/" . $xxx["day"]);
          } */
        $week = $this->getWeek($requestYear, $requestMonth, $requestDay);
        $weekArray = [];
        foreach ($week as $xxx) {
            //array_push($weekArray, $xxx["year"] . "/" . $xxx["month"] . "/" . $xxx["day"]);
            array_push($weekArray, $xxx["month"] . "/" . $xxx["day"] . "/" . $xxx["year"]);
        }
        
        $json = ["budget" => $res, "week" => $weekArray];

        return response()->json($json);
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
    
    public function columnArray() {
        $array = [];
        $array[0] = "A";
        $array[1] = "B";
        $array[2] = "C";
        $array[3] = "D";
        $array[4] = "E";
        $array[5] = "F";
        $array[6] = "G";
        $array[7] = "H";
        $array[8] = "I";
        $array[9] = "J";
        $array[10] = "K";
        $array[11] = "L";
        $array[12] = "M";
        $array[13] = "N";
        $array[14] = "O";
        $array[15] = "P";
        $array[16] = "Q";
        $array[17] = "R";
        $array[18] = "S";
        $array[19] = "T";
        $array[20] = "U";
        $array[21] = "V";
        $array[22] = "W";
        $array[23] = "X";
        $array[24] = "Y";
        $array[25] = "Z";
        $array[26] = "AA";
        $array[27] = "AB";
        $array[28] = "AC";
        $array[29] = "AD";
        $array[30] = "AE";
        $array[31] = "AF";
        $array[32] = "AG";
        $array[33] = "AH";
        $array[34] = "AI";
        $array[35] = "AJ";
        $array[36] = "AK";
        $array[37] = "AL";
        $array[38] = "AM";
        $array[39] = "AN";
        $array[40] = "AO";
        $array[41] = "AP";
        $array[42] = "AQ";
        $array[43] = "AR";
        $array[44] = "AS";
        $array[45] = "AT";
        $array[46] = "AU";
        $array[47] = "AV";
        $array[48] = "AW";
        $array[49] = "AX";
        $array[50] = "AY";
        $array[51] = "AZ";
        $array[52] = "BA";
        $array[53] = "BB";
        $array[54] = "BC";
        $array[55] = "BD";
        $array[56] = "BE";
        $array[57] = "BF";
        $array[58] = "BG";
        $array[59] = "BH";
        $array[60] = "BI";
        $array[61] = "BJ";

        return $array;
    }
    
    public function initArray() {
        $data = [];
        for ($s = 0; $s < 66; $s++) {
            $data[$s] = "";
        }

        return $data;
    }
    
    public function save(Request $request) {
        //Staff idを取得
        $staffObj = Staff::where([['initial', '=', $request->staff]])->get();
        $staffId = "";
        foreach ($staffObj as $stf) {
            $staffId = $stf["id"];
        }

        //project idを取得
        $projectObj = Project::select("project.id as p_id")
                ->leftjoin("client", "client.id", "=", "project.client_id")
                ->where([['client.name', '=', $request->client], ["project.project_name", "=", $request->project]])
                ->get();
        $projectId = "";
        foreach ($projectObj as $prj) {
            $projectId = $prj["p_id"];
        }

        //project idとstaff idからassign_idを取得
        $assignObj = Assign::where([['project_id', '=', $projectId], ["staff_id", "=", $staffId]])->get();
        $assignId = "";
        foreach ($assignObj as $assign) {
            $assignId = $assign["id"];
        }

        //assign idとyear,month,dayをキーにbudgetのworking_daysをupdate
        $budgetObj = Budget::where([['assign_id', '=', $assignId], ["year", "=", $request->year], ["month", "=", $request->month], ["day", "=", $request->day]]);
        $isExistBudget = $budgetObj->exists();

        if (!$isExistBudget) {
            $budgetTable = new Budget;
            $budgetTable->assign_id = $assignId;
            $budgetTable->year = $request->year;
            $budgetTable->month = $request->month;
            $budgetTable->day = $request->day;
            $budgetTable->working_days = str_replace(",","",$request->value);           
            $budgetTable->ymd = $request->year . sprintf('%02d', $request->month) . sprintf('%02d', $request->day);
            $budgetTable->save();
        } else {
            $budgetObj->update([
                "working_days" => $request->value,
            ]);
        }


        //ボタンで一気に変更する場合
        /* $reqArray = $request->json()->all();
          //$reqArray = json_decode($request->postArray);
          foreach ($reqArray as $req) {
          $client = $req[0];
          $project = $req[1];
          $role = $req[2];
          $assign = $req[3];

          if ($project != "" && substr($project, -5) != "Total") {
          if ($project == "AUD-2018" && $req[13] != "") {
          $queryObj = BFBudget::where([['client_id', '=', $client], ['project_id', '=', $project], ['role_id', '=', $role], ['assign_id', '=', $assign], ['year', '=', "2020"], ['no', '=', "9"]]);
          $queryObj->update([
          "working_days" => $req[13],
          ]);
          }
          }
          } */
    }
    
    public function getWeekNo($week, $year, $month, $day) {
        $weekNo = 0;
        $cnt = 0;
        foreach ($week as $w) {
            if ($w->year == $year && $w->month == $month && $w->day == $day) {
                $weekNo = $cnt;
            }
            $cnt += 1;
        }
        return $weekNo + 1;
    }
    
    function getDateRange($dateFrom, $dateTo){
        //Date From Toを指定されない場合は当年
        //Fromが指定された場合は以降1年
        //Toが指定された場合は以前1年
        $retDateFrom = [];//$dateFrom;
        $retDateTo = [];//$dateTo;
        if($dateFrom == "blank" && $dateTo == "blank"){
            $calendarArray = Week::where([["year", "=", date("Y")]])->get();            
            array_push($retDateFrom,sprintf('%02d', $calendarArray[0]["month"]));
            array_push($retDateFrom,sprintf('%02d', $calendarArray[0]["day"]));
            array_push($retDateFrom,$calendarArray[0]["year"]);
            
            array_push($retDateTo,sprintf('%02d', $calendarArray[51]["month"]));
            array_push($retDateTo,sprintf('%02d', $calendarArray[51]["day"]));  
            array_push($retDateTo,$calendarArray[51]["year"]);
        } else if($dateFrom != "blank" || $dateTo == "blank") {  
            $dateFrom = explode("-", $dateFrom);  
            $calendarArray = $this->getWeek($dateFrom[2], intval($dateFrom[0]), intval($dateFrom[1]));                   
            array_push($retDateFrom,sprintf('%02d', $calendarArray[0]["month"]));
            array_push($retDateFrom,sprintf('%02d', $calendarArray[0]["day"]));
            array_push($retDateFrom,$calendarArray[0]["year"]);
            
            array_push($retDateTo,sprintf('%02d', $calendarArray[51]["month"]));
            array_push($retDateTo,sprintf('%02d', $calendarArray[51]["day"]));   
            array_push($retDateTo,$calendarArray[51]["year"]);
        } else if($dateFrom == "blank" || $dateTo != "blank"){
            $calendarArray = Week::where([["year", "=", date("Y")]])->get();            
            array_push($retDateFrom,sprintf('%02d', $calendarArray[0]["month"]));
            array_push($retDateFrom,sprintf('%02d', $calendarArray[0]["day"]));
            array_push($retDateFrom,$calendarArray[0]["year"]);
            
            array_push($retDateTo,sprintf('%02d', $calendarArray[51]["month"]));
            array_push($retDateTo,sprintf('%02d', $calendarArray[51]["day"]));  
            array_push($retDateTo,$calendarArray[51]["year"]);
        }
        
        return [$retDateFrom,$retDateTo];
    }
    
    function getDetailData(Request $request) {
        $requestDateFrom = explode("-", $request->from);        
        $requestDateTo = explode("-", $request->to);
        
        list($dateFrom,$dateTo) = $this->getDateRange($request->from, $request->to);        
       //var_dump($dateFrom);
       //var_dump($dateTo);
        //row setting
        $colClient = 0;
        $colProject = 1;
        $colFye = 2;
        $colVic = 3;
        $colPic = 4;
        $colRole = 5;
        $colAssign = 6;
        $colBudget = 7;
        $colAssignedHours = 8;
        $colDiff = 9;
        $colWeek = 10;
        
        /*if($request->from != "blank"){
            $dateFrom = $requestDateFrom;
        }
        
        if($request->to != "blank"){
            $dateTo = $requestDateTo;
        }*/
        
        $weekArray = $this->getWeek($dateFrom[2], intval($dateFrom[0]), intval($dateFrom[1]));        
        $startDateAll = $weekArray[0]["year"] . sprintf('%02d', $weekArray[0]["month"]) . sprintf('%02d', $weekArray[0]["day"]);
        $endDateAll = $weekArray[51]["year"] . sprintf('%02d', $weekArray[51]["month"]) . sprintf('%02d', $weekArray[51]["day"]);        
        $startDate = $dateFrom[2] . $dateFrom[0] . $dateFrom[1];        
        $endDate = $dateTo[2] . $dateTo[0] . $dateTo[1];       
        /*var_dump($startDateAll);
        var_dump($endDateAll);
        var_dump($startDate);
        var_dump($endDate);*/

        $res = [];
        
        //対象のAssign取得
        $targetAssignIdList = $this->getOverallDetailQuery($request, $startDate, $endDate)
                ->select("assign_id","client.name","role_order.order")
                ->where("working_days","<>","0")                
                ->orderBy("client.name")
                ->orderBy("project.id")
                ->orderBy("role_order.order")
                ->groupBy("assign_id")
                ->groupBy("client.name")     
                ->groupBy("role_order.order");
                //->get();
        
        if($request->clientAS == "true"){
            $targetAssignIdList->where([["client.is_archive","<>",1]]);
        }
        
        if($request->projectAS == "true"){
            $targetAssignIdList->where([["project.is_archive","<>",1]]);
        }
        
        $targetAssignIdList = $targetAssignIdList->get();
        
        $targetAssignId = "";
        foreach($targetAssignIdList as $idList){
            $targetAssignId .= $idList["assign_id"];
            $targetAssignId .= ",";
        }
        if($targetAssignId != ""){
            $targetAssignId = substr($targetAssignId,0,-1);
        }
        
        $overallDetailData = $this->getAssignDataObj()
                ->wherein('assign_id', explode(",", $targetAssignId))
                //->where([["ymd",">=",$startDateAll]])  
                //->where([["ymd",">=",$startDateAll],["client.id","<>","0"]]) 
                ->where([["ymd",">=",$startDateAll]]) 
                ->groupBy("staff_id", "initial", "year", "month", "day")
                ->orderBy("staff_id", "asc")
                ->orderBy("year", "asc")
                ->orderBy("month", "asc")
                ->orderBy("day", "asc")
                ->get();      
        
        $overallTotal = $this->getAssignDataObj()
                ->select("budget.year", "budget.month", "budget.day", DB::raw("SUM(CEILING(working_days)) as working_days"))
                ->wherein('assign_id', explode(",", $targetAssignId))             
                ->where([["ymd",">=",$startDateAll]])  
                //->where([["ymd",">=",$startDateAll],["client.id","<>","0"]]) 
                ->groupBy("year", "month", "day")
                ->orderBy("year", "asc")
                ->orderBy("month", "asc")
                ->orderBy("day", "asc")
                ->get();
        
        $overallWeekTotal = $this->getAssignDataObj()
                ->select(DB::raw("SUM(CEILING(working_days)) as working_days"))    
                ->wherein('assign_id', explode(",", $targetAssignId))   
                ->where([["ymd",">=",$startDateAll]])  
                //->where([["ymd",">=",$startDateAll],["client.id","<>","0"]]) 
                ->get();
        
        $overallPersonalTotal = $this->getAssignDataObj()
                ->select("staff_id", DB::raw("SUM(CEILING(working_days)) as working_days"))
                ->wherein('assign_id', explode(",", $targetAssignId))
                //->where([["ymd",">=",$startDateAll],["client.id","<>","0"]])  
                ->where([["ymd",">=",$startDateAll]])  
                ->groupBy("staff_id")
                ->get();
        
        //phase取得用
        $phaseColorList = [];
        $targetPhaseList = $this->getOverallDetailQuery($request, $startDate, $endDate)
                ->select("project.id as project_id","client.name as client_name","project_name")   
                ->groupBy("project.id")
                ->groupBy("client.name")     
                ->groupBy("project.project_name")
                ->get();
        foreach ($targetPhaseList as $idList) {
            $dataPhase = $this->initArray();
            $phaseListObj = ProjectPhase::join("phase","phase.id","=","project phase.phase_id")
                    ->where([["project_id","=",$idList["project_id"]],["ymd",">=",$startDate],["ymd","<=",$endDate]]);
            if(!$phaseListObj->exists()){
                //continue;
            }
            
            //$warningProjectList = $this->warningProject($idList["project_id"]);
            $warningProjectList = $this->warningProject($idList["project_id"],$weekArray);

            $dataPhase[0] = $idList["project_id"];
            $dataPhase[1] = $idList["client_name"];
            $dataPhase[2] = $idList["project_name"];
                        
            $phaseList = $phaseListObj->get();            
            foreach ($phaseList as $yyy) {
                if($dataPhase[$colWeek - 1 + $this->getWeekNo($weekArray, $yyy->year, $yyy->month, $yyy->day)] != ""){
                    $dataPhase[$colWeek - 1 + $this->getWeekNo($weekArray, $yyy->year, $yyy->month, $yyy->day)] .= ";";
                }
                $pColor = $yyy->color;
                foreach($warningProjectList as $warningList){
                    if($warningList["year"] == $yyy->year && $warningList["month"] == $yyy->month && $warningList["day"] == $yyy->day){
                        $pColor = $warningList["errorColor"];
                        if($warningList["errorColor"] == "#cc0000"){
                            break;
                        }                        
                    }
                }
                /*foreach ($warningProjectList as $warningList){
                    if ($warningList->color == $pColor) {
                        $pColor = "#e06666";
                        $dt = new \DateTime("now", new \DateTimeZone('America/Los_Angeles'));
                        $usDate = $dt->format('Ymd');
                        
                        if (!is_null($warningList->due_date) && str_replace("-", "", $warningList->due_date) <= $usDate) {
                             $pColor = "#cc0000";
                        }
                    }
                }*/
                $dataPhase[$colWeek - 1 + $this->getWeekNo($weekArray, $yyy->year, $yyy->month, $yyy->day)] .= $pColor;//$yyy->color;                
            }            
            
            foreach($warningProjectList as $warningList){
                if($dataPhase[$colWeek - 1 + $this->getWeekNo($weekArray, $warningList["year"], $warningList["month"], $warningList["day"])] != ""){
                    $dataPhase[$colWeek - 1 + $this->getWeekNo($weekArray, $warningList["year"], $warningList["month"], $warningList["day"])] .= ";";
                }
                $dataPhase[$colWeek - 1 + $this->getWeekNo($weekArray, $warningList["year"], $warningList["month"], $warningList["day"])] .= $warningList["errorColor"];//$yyy->color;                
            }

            array_push($phaseColorList, $dataPhase);
        }
       
        //------------------------------
        
        $index = 2;
        
        foreach ($targetAssignIdList as $idList) { 
            $data = $this->initArray();
            $budgetDetail = Assign::select("client.name as client_id", "project.project_name as project_id", "assign.role as role_id", "staff.initial", "budget.year", "budget.month", "budget.day", "budget.working_days as working_days","client.fye", "client.vic_status","B.initial as pic","assign.budget_hour","client.id as client")//, "budget.no as no")//,"B.initial as pic")
                    ->leftjoin("project", "assign.project_id", "=", "project.id")
                    ->leftjoin("client", "client.id", "=", "project.client_id")
                    ->leftjoin("staff", "staff.id", "=", "assign.staff_id")
                    ->leftjoin("budget", "budget.assign_id", "=", "assign.id")
                    ->leftjoin("staff as B", "B.id", "=", "project.pic")
                    ->where([['assign_id', '=', $idList["assign_id"]],["ymd",">=",$startDateAll],["ymd","<=",$endDateAll]])                   
                    ->get();
                                    
            $data[$colClient] = $budgetDetail[0]["client_id"];
            $data[$colProject] = $budgetDetail[0]["project_id"];
            $data[$colFye] = $budgetDetail[0]["fye"];
            $data[$colVic] = $budgetDetail[0]["vic_status"];
            $data[$colPic] = $budgetDetail[0]["pic"];
            $data[$colRole] = $budgetDetail[0]["role_id"];
            $data[$colAssign] = $budgetDetail[0]["initial"];
            $data[$colBudget] = $budgetDetail[0]["budget_hour"];
            $data[$colAssignedHours] = 0;
            $data[$colDiff] = 0;

            $totalBudget = 0;
            foreach ($budgetDetail as $yyy) {
                $data[$colWeek - 1 + $this->getWeekNo($weekArray, $yyy->year, $yyy->month, $yyy->day)] = $yyy->working_days;
                $totalBudget += $yyy->working_days;
            }
            $data[$colAssignedHours] = $totalBudget;
            $data[$colDiff] = $data[$colBudget] - $data[$colAssignedHours];
            $colClientId = "65";
            $data[$colClientId] = $budgetDetail[0]["client"];

            if ($totalBudget != 0) {
                array_push($res, $data);
                $index += 1;
            }
        }


        //overall data + to do list
        $overallDetailArray = [];
        //overall detail data
        foreach($overallDetailData as $ovDetail){
            $tdata = [];
            $tdata["day"] = $ovDetail->day;
            $tdata["initial"] = $ovDetail->initial;
            $tdata["month"] = $ovDetail->month;
            $tdata["staff_id"] = $ovDetail->staff_id;
            $tdata["working_days"] = $ovDetail->working_days;
            $tdata["year"] = $ovDetail->year;

            array_push($overallDetailArray, $tdata);
        }

        //overall total data + to do list
        $overallTotalData = [];
        foreach($overallTotal as $ovtData){
            $tdata = [];
            $tdata["day"] = $ovtData->day;            
            $tdata["month"] = $ovtData->month;   
            $tdata["year"] = $ovtData->year;   
            $tdata["working_days"] = $ovtData->working_days;   
            
            array_push($overallTotalData, $tdata);
        }

        //overall total personal data + to do list
        $overallPersonalTotalData = [];
        foreach($overallPersonalTotal as $ovpData){
            $tdata = [];              
            $tdata["staff_id"] = $ovpData->staff_id;   
            $tdata["working_days"] = $ovpData->working_days;   

            array_push($overallPersonalTotalData, $tdata);
        }

        //overall total week data + to do list
        $overallWeekTotalData = [];        
        foreach($overallWeekTotal as $ovwData){
            $tdata = [];              
            $tdata["working_days"] = $ovwData->working_days;               

            array_push($overallWeekTotalData, $tdata);
        }
        

        //------------------------------

        //to do list
        //-----------------------------------------        
        $todoListGroupObj = $this->getToDoListDetailObj($request->client, $request->project,$startDateAll,$endDateAll,$request->staff,$request->pic,$request->vic,$request->fye);
        $todoListGroupData = $todoListGroupObj->get();

        //preparer_idで必要な人数分、行を作成
        $todoGroupArray = [];
        $staffArray = explode(",",$request->staff);
        foreach($todoListGroupData as $lGroupData){
            $preparerArray = explode(",",$lGroupData->preparer_id);
            for($i=0; $i<count($preparerArray); $i++){

                if($request->staff != "blank" && !in_array($preparerArray[$i],$staffArray)){
                    continue;
                }

                $todoGroupArrayItem = [];                
                $todoGroupArrayItem["client_id"] = $lGroupData->client_id;
                $todoGroupArrayItem["project_id"] = $lGroupData->project_id;
                $todoGroupArrayItem["staff_id"] = $preparerArray[$i];

                $isUnique = true;
                for($j=0; $j<count($todoGroupArray); $j++){
                    if($todoGroupArray[$j]["client_id"] == $lGroupData->client_id && $todoGroupArray[$j]["project_id"] == $lGroupData->project_id && $todoGroupArray[$j]["staff_id"] == $preparerArray[$i]){
                        $isUnique = false;
                    }
                }

                if($isUnique){
                    array_push($todoGroupArray,$todoGroupArrayItem);
                }
                
            }
        }
        
        $xxx = [];
        $ddd = [];
        foreach($todoGroupArray as $gData){    
            $todoListObj = ToDoList::select("to_do_list.requestor_id","client.name as client_name","project.project_name","client.fye","client.vic_status","B.initial as pic","to_do_list.duration","to_do_list.end_time")
                            ->leftjoin("project", "to_do_list.project_id", "=", "project.id")
                            ->leftjoin("client", "client.id", "=", "to_do_list.client_id")
                            ->leftjoin("staff as B", "B.id", "=", "project.pic")                        
                            //->leftjoin("assign as C", [["C.project_id", "=", "to_do_list.project_id"],["C.staff_id","=","to_do_list.requestor_id"]])
                            //->leftjoin("staff as D", "D.id", "=", "to_do_list.requestor_id")                        
                            ->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") BETWEEN "' . $startDateAll . '" and "' . $endDateAll . '"'); 

            $todoListObj = $todoListObj->where('to_do_list.client_id',"=", $gData["client_id"]);
            $todoListObj = $todoListObj->where('to_do_list.project_id',"=" ,$gData["project_id"]);
            $todoListObj = $todoListObj->whereRaw('FIND_IN_SET(' . $gData["staff_id"] . "," . 'to_do_list.preparer_id)');
            $todoList = $todoListObj->get();   
           
            //role id
            $roleIdObj = Assign::where([["project_id","=",$gData["project_id"]],["staff_id","=",$gData["staff_id"]]]);
            $roleName = "";            
            if($roleIdObj->exists()){
                $roleName = $roleIdObj->first()->role;                
            }

            //roleの検索条件があれば除外
            $isRole = false;
            if ($request->role != "blank") {                
                $roleArray = explode(",", $request->role);
                $roleFilter = [];
                for ($i = 0; $i < count($roleArray); $i++) {
                    if ($roleArray[$i] == 1 && $roleName == "Partner") {
                        $isRole = true;
                    }   
                    if ($roleArray[$i] == 2 && $roleName == "Senior Manager") {
                        $isRole = true;
                    }
                    if ($roleArray[$i] == 3 && $roleName == "Manager") {
                        $isRole = true;
                    }
                    if ($roleArray[$i] == 4 && $roleName == "Experienced Senior") {
                        $isRole = true;
                    }
                    if ($roleArray[$i] == 5 && $roleName == "Senior") {
                        $isRole = true;
                    }
                    if ($roleArray[$i] == 6 && $roleName == "Experienced Staff") {
                        $isRole = true;
                    }
                    if ($roleArray[$i] == 7 && $roleName == "Staff") {
                        $isRole = true;
                    }
                }    
                if($isRole == false){
                    continue;
                }
            }
            

            //staff initial
            $assignInitial = Staff::where([["id","=",$gData["staff_id"]]])->first()->initial;

            $data = $this->initArray();
            $data[$colClient] = $todoList[0]["client_name"];
            $data[$colProject] = $todoList[0]["project_name"] . "[TD]";
            $data[$colFye] = $todoList[0]["fye"];
            $data[$colVic] = $todoList[0]["vic_status"];
            $data[$colPic] = $todoList[0]["pic"];
            $data[$colRole] = $roleName;
            $data[$colAssign] = $assignInitial;
            $data[$colBudget] = "0";
            $data[$colAssignedHours] = $todoList[0]["duration"];
            $data[$colDiff] = $todoList[0]["duration"];

            foreach($todoList as $yyy){
                $targetToDoYear = substr($yyy->end_time,6,4);
                $targetToDoMonth = substr($yyy->end_time,0,2);
                $targetToDoDay = substr($yyy->end_time,3,2);
                
                $cellData = $data[$colWeek - 1 + $this->getToDoListWeekNo($weekArray, $targetToDoYear, $targetToDoMonth, $targetToDoDay)];                
                if($cellData != ""){
                    //$cellData = doubleval($cellData) + $yyy->duration;
                    $targetYearArray = $this->getTargetWeek($weekArray, $targetToDoYear, $targetToDoMonth, $targetToDoDay);

                    //overall detail
                    $isUpdate = false;                    
                    for($i=0;$i<count($overallDetailArray);$i++){                            
                        if($overallDetailArray[$i]["staff_id"] == $gData["staff_id"] && $overallDetailArray[$i]["year"] == $targetYearArray["year"] && $overallDetailArray[$i]["month"] == $targetYearArray["month"] && $overallDetailArray[$i]["day"] == $targetYearArray["day"]){                            
                            //$overallDetailArray[$i]["working_days"] += $cellData;
                            $overallDetailArray[$i]["working_days"] += $yyy->duration;
                            $isUpdate = true;
                        }
                    }

                    if($isUpdate == false){
                        $tdata = [];
                        $tdata["day"] = $targetYearArray["day"];
                        $tdata["initial"] = $data[$colAssign];
                        $tdata["month"] = $targetYearArray["month"];
                        $tdata["staff_id"] = $gData["staff_id"];
                        $tdata["working_days"] = $cellData;
                        $tdata["year"] = $targetYearArray["year"];
        
                        array_push($overallDetailArray, $tdata);
                    }
                    
                }else{                        
                    //overall detail
                    $cellData = $yyy->duration;

                    //over all data 追加
                    $targetYearArray = $this->getTargetWeek($weekArray, $targetToDoYear, $targetToDoMonth, $targetToDoDay);
                    $isUpdate = false;                    
                    for($i=0;$i<count($overallDetailArray);$i++){                            
                        if($overallDetailArray[$i]["staff_id"] == $gData["staff_id"] && $overallDetailArray[$i]["year"] == $targetYearArray["year"] && $overallDetailArray[$i]["month"] == $targetYearArray["month"] && $overallDetailArray[$i]["day"] == $targetYearArray["day"]){
                            $overallDetailArray[$i]["working_days"] += $cellData;                            
                            $isUpdate = true;
                        }
                    }

                    if($isUpdate == false){
                        $tdata = [];
                        $tdata["day"] = $targetYearArray["day"];
                        $tdata["initial"] = $data[$colAssign];
                        $tdata["month"] = $targetYearArray["month"];
                        $tdata["staff_id"] = $gData["staff_id"];
                        $tdata["working_days"] = $cellData;
                        $tdata["year"] = $targetYearArray["year"];
        
                        array_push($overallDetailArray, $tdata);
                    }
                }

                $data[$colWeek - 1 + $this->getToDoListWeekNo($weekArray, $targetToDoYear, $targetToDoMonth, $targetToDoDay)] = $cellData;
                $totalBudget = $cellData;//$yyy->duration;                

                //-------------------------------
                //overall total
                $isUpdate = false;
                for($i=0;$i<count($overallTotalData);$i++){                        
                    if($overallTotalData[$i]["year"] == $targetYearArray["year"] && $overallTotalData[$i]["month"] == $targetYearArray["month"] && $overallTotalData[$i]["day"] == $targetYearArray["day"]){
                        $overallTotalData[$i]["working_days"] += ceil($yyy->duration);
                        $isUpdate = true;
                    }
                }

                if($isUpdate == false){
                    $tdata = [];
                    $tdata["day"] = $targetYearArray["day"];                        
                    $tdata["month"] = $targetYearArray["month"];                        
                    $tdata["working_days"] = ceil($yyy->duration);
                    $tdata["year"] = $targetYearArray["year"];
        
                    array_push($overallTotalData, $tdata);
                }

                //overall personal total                
                $isUpdate = false;
                for($i=0;$i<count($overallPersonalTotalData);$i++){                                            
                    if($overallPersonalTotalData[$i]["staff_id"] == $gData["staff_id"]){
                        $overallPersonalTotalData[$i]["working_days"] += ceil($yyy->duration);
                        $isUpdate = true;
                    }
                }
                if($isUpdate == false){
                    $tdata = [];                                         
                    $tdata["working_days"] = ceil($yyy->duration);                    
                    $tdata["staff_id"] = $gData["staff_id"];
        
                    array_push($overallPersonalTotalData, $tdata);
                }

                //overall week total data                
                for($i=0;$i<count($overallWeekTotalData);$i++){                        
                    $overallWeekTotalData[$i]["working_days"] += ceil($yyy->duration);
                }  
            }
            
            $data[$colAssignedHours] = $totalBudget;
            $data[$colDiff] = $data[$colBudget] - $data[$colAssignedHours];

            if ($totalBudget != 0) {
                array_push($res, $data);
            }
        }
        //------------------------------------------------


        $json = [
            "week" => $weekArray,
            //"total" => $overallDetailData,
            "total" => $overallDetailArray,
            //"overallTotal" => $overallTotal,
            "overallTotal" => $overallTotalData,
            //"overallWeekTotal" => $overallWeekTotal,
            "overallWeekTotal" => $overallWeekTotalData,
            //"overallPTotal" => $overallPersonalTotal,            
            "overallPTotal" => $overallPersonalTotalData,            
            "clientList" => $res,
            "targetAssignId" => $targetAssignId,
            "phaseColor" => $phaseColorList,
            "warningProj" => $ddd,
            "xxx" => $xxx
        ];
        
        return response()->json($json);
        
    }

    function getToDoListDetailObj($client, $project,$startDateAll,$endDateAll,$staff,$pic,$reqVic,$reqFye){        
        $todoListGroupObj = ToDoList::select("to_do_list.id","to_do_list.client_id","to_do_list.project_id","to_do_list.preparer_id")  
                            ->leftjoin("project", "to_do_list.project_id", "=", "project.id")
                            ->leftjoin("client", "client.id", "=", "to_do_list.client_id")
                            ->leftjoin("staff as B", "B.id", "=", "project.pic")           
                            ->whereRaw('str_to_date(left(end_time,10),"%m/%d/%Y") BETWEEN "' . $startDateAll . '" and "' . $endDateAll . '"');                
        if ($client != "blank") {
            $todoListGroupObj = $todoListGroupObj->wherein('client.id', explode(",", $client));
        }

        if ($project != "blank") {
            $todoListGroupObj = $todoListGroupObj->wherein('project.project_name', explode(",", $project));
        }

        //staff
        $asigneeStr = "";
        $assignArray = "";
        if($staff != "blank"){
            $assignArray = explode(",",$staff);
            for($i=0; $i<count($assignArray); $i++){                
                if($asigneeStr != ""){
                    $asigneeStr .= " or ";    
                }
                $asigneeStr .= "FIND_IN_SET(" . $assignArray[$i] . ",to_do_list.preparer_id)";                
            }            
            $todoListGroupObj = $todoListGroupObj->whereRaw("(" . $asigneeStr . ")");
        }

        //pic
        if ($pic != "blank") {
            $picArray = explode(",", $pic);
            $todoListGroupObj = $todoListGroupObj->wherein('project.pic', $picArray);
        }

        //vic
        if ($reqVic != "blank") {
            $vic = "";
            $vicArray = explode(",", $reqVic);
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

            $todoListGroupObj = $todoListGroupObj->wherein('client.vic_status', $vicFilter);
        }

        //fye
        if ($reqFye != "blank") {
            $fye = "";

            $fyeArray = explode(",", $reqFye);
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

            $todoListGroupObj = $todoListGroupObj
                    ->wherein(DB::raw('Substring(client.fye,1,2)'), $fyeFilter);
        }


        return $todoListGroupObj;

    }

    public function getToDoListWeekNo($week, $year, $month, $day) {
        $weekNo = 0;
        $cnt = 0;
        $isOutOfRange = true;
        foreach ($week as $w) {
            if ($w->year == $year && $w->month == $month) {
                //$weekNo = $cnt;
                if($w->day == $day){
                    $weekNo = $cnt;
                    $isOutOfRange = false;
                    break;
                }else if($w->day >= $day) {
                    $weekNo = $cnt - 1;   
                    $isOutOfRange = false; 
                    break;                
                }
            }
            $cnt += 1;
        }
        
        //2023/4/28のようにweekテーブルの月ごとのレコードの最後の日よりも大きい場合、次の月の最初の週にする。
        if($isOutOfRange){
            $cnt = 0;
            $weekNo = 0;
            $defaultYear = $year;
            $defaultMonth = $month + 1;        
            if($month == "12"){
                $defaultYear = $year + 1;
                $defaultMonth = "1";        
            }            
            foreach ($week as $w) {
                if ($w->year == $defaultYear && $w->month == $defaultMonth) {
                    $weekNo = $cnt;
                    break;
                }
                $cnt += 1;
            }
        }
        //--------------------------------------------------------------------------------------------------
        
        return $weekNo + 1;
    }

    public function getTargetWeek($week, $year, $month, $day){
        $weekNo = 0;
        $cnt = 0;
        $targetWeek = [];
        $targetWeek["year"] = "";
        $targetWeek["month"] = "";
        $targetWeek["day"] = "";

        foreach ($week as $w) {
            if ($w->year == $year && $w->month == $month) {
                //$weekNo = $cnt;
                if($w->day == $day){
                    //$weekNo = $cnt;
                    $targetWeek["year"] = $w->year;
                    $targetWeek["month"] = $w->month;
                    $targetWeek["day"] = $w->day;
                    break;
                }else if($w->day >= $day) {
                    //$weekNo = $cnt - 1;    
                    $targetWeek["year"] = $week[$cnt-1]["year"];
                    $targetWeek["month"] = $week[$cnt-1]["month"];
                    $targetWeek["day"] = $week[$cnt-1]["day"];
                    break;                
                }
            }
            $cnt += 1;
        }

        //2023/4/28のようにweekテーブルの月ごとのレコードの最後の日よりも大きい場合、次の月の最初の週にする。
        if($targetWeek["year"] == ""){            
            $defaultYear = $year;
            $defaultMonth = $month + 1;        
            if($month == "12"){
                $defaultYear = $year + 1;
                $defaultMonth = "1";        
            }            
            $weekArray = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', $defaultYear],["month","=",$defaultMonth]])->first();
                 
            $targetWeek["year"] = $weekArray->year;
            $targetWeek["month"] = $weekArray->month;
            $targetWeek["day"] = $weekArray->day;           
        }
        
        return $targetWeek;//$weekNo + 1;
    }
    
    function getErrorRow($targetDate, $dueDate,$usDate,$weekArray) {
        $res = [];
        $errorColor = "#f4cccc";
        if ($dueDate <= $usDate) {
            $errorColor = "#cc0000";
        }
        //$res["errorColor"] = $errorColor;
        $weekCnt = 0;
        //何週目か割り出し
        foreach ($weekArray as $weekItems) {
            $weekymd = $weekItems["year"] . sprintf('%02d', $weekItems["month"]) . sprintf('%02d', $weekItems["day"]);
            $weekCnt += 1;
            if ($targetDate < $weekymd) {
                break;
            }
        }        
      
        if ($weekCnt != 1) {
            $res["errorColor"] = $errorColor;
            $res["year"] = $weekArray[$weekCnt - 2]["year"];
            $res["month"] = $weekArray[$weekCnt - 2]["month"];
            $res["day"] = $weekArray[$weekCnt - 2]["day"];
        }

        return $res;
    }

    function warningProject($projectId,$weekArray) {
        //ワーニング背景色
        $warningPhase = ProjectPhaseItem::select("project phase item.project_id","due_date", "phase.color","planed_prep","prep_sign_off","planned_review","review_sign_off","planned_review2","review_sign_off2")                
                ->leftJoin("phase group", "project phase item.phase_group_id", "=", "phase group.id")
                ->leftJoin("phase", "phase group.phase_id", "=", "phase.id")
                ->where([["project phase item.project_id", "=", $projectId]])                
                ->where(function($query) {
                    $query->where(function($query){
                        $query->whereRaw("planed_prep <= CURDATE() and prep_sign_off is null");
                    });
                    $query->orWhere(function($query){
                        $query->whereRaw("planned_review <= CURDATE() and review_sign_off is null");
                    });
                    $query->orWhere(function($query) {
                        $query->whereRaw("planned_review2 <= CURDATE() and review_sign_off2 is null");
                    });
                 });
                 
        //return $warningPhase->get();
        $warningData = $warningPhase->get();
        $retArray = [];
        foreach($warningData as $items) {
            $errorColor = "";
            $res = [];
            $dueDate = $items["due_date"];     
            $prepDate = $items["planed_prep"];
            $prepSignoff = $items["prep_sign_off"];
            $rev1Date = $items["planned_review"];
            $rev1Signoff = $items["review_sign_off"];
            $rev2Date = $items["planned_review2"];
            $rev2Signoff = $items["review_sign_off2"];
            
            if(!is_null($dueDate)){
                $dueDate = str_replace("-", "", $dueDate);
            }
            
            if(!is_null($prepDate)){
                $prepDate = str_replace("-", "", $prepDate);
            }    
            
            if(!is_null($rev1Date)){
                $rev1Date = str_replace("-", "", $rev1Date);
            }    
            
            if(!is_null($rev2Date)){
                $rev2Date = str_replace("-", "", $rev2Date);
            }    
                            
            $dt = new \DateTime("now", new \DateTimeZone('America/Los_Angeles'));
            $usDate = $dt->format('Ymd');
            
            //prepDateが当日以降
            if (!is_null($prepDate) && $prepDate <= $usDate && is_null($prepSignoff)) {
                $res = $this->getErrorRow($prepDate, $dueDate, $usDate, $weekArray);
                if($res != []){
                    array_push($retArray, $res);
                }
                
            }

            if (!is_null($rev1Date) && $rev1Date <= $usDate && is_null($rev1Signoff)) {
                $res = $this->getErrorRow($rev1Date, $dueDate, $usDate, $weekArray);
                if($res != []){
                array_push($retArray, $res);
                }
            }

            if (!is_null($rev2Date) && $rev2Date <= $usDate && is_null($rev2Signoff)) {
                $res = $this->getErrorRow($rev2Date, $dueDate, $usDate, $weekArray);
                if($res != []){
                array_push($retArray, $res);
                }
            }
        }
        return $retArray;
    }

    function getOverallDetailQuery($request, $dateFrom, $dateTo) {
        $overallDetail = Budget::select("staff_id", "staff.initial as initial", "budget.year", "budget.month", "budget.day", DB::raw("SUM(working_days) as working_days"),"role_order.order")
                ->Join("assign", "assign.id", "=", "budget.assign_id")
                ->leftJoin("staff", "assign.staff_id", "=", "staff.id")
                ->leftJoin("project", "project.id", "=", "assign.project_id")
                ->leftjoin("client", "project.client_id", "=", "client.id")
                ->leftjoin("staff as B", "B.id", "=", "project.pic")
                ->leftjoin("role_order", "role_order.role", "=", "assign.role");;
                
        if($request->picAS == "true"){
            $overallDetail = $overallDetail->where([["B.status","=","Active"]]);
        }
        
        if($request->staffAS == "true"){
            $overallDetail = $overallDetail->where([["staff.status","=","Active"]]);
        }
        
        if ($request->client != "blank") {
            $overallDetail = $overallDetail
                    ->wherein('client.id', explode(",", $request->client));
        }

        if ($request->project != "blank") {
            $overallDetail = $overallDetail
                    ->wherein('project.project_name', explode(",", $request->project));
        }

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

            $overallDetail = $overallDetail
                    ->wherein(DB::raw('Substring(client.fye,1,2)'), $fyeFilter);
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

            $overallDetail = $overallDetail
                    ->wherein('client.vic_status', $vicFilter);
        }

        if ($request->orValue == "blank") {
            if ($request->pic != "blank") {
                $picArray = explode(",", $request->pic);
                
                $overallDetail = $overallDetail
                        ->wherein('project.pic', $picArray);
            }

            if ($request->staff != "blank") {
                $staffArray = explode(",", $request->staff);

                $overallDetail = $overallDetail
                        ->wherein('assign.staff_id', $staffArray);
            }
        } else {           
            $picArray = explode(",", $request->pic);
            $staffArray = explode(",", $request->staff);
            $overallDetail = $overallDetail->where(function($query) use($picArray,$staffArray){                
                $query->wherein('project.pic', $picArray);
                $query->orwherein('assign.staff_id', $staffArray);
            });
            
        }

        if ($request->role != "blank") {
            $role = "";
            $roleArray = explode(",", $request->role);
            $roleFilter = [];
            for ($i = 0; $i < count($roleArray); $i++) {
                if ($roleArray[$i] == 1) {
                    array_push($roleFilter, "Partner");
                }
                if ($roleArray[$i] == 2) {
                    array_push($roleFilter, "Senior Manager");
                }
                if ($roleArray[$i] == 3) {
                    array_push($roleFilter, "Manager");
                }
                if ($roleArray[$i] == 4) {
                    array_push($roleFilter, "Experienced Senior");
                }
                if ($roleArray[$i] == 5) {
                    array_push($roleFilter, "Senior");
                }
                if ($roleArray[$i] == 6) {
                    array_push($roleFilter, "Experienced Staff");
                }
                if ($roleArray[$i] == 7) {
                    array_push($roleFilter, "Staff");
                }
            }

            $overallDetail = $overallDetail
                    ->wherein('assign.role', $roleFilter);
        }

        $overallDetail = $overallDetail->whereBetween("ymd", [$dateFrom, $dateTo]);

        return $overallDetail;
    }
    
    function getAssignDataObj(){
        $obj = Budget::select("staff_id", "staff.initial as initial", "budget.year", "budget.month", "budget.day", DB::raw("SUM(CEILING(working_days)) as working_days"))
                ->Join("assign", "assign.id", "=", "budget.assign_id")
                ->leftJoin("staff", "assign.staff_id", "=", "staff.id")
                ->leftJoin("project", "project.id", "=", "assign.project_id")
                ->leftjoin("client", "project.client_id", "=", "client.id")
                ->leftjoin("staff as B", "B.id", "=", "project.pic");        
        return $obj;
    }

    function getWeekStartDate($year, $month, $day){                        
        $weekArray = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', $year],["month","=",$month],["day","=",$day]]);
        if($weekArray->exists()){
            return $year . $month . $day;
        }

        //週の真ん中の場合、週初めの日付を取得
        $weekArray = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', $year],["month","=",$month]])->get();
        $rowCnt = 0;
        foreach($weekArray as $item){
            if($item->day < $day){
                $targetDateArray = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', $year],["month","=",$month]])->get($rowCnt);
                foreach($targetDateArray as $xxx){
                    return $xxx->year . $xxx->month . $xxx->day;
                }
            }
            $rowCnt += 1;
        }
        
        //月をまたいだ場合        
        if($month == 1){
            $targetYear = $year - 1;
            $targetMonth = "12";
        }else{
            $targetYear = $year;
            $targetMonth = $month - 1;
        }
        
        $lastRowCnt = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', $targetYear],["month","=",$targetMonth]])->get()->count() - 1;
        $targetDateArray = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', $targetYear],["month","=",$targetMonth]])->get($lastRowCnt);
        foreach($targetDateArray as $xxx){
            return $xxx->year . $xxx->month . $xxx->day;
        }

    }
       
}
