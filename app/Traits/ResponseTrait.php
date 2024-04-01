<?php

namespace App\Http\Controllers;

trait ResponseTrait
{
    public function SendResponse($data = null , $status = null , $message = null)
    {
        $array = [
            'data' => $data,
            'status' => $status,
            'message' => $message
        ];
        return response($array);
    }
}

