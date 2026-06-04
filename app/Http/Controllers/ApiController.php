<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function getData()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'name' => 'John Doe',
                'email' => 'john@example.com'
            ]
        ], 200)
            ->header('Content-Type', 'application/json');
    }
}
