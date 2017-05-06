<?php

namespace App\Http\Controllers;

use DB;
use Request;

class HealthController extends Controller
{
    public function index()
    {
        $dbConnectionOk = DB::connection()->getDatabaseName();

        return response()->json([
            'HOST' => gethostname(),
            'APP_ENV' => env('APP_ENV', 'local'),
            'APP_DEBUG' => env('APP_DEBUG', 'true'),
            'APP_VERSION' => trim(exec('git log --pretty="%h" -n1 HEAD')),
            'APP_URL' => Request::getHttpHost(),
            'DB_CONNECTION' => DB::getDriverName() . ':' . $dbConnectionOk,
        ]);
    }
}