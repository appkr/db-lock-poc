<?php

namespace App\Http\Controllers;

use DB;
use Exception;
use Illuminate\Http\Response;
use Request;

class HealthController extends Controller
{
    public function __invoke()
    {
        $databaseError = null;
        $statusCode = Response::HTTP_OK;

        try {
            DB::select('SELECT 1');
        } catch (Exception $e) {
            $databaseError = $e->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json([
            'HOST' => gethostname(),
            'APP_ENV' => env('APP_ENV', 'local'),
            'APP_DEBUG' => env('APP_DEBUG', 'true'),
            // TODO @appkr Duplicate. Move APP_VERSION to .env and Write it dynamically
            'APP_VERSION' => trim(exec('git log --pretty="%h" -n1 HEAD')),
            'APP_URL' => Request::getHttpHost(),
            'DATABASE' => $databaseError ?: 'OKAY',
        ], $statusCode);
    }
}