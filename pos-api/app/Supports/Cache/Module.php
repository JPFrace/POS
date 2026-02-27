<?php

namespace App\Supports\Cache;

use App\Contracts\Cache\Key as KeyContract;
use App\Supports\Cache\Cache as Generator;
use App\Contracts\Cache\Cache as CacheContract;
use Carbon\Carbon;

class Module implements \App\Contracts\Cache\Module
{
    /**
     * Define static methods
     */
    use ModuleStatic;

    private CacheContract $generator;

    public function __construct(protected string $key)
    {
        $this->generator = new Generator();
    }

    /**
     * Retrieve the module key value
     * @param \App\Contracts\Cache\Key $key
     * @param callable $value
     * @param mixed $expiredAt
     * @return mixed
     */
    public function remember(KeyContract $key, callable $value, ?Carbon $expiredAt = null): mixed
    {
        $resolveKey = $key->get();

        $stored = $this->get();

        if (!is_null($stored) && in_array($resolveKey, array_keys($stored))) {
            return $stored[$resolveKey];
        }

        $stored[$resolveKey] = is_callable($value) ? $value() : $value;

        if (!$stored[$resolveKey]) {
            return null;
        }

        $this->generator->put(
            $this->getKey(),
            $stored,
            $expiredAt
        );

        return $this->get($key);
    }

    /**
     * Forget key
     * @param mixed $key
     * @return mixed
     */
    public function forget(?KeyContract $key = null): mixed
    {
        if (empty($key)) {
            $this->generator->forget($this->getKey());

            return null;
        }

        // Destroy module key
        $stored = $this->generator->get($this->getKey());

        // Preserve the key for the reference of remember
        // When key is existing but the value is empty which means it has been destroyed
        $stored[$key->get()] = null;

        return $this->generator->put(
            $this->getKey(),
            $stored
        );
    }

    /**
     * Retrieve cache or module key
     * @param mixed $key
     * @return mixed
     */
    public function get(?KeyContract $key = null): mixed
    {
        $stored = $this->generator->get(
            $this->getKey()
        );

        if (empty($key)) {
            return $stored;
        }

        return $stored[$key->get()] ?? null;
    }

    /**
     * Get key
     * @return string
     */
    private function getKey(): Key
    {
        return new Key($this->key);
    }
}