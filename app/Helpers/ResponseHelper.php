<?php
namespace App\Helpers;

class ResponseHelper
{
    public static function sendCreated($data, $message)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message
        ], 201);
    }

    public static function sendOk($data, $message)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message
        ], 200);
    }

    public static function sendServerError($message)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message
        ], 500);
    }
}
