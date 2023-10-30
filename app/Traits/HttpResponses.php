<?php

namespace App\Traits;

use Illuminate\Support\MessageBag;

trait HttpResponses
{
    public function success(string $message, string|int $status, array|object $data = []) {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data
        ]);
    }

    public function error(string $message, string|int $status, array|MessageBag $errors = [], array $data = []) {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'errors' => $errors,
            'data' => $data
        ]);
    }
}
