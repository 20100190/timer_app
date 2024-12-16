use Twilio\Rest\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    

    public function sendSMS(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'message' => 'required'
        ]);

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $twilioNumber = config('services.twilio.from');

        $client = new Client($sid, $token);
        $client->messages->create(
            $request->input('phone'),
            array(
                'from' => $twilioNumber,
                'body' => $request->input('message')
            )
        );

        return redirect()->back()->with('success', 'Message sent successfully.');
    }
}

