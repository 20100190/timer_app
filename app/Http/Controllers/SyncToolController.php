<?php

namespace App\Http\Controllers;

use App\ProjectHarvest;
use App\UserHarvest;
use App\ClientHarvest;
use App\TimeEntryHarvest;
use App\InvoiceHarvest;
use App\ExpenseHarvest;
use App\Engagement;
use App\EngagementHarvest;
use Illuminate\Http\Request;

class SyncToolController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$this->getInvoiceFromHarvest();
        
        return view('sync_tools');
    }

    public function syncProject(){
        $data = $this->getProjectFromHarvest();
        $this->insertHarvestProject($data);
    }

    public function syncUser(){
        $data = $this->getUserFromHarvest();
        $this->insertHarvestUser($data);
    }

    public function syncClient(){
        $data = $this->getClientFromHarvest();
        $this->insertHarvestClient($data);
    }

    public function syncTimeEntry(Request $request){
        $dateFrom = explode("-",$request->from);
        $dateTo = explode("-",$request->to);

        $dateFromStr = $dateFrom[2] . "-" . $dateFrom[0] . "-" . $dateFrom[1];
        $dateToStr = $dateTo[2] . "-" . $dateTo[0] . "-" . $dateTo[1];
         
        $queryStr = "?from=" . $dateFromStr;
        $queryStr .= "&to=" . $dateToStr;

        $data = $this->getTimeEntryFromHarvest($queryStr);
        $this->insertHarvestTimeEntry($data,$dateFromStr, $dateToStr);
    }

    public function syncInvoice(){
        $data = $this->getInvoiceFromHarvest();           
        $this->insertHarvestInvoice($data);
    }

    public function syncExpense(){
        $data = $this->getExpenseFromHarvest();
        $this->insertHarvestExpense($data);
    }


    public function getInvoiceFromHarvest(){
        set_time_limit(0);
        $url = "https://api.harvestapp.com/v2/invoices?to=2050-12-31&from=2016-01-01";
        
        $invoiceArray = $this->execCurl($url);

        //100件ごとに帰ってくる、最大ページ数
        $totalPage = $invoiceArray["total_pages"];

        $dataArray = [];

        //for ($i = 1; $i <= $totalPage; $i++) {
        for ($i = 1; $i <= $totalPage; $i++) {
            $urlIndex = $url . "&page=" . $i;

            $projectArrayIndex = $this->execCurl($urlIndex)["invoices"];

            foreach ($projectArrayIndex as $projectItem) {
                foreach ($projectItem["line_items"] as $lineItem) {

                    $dataArrayItem = [];

                    $dataArrayItem["client_id"] = $projectItem["client"]["id"];
                    $dataArrayItem["client_name"] = $projectItem["client"]["name"];

                    $dataArrayItem["project_id"] = 0;
                    if(isset($lineItem["project"]["id"])){
                        $dataArrayItem["project_id"] = $lineItem["project"]["id"];
                    }
                   
                    $dataArrayItem["kind"] = $lineItem["kind"];
                    $dataArrayItem["quantity"] = $lineItem["quantity"];
                    $dataArrayItem["amount"] = $lineItem["amount"];
                    $dataArrayItem["invoice_num"] = $projectItem["number"];
                    $dataArrayItem["description"] = $lineItem["description"];
                    $dataArrayItem["period_start"] = $projectItem["period_start"];
                    $dataArrayItem["period_end"] = $projectItem["period_end"];
                    $dataArrayItem["issue_date"] = $projectItem["issue_date"];
                    //$dataArrayItem["discount"] = $projectItem["discount_amount"];

                    array_push($dataArray, $dataArrayItem);
                }
            }

        }

        return $dataArray;
        
    }

    public function getExpenseFromHarvest(){
        set_time_limit(0);
        $url = "https://api.harvestapp.com/v2/expenses";
        
        $invoiceArray = $this->execCurl($url);

        //100件ごとに帰ってくる、最大ページ数
        $totalPage = $invoiceArray["total_pages"];

        $dataArray = [];

        //for ($i = 1; $i <= $totalPage; $i++) {
        for ($i = 1; $i <= $totalPage; $i++) {
            $urlIndex = $url . "?page=" . $i;

            $projectArrayIndex = $this->execCurl($urlIndex)["expenses"];

            foreach ($projectArrayIndex as $projectItem) {                

                    $dataArrayItem = [];

                    $dataArrayItem["id"] = $projectItem["id"];
                    $dataArrayItem["notes"] = $projectItem["notes"];
                    $dataArrayItem["total_cost"] = $projectItem["total_cost"];
                    $dataArrayItem["units"] = $projectItem["units"];
                    $dataArrayItem["spent_date"] = $projectItem["spent_date"];
                    $dataArrayItem["billable"] = $projectItem["billable"];
                    $dataArrayItem["user_id"] = $projectItem["user"]["id"];
                    $dataArrayItem["user_name"] = $projectItem["user"]["name"];
                    $dataArrayItem["project_id"] = $projectItem["project"]["id"];
                    $dataArrayItem["project_name"] = $projectItem["project"]["name"];
                    $dataArrayItem["expense_category_id"] = $projectItem["expense_category"]["id"];
                    $dataArrayItem["expense_category_name"] = $projectItem["expense_category"]["name"];
                    $dataArrayItem["client_id"] = $projectItem["client"]["id"];
                    $dataArrayItem["client_name"] = $projectItem["client"]["name"];
                
                    $dataArrayItem["invoice_id"] = 0;
                    if(isset($projectItem["invoice"]["id"])){
                        $dataArrayItem["invoice_id"] = $projectItem["invoice"]["id"];           
                    }
                                    
                    $dataArrayItem["invoice_name"] = "";

                    array_push($dataArray, $dataArrayItem);
                
            }

        }

        return $dataArray;
    }

    public function getUserFromHarvest(){
        $url = "https://api.harvestapp.com/v2/users";

        $projectArray = $this->execCurl($url);

        //100件ごとに帰ってくる、最大ページ数
        $totalPage = $projectArray["total_pages"];

        $dataArray = [];

        //for ($i = 1; $i <= $totalPage; $i++) {
        for ($i = 1; $i <= $totalPage; $i++) {
            $urlIndex = $url . "?page=" . $i;

            $projectArrayIndex = $this->execCurl($urlIndex)["users"];       
            
            foreach ($projectArrayIndex as $projectItem) {
                $dataArrayItem = [];
               
                $dataArrayItem["id"] = $projectItem["id"];
                $dataArrayItem["first_name"] = $projectItem["first_name"];
                $dataArrayItem["last_name"] = $projectItem["last_name"];
                $dataArrayItem["hourly_rate"] = $projectItem["default_hourly_rate"];
                $dataArrayItem["cost_rate"] = $projectItem["cost_rate"];    
                /*$dataArrayItem["role"] = "";                
                if ($projectItem["budget"] != "")
                {
                    $dataArrayItem["budget"] = $projectItem["budget"];
                }  */

                array_push($dataArray,$dataArrayItem);
            }

        }

        return $dataArray;
    }

 
    public function getProjectFromHarvest(){
        $url = "https://api.harvestapp.com/v2/projects";

        $projectArray = $this->execCurl($url);

        //100件ごとに帰ってくる、最大ページ数
        $totalPage = $projectArray["total_pages"];

        $dataArray = [];

        //for ($i = 1; $i <= $totalPage; $i++) {
        for ($i = 1; $i <= $totalPage; $i++) {
            $urlIndex = $url . "?page=" . $i;

            $projectArrayIndex = $this->execCurl($urlIndex)["projects"];       
            
            foreach ($projectArrayIndex as $projectItem) {
                $dataArrayItem = [];

                $dataArrayItem["id"] = $projectItem["id"];
                $dataArrayItem["client_id"] = $projectItem["client"]["id"];
                $dataArrayItem["client_name"] = $projectItem["client"]["name"];
                $dataArrayItem["project_name"] = $projectItem["name"];
                $dataArrayItem["code"] = $projectItem["code"];
                $dataArrayItem["is_active"] = $projectItem["is_active"];

                $dataArrayItem["budget"] = 0;                
                if ($projectItem["budget"] != "")
                {
                    $dataArrayItem["budget"] = $projectItem["budget"];
                }

                array_push($dataArray,$dataArrayItem);
            }

        }

        return $dataArray;
    }

    public function getClientFromHarvest(){
        $url = "https://api.harvestapp.com/v2/clients";

        $projectArray = $this->execCurl($url);

        //100件ごとに帰ってくる、最大ページ数
        $totalPage = $projectArray["total_pages"];

        $dataArray = [];

        //for ($i = 1; $i <= $totalPage; $i++) {
        for ($i = 1; $i <= $totalPage; $i++) {
            $urlIndex = $url . "?page=" . $i;

            $projectArrayIndex = $this->execCurl($urlIndex)["clients"];       
            
            foreach ($projectArrayIndex as $projectItem) {
                $dataArrayItem = [];
               
                $dataArrayItem["id"] = $projectItem["id"];
                $dataArrayItem["name"] = $projectItem["name"];                

                array_push($dataArray,$dataArrayItem);
            }

        }

        return $dataArray;
    }

    public function getTimeEntryFromHarvest($queryStr){
        set_time_limit(0);
        //$url = "https://api.harvestapp.com/v2/time_entries?to=2016-05-31&from=2016-01-01";
        $url = "https://api.harvestapp.com/v2/time_entries" . $queryStr;

        $projectArray = $this->execCurl($url);

        //100件ごとに帰ってくる、最大ページ数
        $totalPage = $projectArray["total_pages"];

        $dataArray = [];

        //for ($i = 1; $i <= $totalPage; $i++) {
        for ($i = 1; $i <= $totalPage; $i++) {
            $urlIndex = $url . "&page=" . $i;

            $projectArrayIndex = $this->execCurl($urlIndex)["time_entries"];
            
            foreach ($projectArrayIndex as $projectItem) {
                $dataArrayItem = [];
                               
                $dataArrayItem["client_id"] = $projectItem["client"]["id"];
                $dataArrayItem["spent_date"] = $projectItem["spent_date"];
                $dataArrayItem["project_id"] = $projectItem["project"]["id"];
                $dataArrayItem["project_name"] = $projectItem["project"]["name"];
                $dataArrayItem["hour"] = $projectItem["hours"];
                $dataArrayItem["user_id"] = $projectItem["user"]["id"];
                $dataArrayItem["user_name"] = $projectItem["user"]["name"];
                $dataArrayItem["billable_rate"] = $projectItem["billable_rate"];
                $dataArrayItem["cost_rate"] = $projectItem["cost_rate"];
                $dataArrayItem["task_id"] = $projectItem["task"]["id"];
                $dataArrayItem["task_name"] = $projectItem["task"]["name"];
                $dataArrayItem["is_billed"] = $projectItem["is_billed"];
                $dataArrayItem["invoice_id"] = 0;
                if(isset($projectItem["invoice"]["id"])){
                    $dataArrayItem["invoice_id"] = $projectItem["invoice"]["id"];
                }

                $dataArrayItem["invoice_name"] = "";
                if(isset($projectItem["invoice"]["name"])){
                    $dataArrayItem["invoice_name"] = $projectItem["invoice"]["name"];
                }
                
                        
                //$dataArrayItem["invoice_id"] = $projectItem["invoice"]["id"];
                //$dataArrayItem["invoice_name"] = $projectItem["invoice"]["name"];
                                                
                $dataArrayItem["rounded_hours"] = $projectItem["rounded_hours"];
                $dataArrayItem["notes"] = $projectItem["notes"];

                array_push($dataArray,$dataArrayItem);
            }

        }

        return $dataArray;
    }

    public function createProjectToHarvest(){
        $url = "https://api.harvestapp.com/v2/projects";

        $projectDetail = [
            "client_id" => 867689,
            "name" => "Test Project",
            "is_billable" => true,
            "bill_by" => "People",
            "hourly_rate" => 321.0,
            "budget_by" => "project",
            "budget" => 123,
            "notify_when_over_budget" => true,
            "show_budget_to_all" => true,
            "starts_on" => "2021-01-01",
            "ends_on" => "2021-12-31",
            "notes" => "test project note",
        ];

        $projectArray = $this->execPostCurl($url,json_encode($projectDetail));

        var_dump($projectArray);
    }

    public function insertHarvestProject($projectData){

        $table = new ProjectHarvest;
        $table->query()->delete();

        foreach($projectData as $data){
            $table = new ProjectHarvest;
            $table->id = $data["id"];
            $table->client_id = $data["client_id"];
            $table->client_name = $data["client_name"];
            $table->project_name = $data["project_name"];
            $table->code = $data["code"];
            $table->is_active = $data["is_active"];
            $table->budget = $data["budget"];

            $table->save();            
        }
        
    }

    public function insertHarvestUser($projectData){

        $table = new UserHarvest;
        $table->query()->delete();

        foreach($projectData as $data){
            $table = new UserHarvest;
            
            $table->id = $data["id"];
            $table->first_name = $data["first_name"];
            $table->last_name = $data["last_name"];
            $table->hourly_rate = $data["hourly_rate"];
            $table->cost_rate = $data["cost_rate"];    

            $table->save();            
        }
        
    }

    public function insertHarvestInvoice($projectData){

        $table = new InvoiceHarvest;
        $table->query()->delete();

        foreach($projectData as $data){
            $table = new InvoiceHarvest;
            
            $table->client_id = $data["client_id"];
            $table->client_name = $data["client_name"];

            //$table->project_id = 0;
            $table->project_id = $data["project_id"];

            $table->kind = $data["kind"];
            $table->quantity = $data["quantity"];
            $table->amount = $data["amount"];
            $table->invoice_num = $data["invoice_num"];
            $table->description = $data["description"];
            $table->period_start = $data["period_start"];
            $table->period_end = $data["period_end"];
            $table->issue_date = $data["issue_date"];
            //$table->discount = $data["discount"];

            $table->save();            
        }
        
    }

    public function insertHarvestExpense($projectData){

        $table = new ExpenseHarvest;
        $table->query()->delete();

        foreach($projectData as $data){
            $table = new ExpenseHarvest;
            
            $table->id = $data["id"];
            $table->notes = $data["notes"];
            $table->total_cost = $data["total_cost"];
            $table->units = $data["units"];
            $table->spent_date = $data["spent_date"];
            $table->billable = $data["billable"];
            $table->user_id = $data["user_id"];
            $table->user_name = $data["user_name"];
            $table->project_id = $data["project_id"];
            $table->project_name = $data["project_name"];
            $table->expense_category_id = $data["expense_category_id"];
            $table->expense_category_name = $data["expense_category_name"];
            $table->client_id = $data["client_id"];
            $table->client_name = $data["client_name"];
                                    
            $table->invoice_id = $data["invoice_id"];           
                                    
            $table->invoice_name = "";

            $table->save();            
        }
        
    }

    public function insertHarvestClient($projectData){

        $table = new ClientHarvest;
        $table->query()->delete();

        foreach($projectData as $data){
            $table = new ClientHarvest;
            
            $table->id = $data["id"];
            $table->name = $data["name"];
            
            $table->save();            
        }
        
    }

    public function insertHarvestTimeEntry($projectData,$dateFrom, $dateTo){

        $table = new TimeEntryHarvest;
        $table->whereBetween('spent_date', [$dateFrom, $dateTo])->delete();

        foreach($projectData as $data){
            $table = new TimeEntryHarvest;
            
            $table->client_id = $data["client_id"];
            $table->spent_date = $data["spent_date"];
            $table->project_id = $data["project_id"];
            $table->project_name = $data["project_name"];
            $table->hour = $data["hour"];
            $table->user_id = $data["user_id"];
            $table->user_name = $data["user_name"];
            $table->billable_rate = $data["billable_rate"];
            $table->cost_rate = $data["cost_rate"];
            $table->task_id = $data["task_id"];
            $table->task_name = $data["task_name"];
            $table->is_billed = $data["is_billed"];
                      
            $table->invoice_id = $data["invoice_id"];
            $table->invoice_name = $data["invoice_name"];                                                
            $table->rounded_hour = $data["rounded_hours"];
            $table->note = $data["notes"];
            
            $table->save();            
        }
        
    }

    public function createEngagementFee(){
        $table = Engagement::select("engagement.id","engagement.project_id","engagement.no","engagement.type","engagement.col1","engagement.col2","engagement.col3","engagement.col4","engagement.col5","engagement.col6","engagement.col7","engagement.col8","engagement.col9","engagement.col10","engagement.col11","engagement.col12","project.project_name","client.name","engagement.start_year","engagement.start_month")->leftJoin("project","engagement.project_id","=","project.id")->leftJoin("client","client.id","=","project.client_id")->get();
        $dataArray = [];
        foreach($table as $data){                        
            
            $currentYear = "";
            
            //1から12
            for($i=0; $i<12; $i++){
                $dataArrayItem = [];
                $dataArrayItem["id"] = $data["id"];
                $dataArrayItem["project_id"] = $data["project_id"];
                $dataArrayItem["no"] = $data["no"];
                $dataArrayItem["type"] = $data["type"];
                $dataArrayItem["project_name"] = $data["project_name"];
                $dataArrayItem["name"] = $data["name"];
                
                //月
                $currentMonth = $data["start_month"] + $i;
                if($currentMonth > 12){
                    $currentMonth = $currentMonth - 12;
                } 

                $startYear = $data["start_year"];
                if($i != 0 &&  $currentMonth == 1){
                    $currentYear = $data["start_year"] + 1;
                }

                if($currentYear != ""){
                    $currentDate = $currentYear . "-" . str_pad($currentMonth, 2,0,STR_PAD_LEFT) . "-" .  "15";
                }else{
                    $currentDate = $startYear . "-" . str_pad($currentMonth, 2,0,STR_PAD_LEFT) . "-" . "15";
                }
                
                $dataArrayItem["date"] = $currentDate;
                $currentRow = $i+1;
                $amount = $data["col" . $currentRow];
                $dataArrayItem["amount"] = $amount;
                array_push($dataArray,$dataArrayItem);
            }            
        }        

        $table = new EngagementHarvest;
        $table->query()->delete();

        foreach($dataArray as $dataItem){
            $table = new EngagementHarvest;
            $table->id = $dataItem["id"];
            $table->project_id = $dataItem["project_id"];
            $table->type = $dataItem["type"];
            $table->no = $dataItem["no"];
            $table->project_name = $dataItem["project_name"];
            $table->client_name = $dataItem["name"];
            $table->date = $dataItem["date"];
            $table->amount = $dataItem["amount"];
            
            $table->save();
        }

    }

    public function execCurl($url){
        $headers = array(
            "Authorization: Bearer 1811250.pt.FwB6sKeYVYTGxSiARVlWk9eZATp7Jdu4u5eRjyFLv0XDGDs1A2gvTtilegTjoIJ4sCr0uqDOA-rWUGy1SNx4TA",
            "Harvest-Account-Id: 231068",
            "User-Agent: MyApp (takahiroy@topc.us)"
        );
        $conn = curl_init(); #cURLセッションの初期化
        curl_setopt($conn, CURLOPT_URL, $url); #取得するURLを指定
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true); #実行結果を文字列で返す。
        curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($conn);
        curl_close($conn); #セッションの終了
        
        $ary = json_decode($res, true);

        //var_dump($ary);
    
        //$data = $ary[$arrayItemName];

        return $ary;
    }

    public function execPostCurl($url,$targetJson){
        $headers = array(
            "Authorization: Bearer 1811250.pt.FwB6sKeYVYTGxSiARVlWk9eZATp7Jdu4u5eRjyFLv0XDGDs1A2gvTtilegTjoIJ4sCr0uqDOA-rWUGy1SNx4TA",
            "Harvest-Account-Id: 231068",
            "User-Agent: MyApp (takahiroy@topc.us)",           
            "Content-Type: application/json",
        );
        $conn = curl_init(); #cURLセッションの初期化
        curl_setopt($conn,CURLOPT_POST, true);
        curl_setopt($conn, CURLOPT_URL, $url); #取得するURLを指定
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true); #実行結果を文字列で返す。
        curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($conn, CURLOPT_POSTFIELDS, '{"client_id":867689,"name":"Test Project","is_billable":true,"bill_by":"Project","hourly_rate":100.0,"budget_by":"project","budget":10000}');
        curl_setopt($conn, CURLOPT_POSTFIELDS, $targetJson);


        $res = curl_exec($conn);
        curl_close($conn); #セッションの終了
        
        $ary = json_decode($res, true);
    
        //$data = $ary[$arrayItemName];        

        return $ary;
    }
}
