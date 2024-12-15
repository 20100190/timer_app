<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectCompareController extends Controller
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
        return view('project_compare');
    }

    public function getData()
    {

        $url = "https://api.harvestapp.com/v2/projects?page=1";
        $headers = array(
            "Authorization: Bearer " . "1811250.pt.FwB6sKeYVYTGxSiARVlWk9eZATp7Jdu4u5eRjyFLv0XDGDs1A2gvTtilegTjoIJ4sCr0uqDOA-rWUGy1SNx4TA",
            "Harvest-Account-ID: "   . "231068"
        );

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_USERAGENT, "MyApp (takahiroy@topc.us)");

        $response = curl_exec($handle);

        if (curl_errno($handle)) {
            print "Error: " . curl_error($handle);
        } else {
            print json_encode(json_decode($response), JSON_PRETTY_PRINT);
            curl_close($handle);
        }
    }
}
