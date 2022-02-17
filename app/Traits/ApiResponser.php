<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{

    protected function successResponse($message = null, $data = null, $code = 200, $statusCode = 200)
    {
        return response()->json([
            'status' => 'Sucess',
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $statusCode);
    }

    protected function errorResponse($message = null, $errors = null, $code = 200, $data = null, $statusCode = 200)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'errors' => $errors
        ], $statusCode);
    }
    protected function exceptionResponse(
        $data = null,
        $message = 'Something went wrong. Please try again later or contact support',
        $code = 500,
        $statusCode = 500
    ) {
        return response()->json([
            'status' => 'Exception',
            'message' => $message,
            'code' => $code,
            'data' => $data
        ], $statusCode);
    }


    protected function mediaResponse($path)
    {
        $data = ['path' => $path, 'url' => $path];
        return $this->successResponse("Media upload successfully", $data);
    }

    private function pathToUrl($path)
    {
        return env('APP_URL') . '/storage/app/' . $path;
    }
}
