<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function welcome()
    {
        return response()->json([
            'message' => 'Welcome to db-lock-poc api.',
            'version' => 'v1',
            'endpoints' => [
                'products' => '/api/v1/products',
                'reviews' => '/api/v1/products/{product}/reviews',
            ]
        ]);
    }
}