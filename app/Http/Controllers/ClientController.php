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

//=======================================================================
class ClientController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $keyword = $request->get("search");
        //$perPage = 25;

        if (!empty($keyword)) {
            $client = Client::select("client.id as id","name","fye","vic_status","group_companies","initial")->leftJoin("staff","staff.id","=","client.pic")->where("name", "LIKE", "%$keyword%")->orWhere("Initial", "LIKE", "%$keyword%")->Where("client.id","<>","0")->get();//paginate($perPage);
        } else {
            $client = Client::select("client.id as id","name","fye","vic_status","group_companies","initial")->leftJoin("staff","staff.id","=","client.pic")->Where("client.id","<>","0")->get();//paginate($perPage);
        }
                
        
        return view("master.client.index",compact("client"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {        
        //pic
        $picData = Staff::ActiveStaffOrderByInitial();
        
        return view("master.client.create",compact("picData"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
       
        $requestData = $request->all();

        $maxClientId = Client::orderBy("id","desc")->first()->id;
        
        //$requestData["id"] = $maxClientId + 1; 
        
        $incorporationDate = "";
        if($request->input("incorporation_date") != ""){
            $bArray = explode("/",$request->input("incorporation_date"));
            $incorporationDate = $bArray[2] . "-" . $bArray[0] . "-" . $bArray[1];
        }
        
        $businessStartedDate = "";
        if($request->input("business_started") != ""){
            $bArray = explode("/",$request->input("business_started"));
            $businessStartedDate = $bArray[2] . "-" . $bArray[0] . "-" . $bArray[1];
        }
       
        
        $table = new Client;
        $table->id = $maxClientId + 1; 
        $table->name = $request->input("name");
        $table->fye = $request->input("fye");
        $table->vic_status = $request->input("vic_status");
        $table->group_companies = $request->input("group_companies");
        $table->website = $request->input("website");
        $table->address_us = $request->input("address_us");
        $table->address_jp = $request->input("address_jp");
        $table->mailing_address = $request->input("mailing_address");
        $table->tel1 = $request->input("tel1");
        $table->tel2 = $request->input("tel2");
        $table->tel3 = $request->input("tel3");
        $table->fax = $request->input("fax");
        $table->federal_id = $request->input("federal_id");
        $table->state_id = $request->input("state_id");
        $table->edd_id = $request->input("edd_id");
        $table->note = $request->input("note");
        $table->pic = $request->input("pic");
        $table->nature_of_business = $request->input("nature_of_business");
        $table->incorporation_date = $incorporationDate;
        $table->incorporation_state = $request->input("incorporation_state");
        $table->business_started = $businessStartedDate;
        
        $table->save();
        
        //$queryObj = ContactPerson::where([['client_id', '=', $maxClientId + 1; ]]);
        //$queryObj->delete();
        
        $id = $maxClientId + 1;
        
        for ($contactCnt = 1; $contactCnt < 20; $contactCnt++) {
            if (!isset($_POST["contact_person" . $contactCnt])) {
                break;
            }

            $pTable = new ContactPerson;
            $pTable->client_id = $id;            
            $pTable->person = $_POST["contact_person" . $contactCnt];            
            $pTable->person_jp = $_POST["contact_person_jp" . $contactCnt];
            $pTable->person_title = $_POST["title" . $contactCnt];
            $pTable->tel = $_POST["telephone" . $contactCnt];
            $pTable->mobile_phone = $_POST["cellphone" . $contactCnt];
            $pTable->fax = $_POST["fax" . $contactCnt];
            $pTable->email = $_POST["email" . $contactCnt];            

            $pTable->save();
        }
        
        //us shareholder
        //$queryObj = Shareholders::where([['client_id', '=', $id],["type","=",1]]);
        //$queryObj->delete();
        
        for ($usCnt = 1; $usCnt < 20; $usCnt++) {
            if (!isset($_POST["us_name" . $usCnt])) {
                break;
            }

            $pTable = new Shareholders;
            $pTable->client_id = $id;
            $pTable->type = 1;
            $pTable->name = $_POST["us_name" . $usCnt];
            $pTable->percent = $_POST["us_percent" . $usCnt];

            $pTable->save();
        }
        
        //foreign shareholder
       //$queryObj = Shareholders::where([['client_id', '=', $id],["type","=",2]]);
       // $queryObj->delete();
        
        for ($foreignCnt = 1; $foreignCnt < 20; $foreignCnt++) {
            if (!isset($_POST["foreign_name" . $foreignCnt])) {
                break;
            }

            $pTable = new Shareholders;
            $pTable->client_id = $id;
            $pTable->type = 2;
            $pTable->name = $_POST["foreign_name" . $foreignCnt];
            $pTable->percent = $_POST["foreign_percent" . $foreignCnt];

            $pTable->save();
        }
        
        //officer        
        //$queryObj = Officers::where([['client_id', '=', $id]]);
        //$queryObj->delete();
        
        for ($officerCnt = 1; $officerCnt < 20; $officerCnt++) {
            if (!isset($_POST["officer_name" . $officerCnt])) {
                break;
            }

            $pTable = new Officers;
            $pTable->client_id = $id;
            $pTable->name = $_POST["officer_name" . $officerCnt];
            $pTable->title = $_POST["officer_title" . $officerCnt];

            $pTable->save();
        }
        
        
        return redirect("master/client")->with("flash_message", "client added!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $clien = Client::select("client.id as id","client.*","initial")->leftJoin("staff","staff.id","=","client.pic")->findOrFail($id);
        
        if($clien->incorporation_date != ""){
            $bArray = explode("-",$clien->incorporation_date);
            $clien->incorporation_date = $bArray[1] . "/" . $bArray[2] . "/" . $bArray[0];
        }
        
        if($clien->business_started != ""){
            $bArray = explode("-",$clien->business_started);
            $clien->business_started = $bArray[1] . "/" . $bArray[2] . "/" . $bArray[0];
        }
        
        $contactPerson = ContactPerson::where("client_id","=",$id)->get();
        $usShareholders = Shareholders::where([["client_id","=",$id],["type","=","1"]])->get();
        $foreignShareholders = Shareholders::where([["client_id","=",$id],["type","=","2"]])->get();
        $officers = Officers::where("client_id","=",$id)->get();
        return view("master.client.show", compact("clien","contactPerson","usShareholders","foreignShareholders","officers"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $client = Client::findOrFail($id);
        if($client->incorporation_date != ""){
            $bArray = explode("-",$client->incorporation_date);
            $client->incorporation_date = $bArray[1] . "/" . $bArray[2] . "/" . $bArray[0];
        }
        
        if($client->business_started != ""){
            $bArray = explode("-",$client->business_started);
            $client->business_started = $bArray[1] . "/" . $bArray[2] . "/" . $bArray[0];
        }
               
        //contact person
        $contactPersonList = ContactPerson::where("client_id","=",$id)->get();
        
        //us shareholder
        $usList = Shareholders::where([["client_id","=",$id],["type","=",1]])->get();
        
        //foreign shareholder
        $foreignList = Shareholders::where([["client_id","=",$id],["type","=",2]])->get();
        
        //officer
        $officer = Officers::where("client_id","=",$id)->get();
                
        //pic
        $picData = Staff::ActiveStaffOrderByInitial();

        return view("master.client.edit", compact("client","officer","usList","foreignList","contactPersonList","picData"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id) {
        /*$this->validate($request, [
            "name" => "nullable|max:200", //string('name',200)->nullable()
            "fye" => "nullable|max:5", //string('fye',5)->nullable()
            "vic_status" => "nullable|max:3", //string('vic_status',3)->nullable()
            "group_companies" => "nullable", //integer('group_companies')->nullable()
            "website" => "nullable|max:300", //string('website',300)->nullable()
            "address_us" => "nullable|max:300", //string('address_us',300)->nullable()
            "address_jp" => "nullable|max:300", //string('address_jp',300)->nullable()
            "mailing_address" => "nullable|max:20", //string('mailing_address',20)->nullable()
            "tel1" => "nullable|max:50", //string('tel1',50)->nullable()
            "tel2" => "nullable|max:50", //string('tel2',50)->nullable()
            "tel3" => "nullable|max:50", //string('tel3',50)->nullable()
            "fax" => "nullable|integer", //integer('fax')->nullable()
            "fax" => "nullable|max:50", //string('fax',50)->nullable()
            "federal_id" => "nullable|max:20", //string('federal_id',20)->nullable()
            "state_id" => "nullable|max:20", //string('state_id',20)->nullable()
            "edd_id" => "nullable|max:20", //string('edd_id',20)->nullable()
            "note" => "nullable|max:300", //string('note',300)->nullable()
            "pic" => "nullable|integer", //integer('pic')->nullable()
            "nature_of_business" => "nullable|integer", //integer('nature_of_business')->nullable()
            "incorporation_date" => "nullable|date", //date('incorporation_date')->nullable()
            "incorporation_state" => "nullable|max:50", //string('incorporation_state',50)->nullable()
            "business_started" => "nullable|date", //date('business_started')->nullable()
        ]);*/
        $requestData = $request->all();
       
        $client = Client::where("id","=",$id);
        
        $incorporationDate = "";
        if($request->input("incorporation_date") != ""){
            $bArray = explode("/",$request->input("incorporation_date"));
            $incorporationDate = $bArray[2] . "-" . $bArray[0] . "-" . $bArray[1];
        }
        
        $businessStartedDate = "";
        if($request->input("business_started") != ""){
            $bArray = explode("/",$request->input("business_started"));
            $businessStartedDate = $bArray[2] . "-" . $bArray[0] . "-" . $bArray[1];
        }
        
        $updateItem = [
            "name" => $request->input("name"),
            "fye" => $request->input("fye"),
            "vic_status" => $request->input("vic_status"),
            "group_companies" => $request->input("group_companies"),
            "website" => $request->input("website"),
            "address_us" => $request->input("address_us"),
            "address_jp" => $request->input("address_jp"),
            "mailing_address" => $request->input("mailing_address"),
            "tel1" => $request->input("tel1"),
            "tel2" => $request->input("tel2"),
            "tel3" => $request->input("tel3"),
            "fax" => $request->input("fax"),
            "federal_id" => $request->input("federal_id"),
            "state_id" => $request->input("state_id"),
            "edd_id" => $request->input("edd_id"),
            "note" => $request->input("note"),
            "pic" => $request->input("pic"),
            "nature_of_business" => $request->input("nature_of_business"),
            "incorporation_date" => $incorporationDate,
            "incorporation_state" => $request->input("incorporation_state"),
            "business_started" => $businessStartedDate,
        ];

        $client->update($updateItem);
        
        //contact person    
        $queryObj = ContactPerson::where([['client_id', '=', $id]]);
        $queryObj->delete();
        
        for ($contactCnt = 1; $contactCnt < 20; $contactCnt++) {
            if (!isset($_POST["contact_person" . $contactCnt])) {
                break;
            }

            $pTable = new ContactPerson;
            $pTable->client_id = $id;            
            $pTable->person = $_POST["contact_person" . $contactCnt];            
            $pTable->person_jp = $_POST["contact_person_jp" . $contactCnt];
            $pTable->person_title = $_POST["title" . $contactCnt];
            $pTable->tel = $_POST["telephone" . $contactCnt];
            $pTable->mobile_phone = $_POST["cellphone" . $contactCnt];
            $pTable->fax = $_POST["fax" . $contactCnt];
            $pTable->email = $_POST["email" . $contactCnt];            

            $pTable->save();
        }
        
        //us shareholder
        $queryObj = Shareholders::where([['client_id', '=', $id],["type","=",1]]);
        $queryObj->delete();
        
        for ($usCnt = 1; $usCnt < 20; $usCnt++) {
            if (!isset($_POST["us_name" . $usCnt])) {
                break;
            }

            $pTable = new Shareholders;
            $pTable->client_id = $id;
            $pTable->type = 1;
            $pTable->name = $_POST["us_name" . $usCnt];
            $pTable->percent = $_POST["us_percent" . $usCnt];

            $pTable->save();
        }
        
        //foreign shareholder
        $queryObj = Shareholders::where([['client_id', '=', $id],["type","=",2]]);
        $queryObj->delete();
        
        for ($foreignCnt = 1; $foreignCnt < 20; $foreignCnt++) {
            if (!isset($_POST["foreign_name" . $foreignCnt])) {
                break;
            }

            $pTable = new Shareholders;
            $pTable->client_id = $id;
            $pTable->type = 2;
            $pTable->name = $_POST["foreign_name" . $foreignCnt];
            $pTable->percent = $_POST["foreign_percent" . $foreignCnt];

            $pTable->save();
        }
        
        //officer        
        $queryObj = Officers::where([['client_id', '=', $id]]);
        $queryObj->delete();
        
        for ($officerCnt = 1; $officerCnt < 20; $officerCnt++) {
            if (!isset($_POST["officer_name" . $officerCnt])) {
                break;
            }

            $pTable = new Officers;
            $pTable->client_id = $id;
            $pTable->name = $_POST["officer_name" . $officerCnt];
            $pTable->title = $_POST["officer_title" . $officerCnt];

            $pTable->save();
        }

        return redirect("master/client")->with("flash_message", "client updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Client::destroy($id);
        $delContactPerson = ContactPerson::where("client_id","=",$id)->delete();
        $delShareholder = Shareholders::where("client_id","=",$id)->delete();
        $delOffer = Officers::where("client_id","=",$id)->delete();

        return redirect("master/client")->with("flash_message", "client deleted!");
    }

}

//=======================================================================
    
    