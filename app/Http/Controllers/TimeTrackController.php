<?php

namespace App\Http\Controllers;

use App\Client;
use App\Project;
use App\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeTrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //client
        $clientData = Client::orderBy("name", "asc")->get();
        //project
        $projectData = Project::select("project_name")
            ->groupBy('project_name')
            ->orderBy('project_name', 'asc')->get();
        $loginUserInitial = Staff::select("initial")->where([['email', '=', Auth::User()->email]])->first();

        return view('time-tracker.index')
            ->with("client", $clientData)
            ->with("project", $projectData)
            ->with("loginInitial", $loginUserInitial);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
