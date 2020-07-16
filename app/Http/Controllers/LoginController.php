<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\User;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Notifications\SignupActivate;

class LoginController extends Controller
{
    /**
     * Use for send invitation to user 
     *
     * @param  [string] email
     */
    public function login(Request $request)
    {
        
        $request->validate([
            'email' => 'required|email',         
        ]);

        $email = $request->email;
        $user = User::where("email",$email)->first();
        if(!empty($user))
        {    
            $user->active = false;
            $user->login_status = 0;
            $user->activation_token = '';
            $user->save();
        }
        else
        {
            $add = new User();
            $add->name = 'User';
            $add->email = $email;
            $add->password = '123456';
            $add->active = false;
            $add->login_status = 0;
            $add->activation_token = '';
            $add->save();
        }
        $selectedTime = date('Y-m-d h:i:s');
        $endTime = strtotime("+10 minutes", strtotime($selectedTime));
        User::where("email",$email)->update(['activation_token' => Str::random(60),'token_expire_time'=>date('Y-m-d h:i:s', $endTime)]);
        $user = User::where("email",$email)->first();
        Mail::mailer('postmark')->send([], [], function($message) use ($user,$email) {
            $message->to($email)->subject('Laravel Testing Mail with Attachment')->setBody('<a href="'.URL('/confirm/'.$user->activation_token).'">Confirm Mail</a>','text/html');
            $message->from('mn@logixbuilt.com');
        });
        return back();
    }

    /**
     * activate the user
     *
     * @return Customer customer
     */
    public function confirm(Request $request, $token)
    {
        $user = User::where('activation_token', $token)->where('token_expire_time','>',date('Y-m-d h:i:s'))->first();
        if (!$user) 
        {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $userdata = User::where('activation_token', $token)->first();
        $user->active = true;
        $user->activation_token = '';
        $user->login_status = 1;
        $user->uuid = (string) Str::uuid();
        $user->save();
        $tokenResult = $userdata->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $newdata = [
            'access_token' => $tokenResult->accessToken,
            'uuid' => $user->uuid,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];
        return response()->json($newdata);
    }

    /**
     * Display a login user data.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $data = getUserdata($request->header('uuid'));
        if($data != "")
        {
    	   return response()->json($data);
        }
        else
        {
            return redirect('/login');
        }
    }
}
