<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
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
        Cache::put('code', $code , now()->addHour());

        return $this->SendResponse(response::HTTP_CREATED , 'email sended successfully');
    }
    public function register(AdminRegisterRequest $request)
    {
        $validatedData = $request->validated();

        $registrationData = $this->adminService->handleRegistrationData($validatedData , $request);

        Admin::currentEmail()->update($registrationData);

        $admin = Admin::currentEmail()->first('id');
        $token = adminToken($admin);
        
        return $this->SendResponse(response::HTTP_CREATED , 'successful registeration' , ['token' => $token]);
    }

    public function login(AdminLoginRequest $request)
    {
        if(auth()->guard('admin')->attempt($request->only('email' , 'password')))
        {
            $validatedData = $request->validated();

            Cache::forever('email' , $validatedData['email']);

            config(['auth.guards.admin_api.provider' => 'admin']);

            $admin = Admin::currentEmail()->first('id');

            $token = adminToken($admin);
            
            return $this->SendResponse(response::HTTP_OK , 'logged in successfully' , ['token' => $token]);
        }
        return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'invalid password');
    }

    public function logout()
    {
        Token::adminId()->delete();

         return $this->SendResponse(response::HTTP_OK , 'logged out successfully');
    }
}
