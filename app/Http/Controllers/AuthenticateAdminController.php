<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Event\SendEmail;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Services\AdminService;
use App\Http\Requests\CodeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\AdminEmailRequest;
use App\Http\Requests\AdminRegisterRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdminController extends Controller
{
    use ResponseTrait , ValidatesRequests;

    private AdminService $adminService;
 
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function sendCode(AdminEmailRequest $request)
    {
        $email = $request->validated();
        Admin::create($email);

        $code = RandomCode();

        event(new SendEmail($email , $code));

        Cache::forever('email', $email);
        Cache::put('code', $code , 60);

        return $this->SendResponse(response::HTTP_CREATED);
    }

    public function checkCode(CodeRequest $request)
    {
        $user = $request->validated();
        $default = Cache::get('code');

        if(!$default)
        {
            return $this->SendResponse(response::HTTP_GONE , 'expired code');
        }
        if($user === $default)
        {
            return $this->SendResponse(response::HTTP_OK , 'correct code');
        }
        return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'invalid code');
    }


    public function register(AdminRegisterRequest $request)
    {
        $user = $request->validated();//stopping here , what to put inside the token
        $d = $this->adminService->handleRegistrationData($user , $request);

        Admin::email()->update($d);

        $data = Admin::email()->first();

        $token = adminToken($data);//helper for user and for admin
        $data['token'] = $token;
        // $user_data['token'] = $token;
        //dd($data);

         return $this->SendResponse(response::HTTP_CREATED , null , $data);
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
