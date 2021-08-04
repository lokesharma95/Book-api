<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
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
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('api', function ($data,$success,$message) use ($factory) {
            
            $customFormat = [
                'message' => $message,
                'success' => $success,
                'data' => $data
            ];
            return $factory->make($customFormat);
        });
    }
}
