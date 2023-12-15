<?php

namespace App\Http\Controllers;

trait ResponseTrait
{
    public function SendResponse($data = null , $status = null , $message = null)
    {
        $arr = [
            'data' => $data,
            'status' => $status,
            'message' => $message
        ];
        return response($arr);
    }
    public function SendError($status , $message)
    {
        $arr = [
            'data' => null,
            'status' => $status,
            'message' => $message
        ];
        return response($arr);
    }
}

