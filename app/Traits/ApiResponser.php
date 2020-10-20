<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

trait ApiResponser
{

    /**
     * Build success response
     * @param string|array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(["data" => $data], $code);
    }

    /**
     * Build error response
     * @param string|array $data
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, $code)
    {
        return response()->json(["error" => $message, "code" => $code], $code);
    }
}
