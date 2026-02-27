<?php

namespace App\Supports\Common;

use App\Exceptions\Throws;
use Illuminate\Http\Request;

trait RegisterControllerKeys
{
    /**
     * Locate and excecute controler if found
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    private function registeredController($key, Request $request)
    {
        $controller = array_values(array_filter(
            array_keys($this->registers()),
            fn($class) =>
            str($class)->endsWith((string) str("$key Controller")->camel()->ucfirst())
        ))[0] ?? null;

        if ($controller) {
            return [$controller, $this->registers()[$controller]($request)];
        }

        return null;
    }

    /**
     * Execute
     * @param string $controller
     * @return object
     */
    private function call(string $controller)
    {
        return new $controller();
    }

    /**
     * Load request query
     * @param array $queries
     * @throws \App\Exceptions\Throws
     * @return array
     */
    public function loader(Request $request)
    {
        $response = [];

        $request->merge([
            'query' => $request->get('queries')['query'] ?? null
        ]);

        foreach ($request->get('queries', []) as $key => $query) {
            $controller = (string) str("$key Controller")->camel()->ucfirst();
            if (!$query || $key == 'query')
                continue;

            [$controller, $args] = $this->registeredController($key, $request);

            if (!$controller) {
                throw new Throws("Invalid key $key.");
            }

            $response[$key] = $this->call($controller)->index(...$args);
        }

        return $response;
    }
}