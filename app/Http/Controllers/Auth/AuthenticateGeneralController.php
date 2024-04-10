<?php

namespace App\Http\Controllers\Auth;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Auth\CodeRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateGeneralController extends Controller
{
    use ResponseTrait;
    
    public function checkCode(CodeRequest $request)
    {
        $validatedCode = $request->validated()['code'];

        $defaultCode = Cache::get('code');

        if(!$defaultCode)
        {
            return $this->SendResponse(response::HTTP_GONE , 'expired code');
        }
        if($validatedCode == $defaultCode)
        {
            return $this->SendResponse(response::HTTP_OK , 'correct code');
        }
        // dd($validatedCode);
        return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'invalid code');
    }
}
