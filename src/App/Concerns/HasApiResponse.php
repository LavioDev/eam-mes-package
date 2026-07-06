<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait HasApiResponse
{
    /**
     * Return a success JSON response.
     */
    public function successResponse(mixed $data = null, string $message = 'Success', int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return an error JSON response.
     */
    public function errorResponse(string $message = 'Error', int $code = Response::HTTP_BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
