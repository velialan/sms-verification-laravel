<?php

namespace Phonedotcom\SmsVerification;

use Illuminate\Support\ServiceProvider;

/**
 * Class SmsVerificationProvider
 * @package Phonedotcom\SmsVerification
 */
class SmsVerificationProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->registerResources();
        }
    }

    /**
     * Register resources.
     *
     * @return void
     */
    public function registerResources()
    {
        if ($this->isLumen() === false) {
            $this->publishes([
                __DIR__ . '/../config/sms-verifications.php' => config_path('sms-verifications.php'),
            ], 'config');
        }
    }

    /**
     * Check if package is running under Lumen app
     *
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen') === true;
    }

    /**
     * Register endpoints in routing
     * @param $router
     */
    public static function registerRoutes($router)
    {
        $router->post('/sms-verification', function (Request $request) {
            return response()->json([
                'success' => SmsVerification::sendCode($request->input('phone_number'))
            ]);
        });
        $router->get('/sms-verification/{code}/{number}', function ($code, $phoneNumber) {
            return response()->json([
                'success' => SmsVerification::checkCode($code, $phoneNumber)
            ]);
        });
    }

}