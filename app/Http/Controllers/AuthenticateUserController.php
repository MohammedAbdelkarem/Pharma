<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Event\SendEmail;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\CodeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\UserEmailRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Traits\ResponseTrait;

class AuthenticateUserController extends Controller
{
    use ResponseTrait , ValidatesRequests;
    

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function sendCode(UserEmailRequest $request)
    {
        $email = $request->validated();

        User::create($email);

        $code = RandomCode();

        event(new SendEmail($email , $code));

        Cache::forever('email', $email);
        Cache::put('code', $code , now()->addHour());

        return $this->SendResponse(response::HTTP_CREATED , 'email sended successfully');
    }
    public function register(UserRegisterRequest $request)
    {
        $validatedData = $request->validated();

        $registrationData = $this->userService->handleRegistrationData($validatedData , $request);

        User::currentEmail()->update($registrationData);

        $admin = User::currentEmail()->first('id');
        $token = userToken($admin);
        
        return $this->SendResponse(response::HTTP_CREATED , 'successful registeration' , ['token' => $token]);
    }

    public function login(UserLoginRequest $request)
    {
        if(auth()->guard('user')->attempt($request->only('email' , 'password')))
        {
            $validatedData = $request->validated();

            Cache::forever('email' , $validatedData['email']);
            
            config(['auth.guards.user_api.provider' => 'user']);

            $admin = User::currentEmail()->first('id');

            $token = userToken($admin);
            
            return $this->SendResponse(response::HTTP_OK , 'logged in successfully' , ['token' => $token]);
        }
        return $this->SendResponse(response::HTTP_UNAUTHORIZED , 'invalid password');
    }

    public function logout()
    {
        Token::userId()->delete();

         return $this->SendResponse(response::HTTP_OK , 'logged out successfully');
    }
}
