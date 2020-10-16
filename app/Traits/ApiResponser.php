<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
    protected function errorResponse($message, $code)
    {
        return response()->json(["error" => $message, "code" => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse([$collection], $code);
    }

    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        return $this->successResponse([$instance], $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(["data" => $message], $code);
    }
}
