<?php

namespace App\Supports\Cache;

trait Forget
{
    /**
     * Catch static call of undefined methods
     * If method prefix is forget, then execute the key-value corresponding function
     * 
     * @param mixed $method
     * @param mixed $args
     * @throws \BadMethodCallException
     * @return void
     */
    public static function __callStatic(string $method, array $args)
    {
        $forgetKey = strtolower(substr($method, 0, 6));

        if ($forgetKey != 'forget') {
            return;
        }

        self::callForgetAttributeMethod($method, $args);
    }

    /**
     * Execute forget method
     * @param string $method
     * @param array $args
     * @throws \BadMethodCallException
     * @return void
     */
    public static function callForgetAttributeMethod(string $method, array $args)
    {
        $defined = str($method)->replaceStart('forget', '');
        $method = $defined->lcfirst()->toString();
        $key = $defined->snake()->upper()->toString();

        if (!method_exists(static::class, $method)) {
            throw new \BadMethodCallException("Target method $method is undefined.");
        }

        // Throw when key is not existing
        if (!isset(static::keys()[$key])) {
            throw new \BadMethodCallException("Target forget key $key is undefined.");
        }

        // Forget key-value cache data
        self::staticForget(static::keys()[$key](...$args));
    }
}