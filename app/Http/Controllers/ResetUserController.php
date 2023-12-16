<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserResetPassword;
use Illuminate\Support\Facades\Mail;

class ResetUserController extends Controller
{
    use ResponseTrait;
    public $email = null; 
    public function forgotPassword(Request $request) // recieve the email
    {
        $data = $request->validate([
            'email' => ['required' , 'exists:users,email'],
        ]);
        UserResetPassword::query()->where('email' , $request['email'])->delete(); //delete the previous emails and codes from the user-reset_passwords table if it's exist

        $this->email = $request['email'];

        $data['code'] = mt_rand(100000 , 999999); // generate the code 

        UserResetPassword::query()->create($data); // add the email and the code to the mentioned above table

        Mail::to($request['email'])->send($data['code']); // send the code to the email

        return $this->SendResponse(null , 201 , 'verification code has been sent succussfully');
    }
    public function checkCode(Request $request) // check the code if correct
    {
        $request->validate([
            'code' => ['required' , 'exists:user_reset_passwords,code']
        ]);

        $verify_data = UserResetPassword::query()->firstWhere('code' , $request['code']); // get the row where we store the email and the code

        if($verify_data['created_at'] > now()->addHour()) // if one hour passed then this code is unacceptable
        {
            $verify_data->delete();
            return $this->SendError(422 , 'expired code');
        }

        return $this->SendResponse($verify_data , 201 , 'correct code');
    }
    public function resetPassword(Request $request) 
    {
        $data = $request->validate([
            'password' => ['required' , 'min:8'],
            'confirm_password' => ['required' , 'same:password'],
        ]);
        
        $user = User::query()->firstWhere('email' , $this->email); //get the old data of the user form users table
        $user->update([
            'password' => bcrypt($data['password']) //updating the password
        ]);

        return $this->SendResponse(null , 201 , 'password has been reset successfully');

    }
}
