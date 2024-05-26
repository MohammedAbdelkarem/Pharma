<?php

namespace App\Http\Controllers\Auth;

use App\Event\SendEmail;
use Laravel\Passport\Token;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Admin\{EditRequest , EmailRequest , LoginRequest , RegisterRequest};
use Symfony\Component\HttpFoundation\Response;
use App\Services\AdminService;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthenticateAdminController extends Controller
{
    use ResponseTrait , ValidatesRequests;

    private AdminService $adminService;
 
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function sendCode(EmailRequest $request)
    {
        $email = $request->validated();

        $code = RandomCode();

        event(new SendEmail($email , $code));

        $this->adminService->createAdmin($email , $code);

        return $this->SendResponse(response::HTTP_OK , 'email sended successfully');
    }
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();

        $this->adminService->updateAdmin($validatedData);

        $token = getAdminToken();
        
        return $this->SendResponse(response::HTTP_CREATED , 'successful registeration' , ['token' => $token]);
    }

    public function login(LoginRequest $request)
    {
        if(auth()->guard('admin')->attempt($request->only('email' , 'password')))
        {
            $email = $request->validated();

            configAdminAuth($email);

            $token = getAdminToken();
            
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

        $this->adminService->updateAdmin($validatedData);

        return $this->SendResponse(response::HTTP_OK , 'data updated succussfully');
    }
}
