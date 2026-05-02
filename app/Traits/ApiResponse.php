<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($data = null, ?string $message = null, int $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    protected function created($data = null, ?string $message = null)
    {
        return $this->success($data, $message, 201);
    }

    protected function noContent()
    {
        return response()->json(null, 204);
    }

    protected function error(string $message, int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    protected function unauthorized(?string $message = null)
    {
        return $this->error($message ?? trans('auth.unauthorized'), 401);
    }

    protected function forbidden(?string $message = null)
    {
        return $this->error($message ?? trans('auth.unauthorized'), 403);
    }

    protected function notFound(?string $message = null)
    {
        return $this->error($message ?? 'Not found', 404);
    }
}
