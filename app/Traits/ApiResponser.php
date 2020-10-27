<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

trait ApiResponser
{

    /**
     * Build success response
     * @param string|array $data
     * @param int $code
     * @param null $tokenData
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $code = Response::HTTP_OK, $tokenData = null)
    {
        return isset($tokenData) ? response()->json(["data" => $data, "tokenData" => $tokenData], $code) : response()->json(["data" => $data], $code);
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

    protected function showAll(Collection $collection, $code = 200){
        if ($collection->isEmpty()) {
            return $this->successResponse(["data" => $collection], $code);
        }

        $collection = $this->filterData($collection);
        return $this->successResponse([$collection], $code);
    }

    /**
     * @param Collection $collection
     * @param $transformer
     * @return Collection|\Ramsey\Collection\CollectionInterface
     */
    protected function filterData(Collection $collection)
    {
        foreach (request()->query() as $query => $value) {
            if (isset( $value)) {
                $collection = $collection->where($query, $value);
            }
        }
        return $collection;
    }
}
