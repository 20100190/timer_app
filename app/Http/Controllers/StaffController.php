<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validate;
use DB;
use App\Staff;

//=======================================================================
class StaffController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $keyword = $request->get("search");
        $perPage = 25;

        if (!empty($keyword)) {
            $clien = Staff::where("id", "LIKE", "%$keyword%")->orWhere("employee_no", "LIKE", "%$keyword%")->orWhere("first_name", "LIKE", "%$keyword%")->orWhere("last_name", "LIKE", "%$keyword%")->orWhere("initial", "LIKE", "%$keyword%")->orWhere("department", "LIKE", "%$keyword%")->orWhere("title", "LIKE", "%$keyword%")->orWhere("billing_title", "LIKE", "%$keyword%")->orWhere("rate", "LIKE", "%$keyword%")->orWhere("extension", "LIKE", "%$keyword%")->orWhere("email", "LIKE", "%$keyword%")->orWhere("cell_phone", "LIKE", "%$keyword%")->orWhere("status", "LIKE", "%$keyword%")->orWhere("default_role", "LIKE", "%$keyword%")->paginate($perPage);
        } else {
            $clien = Staff::paginate($perPage);
        }
        
        //編集権限
        $isEdit = Staff::HaveEditAuthority(Auth::User()->email);
        
        return view("master.staff.index",compact("clien","isEdit"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        return view("master.staff.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request) {
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

        $maxClientId = Staff::max('id');

        $requestData["id"] = $maxClientId + 1;

        Staff::create($requestData);

        return redirect("master/staff")->with("flash_message", "staff added!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id) {
        $clien = Staff::findOrFail($id);
        return view("master.staff.show", compact("clien"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $clien = Staff::findOrFail($id);

        return view("master.staff.edit", compact("clien"));
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

        $clien = Staff::findOrFail($id);
        $clien->update($requestData);

        return redirect("master/staff")->with("flash_message", "staff updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id) {
        Staff::destroy($id);

        return redirect("master/staff")->with("flash_message", "staff deleted!");
    }

}

//=======================================================================
    
    