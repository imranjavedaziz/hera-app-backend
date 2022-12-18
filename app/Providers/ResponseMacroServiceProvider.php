<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('Success', function ($message, $record=null) {
            return response()->json(['message' => $message, 'data'=>$record], 200);
        });
        Response::macro('Error', function ($message, $code='') {
            if (empty($code)) {
                $code = 404;
            }
            return response()->json(['message' => $message], $code);
        });

        Response::macro('Info', function ($message) {
            return response()->json(['message' => $message], 202);
        });
    }
}
