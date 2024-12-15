<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\User;

class SMSVarifyController extends Controller
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
        return view('sms_varify');
    }

    public function sendSMS(Request $request)
    {
       
        $sid = "ACd9aaad21f58e9c03d30848010fe66cb2";
        $token = "442a405d5adf156355ca00eccef025f6";
        $twilioNumber = "+13203028600";

        //電話番号取得
        $email = $request->email;
        $phoneNumber = "";
        $userObj = User::where("email","=",$email);
        
        if(!$userObj->exists()){
            return "error";
        }

        $phoneNumber = $userObj->first()->phone_number;

        // 認証コードを生成する
        $authCode = rand(100000, 999999);
        
        $client = new Client($sid, $token);
        $client->messages->create(
            //"+819054725987",
            $phoneNumber,
            array(
                'from' => $twilioNumber,
                'body' => "Authentication code for Budget Web Form is " . $authCode . "."
            )
        );

        //userの認証コードを更新
        $userData = array(            
            'verification_code' => $authCode,
        );
        $userObj->update($userData);

        //return redirect()->back()->with('success', 'Message sent successfully.');
        return view('sms_varify');
    }
}
