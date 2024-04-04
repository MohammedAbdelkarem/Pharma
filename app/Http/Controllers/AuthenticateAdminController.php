<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminEmailRequest;
use App\Models\Admin;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdminController extends Controller
{
    use ResponseTrait;

    public function Email(AdminEmailRequest $request)
    
    {
    }
    public function Register(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'email' => ['email' , 'required' , 'unique:admins'],
            'mobile' => ['required'],
            'password' => [ 'required'],
        ]); // request 

        $data = $request->all();
        $data['password'] = bcrypt($data['password']); // helper

        $user_data = Admin::query()->create($data);//services
        $token = $user_data->createToken('MyApp' , ['admin'])->accessToken;//helper for user and for admin
        $user_data['token'] = $token;

        return $this->SendResponse($user_data , response::HTTP_CREATED);
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:admins,email|email',
            'password' => 'required',
        ]);
        $credentials = $request->only(['email' , 'password']);

        if(auth()->guard('admin')->attempt($request->only('email' , 'password')))
        {
            config(['auth.guards.admin_api.provider' => 'admin']);
            $user_data = Admin::query()->where('email' , $request->email)->first();

            $token = $user_data->createToken('MyApp' , ['admin'])->accessToken;
            $user_data['token'] = $token;
            return $this->SendResponse($user_data , response::HTTP_OK);
        }
        // dd(auth()->guard('user'));
        return $this->SendResponse(null,response::HTTP_UNAUTHORIZED , 'unauth');
    }

    public function Logout()
    {
        $userId = Auth::guard('admin_api')->user()->id;
        Token::where('user_id', $userId)->delete();
        return $this->SendResponse(null , response::HTTP_OK , 'loggedout successfully');
    }
}
