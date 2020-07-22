<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Project;
use App\Assign;
use App\Budget;
use App\Staff;
use App\Week;
use Illuminate\Support\Facades\DB;

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
        $staffData = Staff::get();
        //pic
        $picData = Staff::get();
       
        return view('budget_input')
                        ->with("client", $clientData)
                        ->with("project", $projectData)
                        ->with("staff", $staffData)
                        ->with("pic", $picData);
    }
    
    function indexShow() {
        //client
        $clientData = Client::orderBy("name", "asc")->get();
        
        //project
        $projectData = Project::select("project_name")
                        ->groupBy('project_name')
                        ->orderBy('project_name', 'asc')->get();
        //staff
        $staffData = Staff::get();
        //pic
        $picData = Staff::get();
        
        return view('budget_show')
                ->with("client", $clientData)
                ->with("project", $projectData)
                ->with("staff", $staffData)
                ->with("pic", $picData);
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
        $data[$colDiff] = "=I1-H1";

        $res = [];
        array_push($res, $data);

        //project取得
        $comments = Project::select("client.name as client", "project.project_name as project", "assign.role as role", "staff.initial as initial", "client.fye", "client.vic_status", "B.initial as pic")
                ->leftjoin("client", "project.client_id", "=", "client.id")
                ->leftjoin("assign", "assign.project_id", "=", "project.id")
                ->leftjoin("staff", "staff.id", "=", "assign.staff_id")
                ->leftjoin("staff as B", "B.id", "=", "client.pic");
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
                    array_push($fyeFilter, "1/31");
                }
                if ($fyeArray[$i] == 2) {
                    array_push($fyeFilter, "2/28");
                }
                if ($fyeArray[$i] == 3) {
                    array_push($fyeFilter, "3/31");
                }
                if ($fyeArray[$i] == 4) {
                    array_push($fyeFilter, "4/30");
                }
                if ($fyeArray[$i] == 5) {
                    array_push($fyeFilter, "5/31");
                }
                if ($fyeArray[$i] == 6) {
                    array_push($fyeFilter, "6/30");
                }
                if ($fyeArray[$i] == 7) {
                    array_push($fyeFilter, "7/31");
                }
                if ($fyeArray[$i] == 8) {
                    array_push($fyeFilter, "8/31");
                }
                if ($fyeArray[$i] == 9) {
                    array_push($fyeFilter, "9/30");
                }
                if ($fyeArray[$i] == 10) {
                    array_push($fyeFilter, "10/31");
                }
                if ($fyeArray[$i] == 11) {
                    array_push($fyeFilter, "11/30");
                }
                if ($fyeArray[$i] == 12) {
                    array_push($fyeFilter, "12/31");
                }
            }

            $comments = $comments
                    ->wherein('client.fye', $fyeFilter);
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
                    ->wherein('client.pic', $picArray);
        }

        if ($request->staff != "blank") {
            $staffArray = explode(",", $request->staff);

            $comments = $comments
                    ->wherein('assign.staff_id', $staffArray);
        }

        if ($request->role != "blank") {
            /* $role=0;
              if($request->role == 1){$role="Partner";}
              if($request->role == 2){$role="Senior Manager";}
              if($request->role == 3){$role="Manager";}
              if($request->role == 4){$role="Experienced Senior";}
              if($request->role == 5){$role="Senior";}
              if($request->role == 6){$role="Experienced Staff";}
              if($request->role == 7){$role="Staff";} */
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

            $comments = $comments
                    ->wherein('assign.role', $roleFilter);
        }

        $comments = $comments
                ->orderBy("client", "asc")
                ->orderBy("project", "asc")
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

            //budget data 取得            
            $budgetDetail = Assign::select("client.name as client_id", "project.project_name as project_id", "assign.role as role_id", "staff.initial", "budget.year", "budget.month", "budget.day", "budget.working_days as working_days")//,"B.initial as pic")
                    ->leftjoin("project", "assign.project_id", "=", "project.id")
                    ->leftjoin("client", "client.id", "=", "project.client_id")
                    ->leftjoin("staff", "staff.id", "=", "assign.staff_id")
                    ->leftjoin("budget", "budget.assign_id", "=", "assign.id")
                    //->leftjoin("A_staff as B", "B.id", "=", "A_client.pic")
                    ->where([['client.name', '=', $xxx->client], ['project.project_name', '=', $xxx->project], ['assign.role', '=', $xxx->role], ['staff.initial', '=', $xxx->initial], ['budget.ymd', '<=', $endDate], ['budget.ymd', '>=', $startDate]])
                    ->get();

            //対象行数            
            $detailRowCnt = Assign::select()
                            ->leftjoin("project", "assign.project_id", "=", "project.id")
                            ->leftjoin("client", "client.id", "=", "project.client_id")
                            ->where([['client.name', '=', $xxx->client], ['project.project_name', '=', $xxx->project]])->count();
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
                $data1[$colBudget] = "0";
                $data1[$colAssignedHours] = "=SUM(K" . $index . ":BJ" . $index . ")";
                $data1[$colDiff] = "=I" . $index . "-H" . $index;
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
                $data1[$colBudget] = "0";
                $data1[$colAssignedHours] = "=SUM(K" . $index . ":BJ" . $index . ")";
                $data1[$colDiff] = "=I" . $index . "-H" . $index;
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
            $data[$colBudget] = "0";
            $data[$colAssignedHours] = "=SUM(K" . $index . ":BJ" . $index . ")";
            $data[$colDiff] = "=I" . $index . "-H" . $index;

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

        //date
        /* $week = Week::orderBy('month', 'asc')->orderBy('week', 'asc')->where([['year', '=', "2020"]])->get();
          $weekArray = [];
          foreach ($week as $xxx) {
          array_push($weekArray, $xxx["month"] . "/" . $xxx["day"]);
          } */
        $week = $this->getWeek($requestYear, $requestMonth, $requestDay);
        $weekArray = [];
        foreach ($week as $xxx) {
            array_push($weekArray, $xxx["year"] . "/" . $xxx["month"] . "/" . $xxx["day"]);
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
        for ($s = 0; $s < 65; $s++) {
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
            $budgetTable->working_days = $request->value;           
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
    
    function getDetailData(Request $request) {

        $dateFrom = explode("-",$request->from);
        $dateTo = explode("-",$request->to);
        $startDate = $dateFrom[2] . $dateFrom[0] . $dateFrom[1];
        $endDate = $dateTo[2] . $dateTo[0] . $dateTo[1];

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

        $weekArray = $this->getWeek($dateFrom[2], intval($dateFrom[0]), intval($dateFrom[1]));

        $res = [];

        $overallDetail = Budget::select("staff_id", "staff.initial as initial", "year", "month", "day", DB::raw("SUM(working_days) as working_days"))
          ->leftJoin("assign", "assign.id", "=", "budget.assign_id")
          ->leftJoin("staff", "assign.staff_id", "=", "staff.id")
          ->leftJoin("project", "project.id", "=", "assign.project_id")
          ->leftjoin("client", "project.client_id", "=", "client.id")
          ->leftjoin("staff as B", "B.id", "=", "client.pic"); 
        
        if ($request->client != "blank") {
            $overallDetail = $overallDetail
                    ->wherein('client.id', explode(",", $request->client));
        }

        if ($request->project != "blank") {
            $overallDetail = $overallDetail
                    ->wherein('project.name', explode(",", $request->project));
        }

        if ($request->fye != "blank") {
            $fye = "";

            $fyeArray = explode(",", $request->fye);
            $fyeFilter = [];
            for ($i = 0; $i < count($fyeArray); $i++) {
                if ($fyeArray[$i] == 1) {
                    array_push($fyeFilter, "1/31");
                }
                if ($fyeArray[$i] == 2) {
                    array_push($fyeFilter, "2/28");
                }
                if ($fyeArray[$i] == 3) {
                    array_push($fyeFilter, "3/31");
                }
                if ($fyeArray[$i] == 4) {
                    array_push($fyeFilter, "4/30");
                }
                if ($fyeArray[$i] == 5) {
                    array_push($fyeFilter, "5/31");
                }
                if ($fyeArray[$i] == 6) {
                    array_push($fyeFilter, "6/30");
                }
                if ($fyeArray[$i] == 7) {
                    array_push($fyeFilter, "7/31");
                }
                if ($fyeArray[$i] == 8) {
                    array_push($fyeFilter, "8/31");
                }
                if ($fyeArray[$i] == 9) {
                    array_push($fyeFilter, "9/30");
                }
                if ($fyeArray[$i] == 10) {
                    array_push($fyeFilter, "10/31");
                }
                if ($fyeArray[$i] == 11) {
                    array_push($fyeFilter, "11/30");
                }
                if ($fyeArray[$i] == 12) {
                    array_push($fyeFilter, "12/31");
                }
            }

            $overallDetail = $overallDetail
                    ->wherein('client.fye', $fyeFilter);
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

        if ($request->pic != "blank") {
            $picArray = explode(",", $request->pic);

            $overallDetail = $overallDetail
                    ->wherein('client.pic', $picArray);
        }

        if ($request->staff != "blank") {
            $staffArray = explode(",", $request->staff);

            $overallDetail = $overallDetail
                    ->wherein('assign.staff_id', $staffArray);
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
        
        $overallDetailData = $overallDetail
                ->groupBy("staff_id", "initial", "year", "month", "day")
                ->orderBy("staff_id", "asc")
                ->orderBy("year", "asc")
                ->orderBy("month", "asc")
                ->orderBy("day", "asc")
                ->get();        
       
        $overallTotal = $overallDetail
                ->groupBy("year", "month", "day")
                ->orderBy("year", "asc")
                ->orderBy("month", "asc")
                ->orderBy("day", "asc")
                ->get();

        $overallPersonalTotal = $overallDetail                
                ->groupBy("staff_id")
                ->get();

        //project取得
        $comments = Project::select("client.name as client", "project.project_name as project", "assign.role as role", "staff.initial as initial", "client.fye", "client.vic_status", "B.initial as pic")
                ->leftjoin("client", "project.client_id", "=", "client.id")
                ->leftjoin("assign", "assign.project_id", "=", "project.id")
                ->leftjoin("staff", "staff.id", "=", "assign.staff_id")
                ->leftjoin("staff as B", "B.id", "=", "client.pic");
        
        if ($request->client != "blank") {
            $comments = $comments
                    ->wherein('client.id', explode(",", $request->client));
        }
        
        if ($request->project != "blank") {
            $comments = $comments
                    ->wherein('project.name', explode(",", $request->project));
        }

        if ($request->fye != "blank") {
            $fye = "";

            $fyeArray = explode(",", $request->fye);
            $fyeFilter = [];
            for ($i = 0; $i < count($fyeArray); $i++) {
                if ($fyeArray[$i] == 1) {
                    array_push($fyeFilter, "1/31");
                }
                if ($fyeArray[$i] == 2) {
                    array_push($fyeFilter, "2/28");
                }
                if ($fyeArray[$i] == 3) {
                    array_push($fyeFilter, "3/31");
                }
                if ($fyeArray[$i] == 4) {
                    array_push($fyeFilter, "4/30");
                }
                if ($fyeArray[$i] == 5) {
                    array_push($fyeFilter, "5/31");
                }
                if ($fyeArray[$i] == 6) {
                    array_push($fyeFilter, "6/30");
                }
                if ($fyeArray[$i] == 7) {
                    array_push($fyeFilter, "7/31");
                }
                if ($fyeArray[$i] == 8) {
                    array_push($fyeFilter, "8/31");
                }
                if ($fyeArray[$i] == 9) {
                    array_push($fyeFilter, "9/30");
                }
                if ($fyeArray[$i] == 10) {
                    array_push($fyeFilter, "10/31");
                }
                if ($fyeArray[$i] == 11) {
                    array_push($fyeFilter, "11/30");
                }
                if ($fyeArray[$i] == 12) {
                    array_push($fyeFilter, "12/31");
                }
            }

            $comments = $comments
                    ->wherein('client.fye', $fyeFilter);
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
                    ->wherein('client.pic', $picArray);
        }

        if ($request->staff != "blank") {
            $staffArray = explode(",", $request->staff);

            $comments = $comments
                    ->wherein('assign.staff_id', $staffArray);
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

            $comments = $comments
                    ->wherein('assign.role', $roleFilter);
        }

        $comments = $comments
                ->orderBy("client", "asc")
                ->orderBy("project", "asc")
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

            //budget data 取得            
            $budgetDetail = Assign::select("client.name as client_id", "project.project_name as project_id", "assign.role as role_id", "staff.initial", "budget.year", "budget.month", "budget.day", "budget.working_days as working_days")//,"B.initial as pic")
                    ->leftjoin("project", "assign.project_id", "=", "project.id")
                    ->leftjoin("client", "client.id", "=", "project.client_id")
                    ->leftjoin("staff", "staff.id", "=", "assign.staff_id")
                    ->leftjoin("budget", "budget.assign_id", "=", "assign.id")
                    //->leftjoin("A_staff as B", "B.id", "=", "A_client.pic")
                    ->where([['client.name', '=', $xxx->client], ['project.project_name', '=', $xxx->project], ['assign.role', '=', $xxx->role], ['staff.initial', '=', $xxx->initial], ['budget.ymd', '<=', $endDate], ['budget.ymd', '>=', $startDate]])
                    ->get();

            //対象行数            
            $detailRowCnt = Assign::select()
                            ->leftjoin("project", "assign.project_id", "=", "project.id")
                            ->leftjoin("client", "client.id", "=", "project.client_id")
                            ->where([['client.name', '=', $xxx->client], ['project.project_name', '=', $xxx->project]])->count();
            //Assignが存在しない場合は、集計の計算式がズレてしまうため
            if ($detailRowCnt == 0) {
                $detailRowCnt = 1;
            }
    
            $newClient = $xxx->client;
            $newProject = $xxx->project;

          
            $data[$colClient] = $xxx->client;
            $data[$colProject] = $xxx->project;
            $data[$colFye] = $xxx->fye;
            $data[$colVic] = $xxx->vic_status;
            $data[$colPic] = $xxx->pic;
            $data[$colRole] = $xxx->role;
            $data[$colAssign] = $xxx->initial;
            $data[$colBudget] = 0;
            $data[$colAssignedHours] = 0;
            $data[$colDiff] = 0;

            $totalBudget = 0;
            foreach ($budgetDetail as $yyy) {
                $data[$colWeek - 1 + $this->getWeekNo($weekArray, $yyy->year, $yyy->month, $yyy->day)] = $yyy->working_days;
                $totalBudget += $yyy->working_days;
            }
            $data[$colAssignedHours] = $totalBudget;
            $data[$colDiff] = $data[$colAssignedHours] - $data[$colBudget];

            array_push($res, $data);
            $index += 1;

            $oldClient = $newClient;
            $oldProject = $newProject;
            $oldRole = $newRole;
            $oldAssign = $newAssign;
        }

        $json = [
            "week" => $weekArray,
            "total" => $overallDetailData,
            "overallTotal" => $overallTotal,
            "overallPTotal" => $overallPersonalTotal,
            "clientList" => $res
        ];

        return response()->json($json);
    }

}
