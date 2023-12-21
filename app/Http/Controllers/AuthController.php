<?php // testbranch comment

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Owner;
use App\Mail\SendMail;
use App\Mail\RegisterMail;
use App\Http\Controllers\DB;
use Illuminate\Http\Request;
use App\Models\UserResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthController extends Controller
{
    use ResponseTrait;
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required' , 'unique:users,username'],
            'email' => ['required' , 'unique:users,email' , 'email'],
            'mobile' => ['required' , 'unique:users,mobile' , 'min:10' , 'max:10'],
            'password' => ['required' , 'min:8'],
            'confirm_password' => ['required' , 'same:password'],
            'role' => ['required'],
        ],
        [
            'username.required' => 'this field is required',
            'username.unique' => 'this username is not availabvle',
            'mobile.required' => 'this field is required',
            'password.required' => 'this field is required',
            'confirm_password.required' => 'this field is required',
            'confirm_password.same' => 'the passwords are not matching',
        ]
        
        
    );
        $register_data = User::create($request->all());

        $token = $register_data->createToken("api")->plainTextToken;

        $data = [];
        $data['data'] = $register_data;
        $data['token'] = $token;
        
        return $this->SendResponse($data , 201 , 'user created successfully');
    }
    public function login(Request $request)
    {
        $request->validate([ //branch comment
            'mobile' => ['required' , 'exists:users,mobile'],
            'password' => ['required']
        ]);
        if(!Auth::attempt($request->only(['mobile' , 'password'])))
        {
            return $this->SendError(401 , 'check your password');
        }

        $user_data = User::query()->where('mobile' , '=' , $request['mobile'])->first();
        $token = $user_data->createToken('api')->plainTextToken;

        $data = [];
        $data['data'] = $user_data;
        $data['token'] = $token;
        return $this->SendResponse($data , 201 , 'logged in successfully');

    }
    public function logout() 
    {
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return $this->SendResponse(null , 201 , 'logged out successfully');
    }
    public function ownerRegister(Request $request)
    {
        $data=$request->validate([
            'email' => ['required' , 'email']
        ]);
        $data['code'] = mt_rand(100000 , 999999);
        Owner::query()->create($data); 

        Mail::to($request['email'])->send(new RegisterMail($data['code'])); 

        return $this->SendResponse(null , 201 , 'code has been sent successfully');
    }
    public function ownerCode(Request $request)
    {
        $request->validate([
            'code' => ['required' , 'exists:owners,code']
         ],
         [
            'code.exist' => 'the code is incorrect'
         ]
        );
        return $this->SendResponse(null , 201 , 'correct code');
    }
    //not usre about it:resend function
}
