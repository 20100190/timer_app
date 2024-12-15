<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validate;
//use DB;
use App\Project;
use App\Client;
use App\ContactPerson;
use App\Shareholders;
use App\Officers;
use App\ClientHarvest;
use App\ProjectHarvest;
use App\Assign;
use App\ProjectTask;
use App\Staff;
use App\TaskHarvest;
use App\Task;
use App\ToDoList;
use App\User;
use App\OutlookAccessToken;
use App\OutlookClientInfo;
use Illuminate\Support\Facades\DB;

//=======================================================================
class TodoListEntryController extends Controller {

    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $clientList = Client::orderBy("name", "asc")->get();
        $projectList = Project::select("project_name", "id")->groupBy('project_name', 'id')->orderBy("project_name", "id", "asc")->get();
        $taskList = Task::select("name", "id")->groupBy('name', 'id')->orderBy("name", "id", "asc")->get();
        $requestorList = Staff::ActiveStaffOrderByInitial();
        $preparerList = Staff::ActiveStaffOrderByInitial();
        $optionalList = Staff::ActiveStaffOrderByInitial();
        return view("master.to_do_list_entry", compact("clientList", "projectList", "taskList", "requestorList", "preparerList", "optionalList"));
    }

    public function saveToDoListTable(Request $request) {
        $todoListObj = ToDoList::where([['id', '=', $request->input("to_do_list_id")]]);
        //$isExistTodoList = $todoListObj->exists();
        $isExistTodoList = false;
        $toDoListId = $request->input("to_do_list_id");
        if($toDoListId != ""){
            $isExistTodoList = true;
        }
        $event_id = "";

        $client = $request->input("client");
        $project = $request->input("project");
        $task = $request->input("task");
        $requestor = $request->input("requestor");
        $preparer = $request->input("preparer");
        $optional = $request->input("optional");
        $start = $request->input("start_date") . " " . $request->input("start_time");//$this->formatDate($request->input("start_date"), $request->input("start_time"));
        $duration = $request->input("duration");
        $end = $request->input("end_time");//substr($request->input("end_time"), 0, 10). "-". substr($request->input("end_time"), -5);
        $progress = $request->input("progress");
        $location = $request->input("location");
        $memo = $request->input("memo");
        $addBudgetHours = $request->input("add_budget_hours");

        //editのlinkをmemoに結合
        //to do listの登録予定ID取得
        $todoListTable = new ToDoList;
        //$toDoListId = intval($todoListTable->count()) + 1;
        $toDoListId = intval(ToDoList::max("id")) + 1;
        $editLink = 'https://' . $_SERVER['HTTP_HOST'] . "/master/to-do-list/" . $toDoListId . "/edit-todo";
        
        if (!$isExistTodoList) {
            $event_id = $this->saveCalendar($client, $project, $task, $requestor, $preparer, $optional, $start, $end, $location, $memo . "<br><a href=" . $editLink . ">" . $editLink . "</a>");
            $todoListTable = new ToDoList;
            $todoListTable->id = $toDoListId;
            $todoListTable->client_id = $client;
            $todoListTable->project_id = $project;
            $todoListTable->project_task_id = $task;
            $todoListTable->requestor_id = $requestor;
            $todoListTable->preparer_id = $preparer;
            $todoListTable->optional_personnel = $optional;
            $todoListTable->start_time = $start;
            $todoListTable->duration = $duration;
            $todoListTable->end_time = $end;
            $todoListTable->progress = $progress;
            $todoListTable->location = $location;
            $todoListTable->memo = $memo;
            $todoListTable->event_id = $event_id;
            $todoListTable->add_budget_hours = $addBudgetHours;

            $todoListTable->save();
        } else {
            $event_id_list = $todoListObj->get();
            $toDoListId = "";
            foreach ($event_id_list as $eid) {
                $event_id = $eid->event_id;
                $toDoListId = $eid->id;
            }

            $editLink = 'https://' . $_SERVER['HTTP_HOST'] . "/master/to-do-list/" . $toDoListId . "/edit-todo";

            $updateItem = [
                "client_id" => $client,
                "project_id" => $project,
                "project_task_id" => $task,
                "requestor_id" => $requestor,
                "preparer_id" => $preparer,
                "optional_personnel" => $optional,
                "start_time" => $start,
                "duration" => $duration,
                "end_time" => $end,
                "progress" => $progress,
                "location" => $location,
                "memo" => $memo,
                "event_id" => $event_id,
                "add_budget_hours" => $addBudgetHours,
            ];

            $todoListObj->update($updateItem);

            $updateItem["memo"] = $memo . "<br><a href=" . $editLink . ">" . $editLink . "</a>";        
            if($progress == "100"){
                $updateItem["memo"] = "Task Completed<br>" . $updateItem["memo"];
            }    
            $this->upadateCalendar($updateItem,$toDoListId);
                        
        }
        //$todoListId = $todoListObj->first()["id"];

        return $toDoListId;
    }

    public function formatDate($dateStr, $timeStr) {
        $dateJp = NULL;

        if ($dateStr != "") {
            $dateArray = explode('/', $dateStr);
            $dateJp = $dateArray[2] . "-" . $dateArray[0] . "-" . $dateArray[1] . "-" . $timeStr;
        }
        return $dateJp;
    }
    
    function taskDropdownStore(Request $request) {
        $clientId = explode(",", $request->project);

        $projectListObj = ProjectTask::select("task.name", "task.id")
                ->leftjoin("task", "task.id", "=", "project task.task_id")
                ->groupBy("task.name", 'task.id')
                ->orderBy('task.name', 'task.id', 'asc');

        if ($clientId != 'blank') {
            $projectListObj = $projectListObj->whereIn("project_id", $clientId);
        }
        $projectList = $projectListObj->get();
        $json = [
            "projectData" => $projectList,
        ];
        return response()->json($json);
    }

    /*
    public function getTodoListEntryData(Request $request) {
        $client = $request->client_id;
        $project = $request->project_id;
        //$task = $request->task_id;
        $requestor = $request->requestor_id;
        $preparerList = explode(",", $request->preparer_idList);
        $optionalList = explode(",", $request->optional_idList);

        $clientList = Client::select("name")->where("id", "=", $client)->get();
        $projectList = project::select("project_name")->where("id", "=", $project)->get();
        //$taskList = Task::select("name")->where("id", "=", $task)->get();
        $requestorList = Staff::select("first_name as requestor_first_name", "last_name as requestor_last_name", "staff.email as requestor_email")->where("id", "=", $requestor)->get();
        $preparerData = $this->getNameEmail($preparerList);
        $optionalData = $this->getNameEmail($optionalList);

        $todoListEntryData = [];

        $todoListEntryData["client"] = $clientList[0]->name;
        $todoListEntryData["project"] = $projectList[0]->project_name;
        $todoListEntryData["task"] = "";//$taskList[0]->name;
        $todoListEntryData["requestor_name"] = $requestorList[0]->requestor_first_name ." ". $requestorList[0]->requestor_last_name;
        $todoListEntryData["requestor_email"] = $requestorList[0]->requestor_email;
        $todoListEntryData["preparer_name"] = $preparerData["names"];
        $todoListEntryData["preparer_email"] = $preparerData["emails"];
        $todoListEntryData["optional_name"] = $optionalData["names"];
        $todoListEntryData["optional_email"] = $optionalData["emails"];

        $json = [
            "todoListEntryData" => $todoListEntryData,
        ];
        return response() -> json($json);

    }
    */

    function getNameEmail($id_list) {
        $str = "";
        $mails = "";
        if ($id_list != [null]) {
            for ($i = 0; $i < count($id_list); $i++) {
                $staffList = Staff::where("id", "=", $id_list[$i])->get();
                foreach ($staffList as $staff) {
                    $first_name = $staff->first_name;
                    $last_name = $staff->last_name;
                    $name = $first_name. " ". $last_name;
                    $str = $str . $name;
                    $mails = $mails . $staff->email;
                    if ($i != count($id_list) - 1){
                        $str = $str . ",";
                        $mails = $mails . ",";
                    }
                }
            }
        } else {
            $str = null;
        }
        return ["names"=>$str, "emails"=>$mails];
    }

    //Get access_token from refresh_token
    public function execPostCurl($requestorEmail) {
        /*
        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
        );
        */
        $requestorId = User::where([["email","=",$requestorEmail]])->first();
        $userId = $requestorId->id;//Auth::id();
        
        $refresh_token = $this->get_refresh_token($userId);
        //$refresh_token = $this->get_refresh_token("1");

        //outlook client情報取得
        $outlookClientInfo = $this->getOutlookClientInfo($userId);

        $params = [
            "Content-Type: application/x-www-form-urlencoded",
            //"client_id" => "0c331368-cc2c-4c80-b73e-97f26f3adad7",
            "client_id" => $outlookClientInfo->outlook_client_id,
            "scope" => "Calendars.ReadWrite offline_access",
            //"client_secret" => "MDJ8Q~yOXLv270V.w7MApwUNzmPHvIquZhtDnawz",
            "client_secret" => $outlookClientInfo->outlook_client_secret,
            "grant_type" => "refresh_token",
            "refresh_token" => $refresh_token
            //"redirect_uri" => "http://localhost:8000/to_do_list_entry",
            //"state" => "12345",
            //"code" => "0.AVkA1Vy7vVrcGUyCBg364o7KjmQENvX96RROk1TF5LF7q35ZACA.AgABAAIAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P_WQ4IJihkMPhCJIvnv0H7IVff_S49aRXm7plo277ydOmipOKFKfu_W-9fcEfo23HL8HR7oaqpiZ_gKEUuEcwMj1uDxWUdiDTatchcPf5a108EwhbDBFo2vJwb3IGjjUHby-Chhbw2I-JXlUIF-x9CA1MQ7ZBpGlelxh-h16wuNTew7fW1BufvzhbEmI10Cglw96wHwVOjY9zpgtDvYNm1pqR3ryNKFKgKtZTaS1IXuCzKherRv7lIP767CgXQkgEE733S1lMLqSF48Mf1akSG6a4YoiMinpqIibf78xmdkHHgS7p9lZqn1S-PZnZHMnX-utUHzuB7p5Wj9Hh_BtkQFb57ui-MKRnED3SmXXBLu-EbynZXpG8DxjprkeiY3o7UPxOooWKMaETx-C8yy6OjcC5ketGZ4syzXWdgnT-QjhynCrTYtrPXKVHn14OyqVr3thJDArtSTV82ndAFFb0fv6dJhANXVkoHAAsknpfyd0jQkeYCG_XNavfu6loJYv150AXpcCoUDgJbEn0sbmNxDyKJlgbt01FV3xvujLYJ6Z4H-v56aebMSvN1nyPuK7I9oI3p7IVpyC87AXYgcjnXpHL40jtdw_oyn9TCloo_UKkL59GiPrry_UwJ7ldeOHmnUmV0k23UawaFxHXO54RJcLLkNs8h9zIIuIQdKiF6F"
            
        ];

        $conn = curl_init();
        curl_setopt($conn, CURLOPT_POST, true);
        curl_setopt($conn, CURLOPT_URL, "https://login.microsoftonline.com/bdbb5cd5-dc5a-4c19-8206-0dfae28eca8e/oauth2/v2.0/token");
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($conn, CURLOPT_POSTFIELDS, $params);
        //curl_setopt($conn, CURLOPT_POSTFIELDS, $targetJson);

        $res = curl_exec($conn);
        curl_close($conn);

        $ary = json_decode($res, true);
        
        $access_token = $ary["access_token"];
        $new_refresh_token = $ary["refresh_token"];

        /*
        $conn = curl_init();
        curl_setopt($conn, CURLOPT_POST, true);
        curl_setopt($conn, CURLOPT_URL, " https://graph.microsoft.com/v1.0/users/260e2e6d-9aea-49c2-927d-23957ae7b7d4");
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_POSTFIELDS, ["Authorization: Bearer " . $access_token]);
        */

        $this->saveToken($userId, $access_token, $new_refresh_token);
        //$this->saveToken("1", $access_token, $new_refresh_token);

        return $ary;
    }

    //Save access_token and refresh_token to database
    function saveToken($staff_id, $access_token, $refresh_token) {
        $tokenObj = OutlookAccessToken::where([['staff_id', "=", $staff_id]]);
        $isExistToken = $tokenObj->exists();
        if (!$isExistToken) {
            $tokenListTable = new OutlookAccessToken;
            $tokenListTable->staff_id = $staff_id;
            $tokenListTable->access_token = $access_token;
            $tokenListTable->refresh_token = $refresh_token;

            $tokenListTable->save();
        } else {
            $updateItem = [
                "staff_id" => $staff_id,
                "access_token" => $access_token,
                "refresh_token" => $refresh_token
            ];

            $tokenObj->update($updateItem);
        }
    }

    //get refresh_token from database
    function get_refresh_token($staff_id) {
        //$refresh_token = "0.AVkA1Vy7vVrcGUyCBg364o7KjmQENvX96RROk1TF5LF7q35ZACA.AgABAAEAAAD--DLA3VO7QrddgJg7WevrAgDs_wQA9P_ViOgbJe5-HFrXTPMvORHF7o7J7TNDc64J9gA3KyE8I-EjHnDr7e8rmuKdfuxejBZwsUiutYxrxG_qdPCV6isB-HNm1hjt4l7pHJyRrXCSYuiisF47E4ZWSsUhCrgvnnxM9SFbMwZGIiJkzHQKqELGVzBtEC1iA8rEzSi0t9MjfhKP6EHZMj-BcLGP2FoNtoEWiF8N83STX4PrYCqz1_INGEtMS-MrWEGgeOBRcfNhGKrn91fzbaFaUOvTFPIqlfwNqDWYAYH_P159eG2pGiQykIU-Rhg2xmUEmfXutXx3JW88xcB_BSTq8es61aTSagoRNGtczh3HgqprmJLcnhUBTdr-uRNd4Ts7FnMWSOgXAJRSd90pXSUZFFZSKWYKFKLUSilfy2t6sUrDYxT9mzdc7viyz-HXoeEWSzCPXC3Catd9oHGn7jcERIAg5fYlOzuHILJeqn_WNMBk_t5mAKCAEdswiizrEPlHDzowtOEnFOnAnnRzCGrLAbBh9nXdje5maXyoW2PpBREQZE5gFsQFmtwmkxK2AYn5SFHotjQxWyzpZXLEv9vmwv8Jae-unXbVqj0DVwnRdXNCUxGyU8XDEizEetATzeXSgzzJCSNHbLemgEo_Z9EBPHm2gCGNv_3DhMn5I-rZwGk_jrl0RCtJkWiJSG3IYfvA2PWut2YPlPGZ2XIcgUqGS1cLsHcIWxM1YDFMIe-6SSzAqnnaDyiio7y6Xn6pDIzoL1jkviJkHxeMdarzveDT3ozcoigXzbTrpHjpxo4";
        
        $refresh_token = "";
        $tokenObjList = OutlookAccessToken::select("refresh_token")->where("staff_id", "=", $staff_id)->get();
        foreach ($tokenObjList as $tokenObj) {
            $refresh_token = $tokenObj["refresh_token"];
        }        
        
        return $refresh_token;
        
    }

    function getOutlookClientInfo($staff_id){
        $clientInfo = OutlookClientInfo::where("staff_id","=",$staff_id)->first();

        return $clientInfo;
    }

function saveCalendar($client_id, $project_id, $task, $requestor_id, $preparer_id, $optional_id, $start_time, $end_time, $location, $memo) {
        //make title
        $client = "";
        $project = "";
        //$task = "";
        $clientList = Client::select("name")->where("id", "=", $client_id)->get();
        foreach ($clientList as $c) {
            $client = $c["name"];
        }
        $projectList = Project::select("project_name")->where("id", "=", $project_id)->get();
        foreach ($projectList as $p) {
            $project = $p["project_name"];
        }
        /*$taskList = Task::select("name")->where("id", "=", $task_id)->get();
        foreach ($taskList as $t) {
            $task = $t["name"];
        }*/
        //$title = $client." ".$project." ".$task;
        $title = $this->getEmailTitle($client,$project,$task);

        //get name and email from id
        $preparerList = $this->getNameEmail(explode(",", $preparer_id));
        $optionalList = $this->getNameEmail(explode(",", $optional_id));
        $preparerNames = explode(",", $preparerList["names"]);
        $preparerMails = explode(",", $preparerList["emails"]);
        $optionalNames = explode(",", $optionalList["names"]);
        $optionalMails = explode(",", $optionalList["emails"]);
        
        //attendee(required and optional)
        
        $attendee = [];
        for ($i = 0; $i < count($preparerNames); $i++) {
            $required = [
                "emailAddress" => [
                    "address" => $preparerMails[$i],
                    "name" => $preparerNames[$i]
                ],
            "type" => "required"
            ];
            array_push($attendee, $required);
        }
        for ($i = 0; $i < count($optionalNames); $i++) {
            $optional = [
                "emailAddress" => [
                    "address" => $optionalMails[$i],
                    "name" => $optionalNames[$i]
                ],
            "type" => "optional"
            ];
            array_push($attendee, $optional);
        }
        
        //for test
        /*
        $attendee = [[
            "emailAddress" => [
                "address" => "intern2@topc.us",
                "name" => "Takumi Makara"
            ],
            "type" => "required"
        ]];
        */

        //get token
        $tokens = $this->execPostCurl($preparerMails[0]);
        $access_token = $tokens["access_token"];
        $refresh_token = $tokens["refresh_token"];

        //change time        
        $start = $start_time;
        $end = $end_time;

        $headers = array(
            "Authorization: Bearer ". $access_token,
            "Content-Type: application/json" 
        );

        $params = [
            "subject" => $title,
            "body"=> [
                "contentType"=> "HTML",
                "content"=> $memo
            ],
            "start"=> [
                "dateTime"=> $start,
                "timeZone"=> "Pacific Standard Time"
            ],
            "end"=> [
                "dateTime"=> $end,
                "timeZone"=> "Pacific Standard Time"
            ],
            "location"=> [
                "displayName"=> $location
            ],
            "attendees"=> $attendee,
        ];

        /*$conn = curl_init();
        curl_setopt($conn, CURLOPT_POST, true);
        curl_setopt($conn, CURLOPT_URL, "https://graph.microsoft.com/v1.0/me/events");
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($params));

        $res = curl_exec($conn);
        curl_close($conn);
        $ary = json_decode($res, true);
        $event_id = $ary["id"];    */
        
        $mh = curl_multi_init();
        // URLをキーとして、複数のCurlハンドルを入れて保持する配列
        $ch_list = array();
        $ch_list[0] = curl_init();

        curl_setopt($ch_list[0], CURLOPT_POST, true);
        curl_setopt($ch_list[0], CURLOPT_URL, "https://graph.microsoft.com/v1.0/me/events");
        curl_setopt($ch_list[0], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_list[0], CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_list[0], CURLOPT_POSTFIELDS, json_encode($params));
        curl_multi_add_handle($mh, $ch_list[0]);

        $running = null;        
        do {
            curl_multi_exec($mh, $running);
        } while ( $running );

        //結果
        $results[0] = curl_getinfo($ch_list[0]);
        $results[0]["content"] = curl_multi_getcontent($ch_list[0]);
        
        //close
        curl_multi_remove_handle($mh, $ch_list[0]);
        curl_close($ch_list[0]);    
        
        //後処理
        $ary = json_decode($results[0]["content"], true);        
        $event_id = $ary["id"];
                
        return $event_id;
    }

    //change time format
    function changeTime($time) {
        $items = explode("-", $time);
        return $items[0]."-".$items[1]."-".$items[2]."T".$items[3];
    }

    function deleteCalendar($id){
        
        //to do list event id, access token用preparer id取得
        $todoListObj = ToDoList::where([['id', '=', $id]]);
        $todoListData = $todoListObj->first();

        $preparerList = $this->getNameEmail(explode(",", $todoListData->preparer_id));
        $preparerMails = explode(",", $preparerList["emails"]);
        
        //get token
        $tokens = $this->execPostCurl($preparerMails[0]);
        $access_token = $tokens["access_token"];
        $refresh_token = $tokens["refresh_token"];

        $headers = array(
            "Authorization: Bearer ". $access_token,
            "Content-Type: application/json" 
        );

        $url = "https://graph.microsoft.com/v1.0/me/events/". $todoListData->event_id;

        $conn = curl_init();
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($params));

        $res = curl_exec($conn);
        curl_close($conn);

    }

    function upadateCalendar($item,$toDoListId) {
        //make title
        $client = "";
        $project = "";
        $task = "";
        $clientList = Client::select("name")->where("id", "=", $item["client_id"])->get();
        foreach ($clientList as $c) {
            $client = $c["name"];
        }
        $projectList = Project::select("project_name")->where("id", "=", $item["project_id"])->get();
        foreach ($projectList as $p) {
            $project = $p["project_name"];
        }
        /*$taskList = Task::select("name")->where("id", "=", $item["project_task_id"])->get();
        foreach ($taskList as $t) {
            $task = $t["name"];
        }*/
        $task = $item["project_task_id"];
        //$title = $client." ".$project." ".$task;
        $title = $this->getEmailTitle($client,$project,$task);

        //get each data
        $preparer_id = $item["preparer_id"];
        $optional_id = $item["optional_personnel"];
        $start_time = $item["start_time"];
        $end_time = $item["end_time"];
        $location = $item["location"];
        $memo = $item["memo"];
        $event_id = $item["event_id"];
        $requestor = $item["requestor_id"];

        $preparerList = $this->getNameEmail(explode(",", $preparer_id));
        $optionalList = $this->getNameEmail(explode(",", $optional_id));
        $preparerNames = explode(",", $preparerList["names"]);
        $preparerMails = explode(",", $preparerList["emails"]);
        $optionalNames = explode(",", $optionalList["names"]);
        $optionalMails = explode(",", $optionalList["emails"]);
        
        //attendee(required and optional)
        
        $attendee = [];
        for ($i = 0; $i < count($preparerNames); $i++) {
            $required = [
                "emailAddress" => [
                    "address" => $preparerMails[$i],
                    "name" => $preparerNames[$i]
                ],
            "type" => "required"
            ];
            array_push($attendee, $required);
        }
        for ($i = 0; $i < count($optionalNames); $i++) {
            $optional = [
                "emailAddress" => [
                    "address" => $optionalMails[$i],
                    "name" => $optionalNames[$i]
                ],
            "type" => "optional"
            ];
            array_push($attendee, $optional);
        }
        
        //for test
        /*
        $attendee = [[
            "emailAddress" => [
                "address" => "intern2@topc.us",
                "name" => "Takumi Makara"
            ],
            "type" => "required"
        ]];
        */

        //get token
        $tokens = $this->execPostCurl($preparerMails[0]);
        $access_token = $tokens["access_token"];
        $refresh_token = $tokens["refresh_token"];

        //change time
        //$start = $this->changeTime($start_time);
        //$end = $this->changeTime($end_time);
        $start = $start_time;
        $end = $end_time;


        $headers = array(
            "Authorization: Bearer ". $access_token,
            "Content-Type: application/json" 
        );

        $params = [
            "subject" => $title,
            "body"=> [
                "contentType"=> "HTML",
                "content"=> $memo
            ],
            "start"=> [
                "dateTime"=> $start,
                "timeZone"=> "Pacific Standard Time"
            ],
            "end"=> [
                "dateTime"=> $end,
                "timeZone"=> "Pacific Standard Time"
            ],
            "location"=> [
                "displayName"=> $location
            ],
            "attendees"=> $attendee,
        ];

        $url = "https://graph.microsoft.com/v1.0/me/events/". $event_id;

        /*$conn = curl_init();
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($conn, CURLOPT_POSTFIELDS, json_encode($params));

        $res = curl_exec($conn);
        curl_close($conn);
        $ary = json_decode($res, true);*/        
        //$event_id = $ary["id"];        

        $mh = curl_multi_init();
        // URLをキーとして、複数のCurlハンドルを入れて保持する配列
        $ch_list = array();
        $ch_list[0] = curl_init();

        curl_setopt($ch_list[0], CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($ch_list[0], CURLOPT_URL, $url);
        curl_setopt($ch_list[0], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_list[0], CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_list[0], CURLOPT_POSTFIELDS, json_encode($params));
        curl_multi_add_handle($mh, $ch_list[0]);

        $running = null;        
        do {
            curl_multi_exec($mh, $running);
        } while ( $running );

        //結果
        $results[0] = curl_getinfo($ch_list[0]);
        $results[0]["content"] = curl_multi_getcontent($ch_list[0]);
        
        //close
        curl_multi_remove_handle($mh, $ch_list[0]);
        curl_close($ch_list[0]);    
        
        //後処理
        $ary = json_decode($results[0]["content"], true);        
        //$event_id = $ary["id"];

        if(!isset($ary["id"])){
            //event_idが無い場合、手動でoutlookのカレンダーを消されたということなので、新規追加
            $event_id = $this->saveCalendar($item["client_id"], $item["project_id"], $task, $requestor, $preparer_id, $optional_id, $start, $end, $location, $memo);
            //to do listのevent_id update
            $todoListObj = ToDoList::where([['id', '=', $toDoListId]]);
            $updateItem = [                
                "event_id" => $event_id
            ];
            $todoListObj->update($updateItem);

        }else{
            $event_id = $ary["id"];        
        }

        return $event_id;            
    }

    function console_log($text) {
        echo '<script>';
        echo 'console.log('.json_encode($text).')';
        echo '</script>';
    }

    function getEmailTitle($client,$project,$task){
        $title = "[TD] " . $client." ".$project." ".$task;
        return $title;
    }

    function getAccessCode(Request $request){
        $access_code = $_GET["code"];
        $token = "";

        return view("master.access_code",compact("access_code","token"));
    }

    function saveAccessCode(Request $request){
        $access_code = $request->access_code;
        $token = "";
        
        $url = "https://login.microsoftonline.com/bdbb5cd5-dc5a-4c19-8206-0dfae28eca8e/oauth2/v2.0/token";

        $headers = array("Content-Length: 10000");

        $params = [
            "Content-Type: application/x-www-form-urlencoded",            
            "client_id" => "0c331368-cc2c-4c80-b73e-97f26f3adad7",
            "scope" => "Calendars.ReadWrite offline_access",
            "code" => $access_code,
            "state" => "12345",
            "redirect_uri" => "http://localhost:8000/master/access_code",
            "grant_type" => "authorization_code",
            "client_secret" => "MDJ8Q~yOXLv270V.w7MApwUNzmPHvIquZhtDnawz",
        ];

        $conn = curl_init();
        curl_setopt($conn, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($conn, CURLOPT_URL, $url);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($conn, CURLOPT_HTTPHEADER, $headers);    
        
        $res = curl_exec($conn);

        $info = curl_getinfo($conn);
        $errno = curl_errno($conn);
        $error = curl_error($conn);
        $str = curl_strerror($errno);
        curl_close($conn);
        var_dump($errno);
        var_dump($error);
        var_dump($info);
        var_dump($str);
        var_dump($res);

        return view("master.access_code",compact("access_code","token"));
    }
}

//=======================================================================