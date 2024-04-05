<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Http\Requests\CodeRequest;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserEmailRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUserController extends Controller
{
    use ResponseTrait;
    
    public function SendCode(UserEmailRequest $request)
    {
        //enter the email
        //send email to the user which has the verificatoin code
        //store email in the cahce
    }

    public function CheckCode(CodeRequest $request)
    {
        //check if the code the same code of the email in the cache in the database and return a response
    }

    public function Register(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'email' => ['email' , 'required' , 'unique:admins'],
            'mobile' => ['required'],
            'password' => [ 'required'],
        ]);

        $data = $request->all();
        $data['passowrd'] = bcrypt($data['password']);

        $user_data = User::query()->create($data);
        $token = $user_data->createToken('MyApp' , ['user'])->accessToken;
        $user_data['token'] = $token;

        return $this->SendResponse($user_data , response::HTTP_CREATED);
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email|email',
            'password' => 'required',
        ]);
        $credentials = $request->only(['email' , 'password']);

        if(auth()->guard('user')->attempt($request->only('email' , 'password')))
        {
            config(['auth.guards.user_api.provider' => 'user']);
            $user_data = User::query()->where('email' , $request->email)->first();

            $token = $user_data->createToken('MyApp' , ['user'])->accessToken;
            $user_data['token'] = $token;
            return $this->SendResponse($user_data , response::HTTP_OK);
        }
        // dd(auth()->guard('user'));
        return $this->SendResponse(null,response::HTTP_UNAUTHORIZED , 'unauth');
    }

    public function Logout()
    {
        $userId = Auth::guard('user_api')->user()->id;
        Token::where('user_id', $userId)->delete();
        return $this->SendResponse(null , response::HTTP_OK , 'loggedout successfully');
    }
}
