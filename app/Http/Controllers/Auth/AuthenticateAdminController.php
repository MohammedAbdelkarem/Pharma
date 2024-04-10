<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Event\SendEmail;
use Laravel\Passport\Token;
use GuzzleHttp\Psr7\Request;
use App\Traits\ResponseTrait;
use App\Services\AdminService;
use App\Services\AuthDataService;
use App\Http\Requests\CodeRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Admin\AdminEditRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\AuthInformationService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Auth\Admin\AdminEmailRequest;
use App\Http\Requests\Auth\Admin\AdminLoginRequest;
use App\Http\Requests\Auth\Admin\AdminRegisterRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AuthenticateAdminController extends Controller
{
    use ResponseTrait , ValidatesRequests;

    private AuthDataService $authDataService;
 
    public function __construct(AuthDataService $authDataService)
    {
        $this->authDataService = $authDataService;
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

        $registrationData = $this->authDataService->handleData($validatedData);

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

    public function editInformation(AdminEditRequest $request)
    {
        $validatedData = $request->validated();
        /*
        stopping here
        complete the updata function for admin and user , use the shared service.
        change the names of the request files.
        */
    }
}
