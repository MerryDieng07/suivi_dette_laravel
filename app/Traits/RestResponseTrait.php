<?php

namespace App\Traits;

use App\Enums\StateEnum;
use Illuminate\Http\JsonResponse;

trait RestResponseTrait
{
    /**
     * Format and return a JSON response.
     *
     * @param mixed $data
     * @param StateEnum $status
     * @return JsonResponse
     */
    public function sendResponse($data, StateEnum $status = StateEnum::SUCCESS): JsonResponse
    {
        $statusCode = $status === StateEnum::SUCCESS ? 200 : 500; // You can adjust the status code mappings as needed

        return response()->json([
            'status' => $status->value,
            'data' => $data,
            'message' => $status === StateEnum::SUCCESS ? 'Success' : 'Error occurred',
        ], $statusCode);
    }

    /**
     * Format and return a JSON error response.
     *
     * @param string $message
     * @param StateEnum $status
     * @return JsonResponse
     */
    public function sendErrorResponse(string $message, StateEnum $status): JsonResponse
    {
        $statusCode = $status === StateEnum::ECHEC ? 500 : 400; // Adjust mappings if necessary

        return response()->json([
            'status' => $status->value,
            'message' => $message,
        ], $statusCode);
    }
}
