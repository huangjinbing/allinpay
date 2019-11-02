<?php

namespace Tonglian\Allinpay\Facades;


use Illuminate\Support\Facades\Facade;

class Allinpay extends Facade
{
    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

    /**
     * @param string $service
     * @param string $method
     * @param mixed $request
     *
     * @return object
     */
    public static function make($service, $method, $request)
    {
        $service = ucfirst($service);
        $class = "\\Tonglian\\Allinpay\\Port\\$service" . 'Service';
        return (new $class())->$method($request);
    }

}