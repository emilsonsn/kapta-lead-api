<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as Controller;

class BaseController extends Controller
{
    protected function response($result)
    {
        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'] ?? null,
            'data' => $result['data'] ?? null,
            'error' => $result['error'] ?? null
        ], $result['statusCode'] ?? 200);
    }
}
