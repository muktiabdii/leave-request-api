<?php

namespace App\Helper;

class ApiResponse
{
    public static function success($data = null, $message = "Success", $code = 200, $meta = null)
    {
        return response()->json([
            "status" => "success",
            "message" => $message,
            "data" => $data,
            "meta" => $meta
        ], $code);
    }

    public static function error($message = "Error", $code = 400, $errors = null)
    {
        return response()->json([
            "status" => "error",
            "message" => $message,
            "errors" => $errors,
            "meta" => null
        ], $code);
    }
}