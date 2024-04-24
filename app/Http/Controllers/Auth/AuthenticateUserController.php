<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Event\SendEmail;
use Laravel\Passport\Token;
use App\Traits\ResponseTrait;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\User\EditRequest;
use App\Http\Requests\Auth\User\EmailRequest;
use App\Http\Requests\Auth\User\LoginRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\User\RegisterRequest;
use App\Services\OrderService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Ramsey\Uuid\Codec\OrderedTimeCodec;

class AuthenticateUserController extends Controller
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

        User::create($email);

        $code = RandomCode();

        event(new SendEmail($email , $code));

        Cache::forever('user_email', $email);
        Cache::put('code', $code , now()->addHour());

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'email sended successfully');
    }
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $registrationData = $this->authService->handleData($validatedData);

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

            Cache::forever('user_email' , $validatedData['email']);
            
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
        
        $updates = $this->authService->handleData($validatedData);

        User::currentEmail()->update($updates);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'data updated succussfully');
    }
}
