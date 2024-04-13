<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Event\SendEmail;
use Laravel\Passport\Token;
use App\Traits\ResponseTrait;
use App\Services\AdminService;
use App\Services\AuthDataService;
use App\Http\Requests\CodeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\AuthInformationService;
use App\Http\Requests\Auth\User\EditRequest;
use App\Http\Requests\Auth\User\EmailRequest;
use App\Http\Requests\Auth\User\LoginRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\User\RegisterRequest;
use App\Http\Requests\Auth\User\UserEditRequest;
use App\Http\Requests\Auth\User\UserEmailRequest;
use App\Http\Requests\Auth\User\UserLoginRequest;
use App\Http\Requests\Auth\User\UserRegisterRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthenticateUserController extends Controller
{
    use ResponseTrait , ValidatesRequests;
    

    private AuthDataService $authDataService;

    public function __construct(AuthDataService $authDataService)
    {
        $this->authDataService = $authDataService;
    }
    public function sendCode(EmailRequest $request)
    {
        $email = $request->validated();

        User::create($email);

        $code = RandomCode();

        event(new SendEmail($email , $code));

        Cache::forever('email', $email);
        Cache::put('code', $code , now()->addHour());

        return $this->SendResponse(response::HTTP_CREATED , 'email sended successfully');
    }
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $registrationData = $this->authDataService->handleData($validatedData);

        User::currentEmail()->update($registrationData);

        $admin = User::currentEmail()->first('id');
        $token = userToken($admin);
        
        return $this->SendResponse(response::HTTP_CREATED , 'successful registeration' , ['token' => $token]);
    }

    public function login(LoginRequest $request)
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

    public function editInformation(EditRequest $request)
    {
        $validatedData = $request->validated();
        
        $updates = $this->authDataService->handleData($validatedData);

        User::currentEmail()->update($updates);

        return $this->SendResponse(response::HTTP_OK , 'data updated succussfully');
    }
}
