<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data = [], $message = 'success', $status = 200): JsonResponse
    {
        return response()
            ->json(
                [
                    'status'  => true,
                    'message' => $message,
                    'data'    => $data
                ],
                $status
            );
    }

    public function error($message = 'error', $status = 500): JsonResponse
    {
        return response()
            ->json(
                [
                    'status'  => false,
                    'message' => $message,
                ],
                $status
            );
    }
}
