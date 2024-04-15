<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Event\SendEmail;
use Laravel\Passport\Token;
use App\Traits\ResponseTrait;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\Admin\EditRequest;
use App\Http\Requests\Auth\Admin\EmailRequest;
use App\Http\Requests\Auth\Admin\LoginRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\Admin\RegisterRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthenticateAdminController extends Controller
{
    use ResponseTrait , ValidatesRequests;

    private AuthService $authService;
 
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function sendCode(EmailRequest $request)
    {
        $email = $request->validated();

        Admin::create($email);

        $code = RandomCode();

        event(new SendEmail($email , $code));

        Cache::forever('email', $email);
        Cache::put('code', $code , now()->addHour());

        return $this->SendResponse(response::HTTP_CREATED , 'email sended successfully');
    }
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $registrationData = $this->authService->handleData($validatedData);

        Admin::currentEmail()->update($registrationData);

        $admin = Admin::currentEmail()->first('id');
        $token = adminToken($admin);
        
        return $this->SendResponse(response::HTTP_CREATED , 'successful registeration' , ['token' => $token]);
    }

    public function login(LoginRequest $request)
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

    public function editInformation(EditRequest $request)
    {
        $validatedData = $request->validated();
        
        $updates = $this->authService->handleData($validatedData);

        Admin::currentEmail()->update($updates);

        return $this->SendResponse(response::HTTP_OK , 'data updated succussfully');
    }
}
