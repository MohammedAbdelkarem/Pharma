<?php

namespace App\Http\Controllers\Auth;

use App\Event\SendEmail;
use Laravel\Passport\Token;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User\EditRequest;
use App\Http\Requests\Auth\User\EmailRequest;
use App\Http\Requests\Auth\User\LoginRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\User\RegisterRequest;
use App\Services\UserService;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthenticateUserController extends Controller
{
    use ResponseTrait , ValidatesRequests;

    private UserService $userService;
 
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function sendCode(EmailRequest $request)
    {
        $email = $request->validated();

        $code = RandomCode();

        event(new SendEmail($email , $code));

        $this->userService->createUser($email , $code);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'email sended successfully');
    }
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $this->userService->updateUser($validatedData);

        $token = getUserToken();
        
        return $this->SendResponse(response::HTTP_CREATED , 'successful registeration' , ['token' => $token]);
    }

    public function login(LoginRequest $request)
    {
        if(auth()->guard('user')->attempt($request->only('email' , 'password')))
        {
            $email = $request->validated();

            configUserAuth($email);

            $token = getUserToken();
            
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

        $this->userService->updateUser($validatedData);

        return $this->SendResponse(response::HTTP_NO_CONTENT , 'data updated succussfully');
    }
}
