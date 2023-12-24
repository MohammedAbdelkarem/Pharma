<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResetPasswordResource;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserResetPassword;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\Global_;

class ResetUserController extends Controller
{
    use ResponseTrait;
    public static $email="oo";
    public function forgotPassword(Request $request) // recieve the email
    {
        $data = $request->validate([
            'email' => ['required' , 'exists:users,email'],
        ],
        [
            'email.exists' => 'the email is not exist',
        ]
    );


        $data['code'] = mt_rand(100000 , 999999); // generate the code 

        ResetUserController::$email = $request['email'];

        $e =  ResetUserController::$email;

        User::query()->where('email' , $request['email'])->update($data); // add the email and the code to the mentioned above table

        Mail::to($request['email'])->send(new SendMail($data['code'])); // send the code to the email

        return $this->SendResponse(null , 201 , 'verification code has been sent succussfully');
    }
    public function checkCode(Request $request) // check the code if correct
    {
        $request->validate([
            'code' => ['required' , 'exists:users,code']
        ],
        [
            'code.exist' => 'invalid code'
        ]
    );

        $verify_data = User::query()->firstWhere('code' , $request['code']); // get the row where we store the email and the code

        if($verify_data['updated_at'] > now()->addHour()) // if one hour passed then this code is unacceptable
        {
            $verify_data->delete();
            return $this->SendError(422 , 'expired code');
        }

        return $this->SendResponse(null , 201 , 'correct code');
    }
    public function resetPassword(Request $request) 
    {
        $data = $request->validate([
            'email' => ['required'],
            'password' => ['required' , 'min:8'],
            // 'confirm_password' => ['required' , 'same:password'],
        ]);
        $user = User::query()->where('email' , $request['email'])->update([
            'password' => bcrypt($request['password'])
        ]);
        // $u = User::query()->where('email' , $request['email'])->first();
        return $this->SendResponse(null, 201 , 'password has been reset successfully');
    }
}
