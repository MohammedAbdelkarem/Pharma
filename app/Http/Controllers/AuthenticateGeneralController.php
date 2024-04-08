<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CodeRequest;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
class AuthenticateGeneralController extends Controller
{
    use ResponseTrait;
    
    public function checkCode(CodeRequest $request)
    {
        $validatedCode = $request->validated()['code'];

        $defaultCode = Cache::get('code');

        if(!$defaultCode)//fix this about the cache and the expiered code(when the code expiered it will be null in the cache or it will removed at all from the cache?)
        {
            return $this->SendResponse(response::HTTP_GONE , 'expired code');
        }
        if($validatedCode == $defaultCode)
        {
            return $this->SendResponse(response::HTTP_OK , 'correct code');
        }
        dd($validatedCode);
        return $this->SendResponse(response::HTTP_UNPROCESSABLE_ENTITY , 'invalid code');
    }
}
