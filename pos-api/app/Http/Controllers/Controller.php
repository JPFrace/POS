<?php

namespace App\Http\Controllers;

use App\Exceptions\Throws;
use App\Repositories\Repository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class Controller
{
    use AuthorizesRequests;
    /**
     * It will Try Catch Exception errors
     * Capable also to execute the provided catch and finally functions
     *  
     * It return json reponse if the expected response is Json format
     * otherwise, it return $callable output if expected
     * 
     * @return mixed
     */
    public function catch(callable $callable, bool $expectResponse = false, ?callable $catch = null, ?callable $finally = null)
    {
        try {
            $output = $callable();
            if ($expectResponse) {
                return $output;
            }
        } catch (\Throwable | \Error | \Exception | Throws $e) {
            if ($catch) {
                $catch();
            }

            if (config('app.debug')) {
                throw $e;
            }

            Log::error($e);

            return response([
                'message' => $e->getMessage()
            ], 422);


        } finally {
            if ($finally) {
                $finally();
            }
        }
    }

    /**
     * Query the data from the given repository and request query
     * 
     * @param \App\Repositories\Repository $repository
     * @param mixed $resource
     * @param \Illuminate\Http\Request $request
     * 
     * @return mixed
     */
    public function query(Repository $repository, string $resource, Request $request, $isCollection = false)
    {
        $query = $request->get('query', []);

        $query = is_array($query) ? $query : (array) json_decode($query);

        $groupBy = $request->get('group_by', []);
        $orderBy = $request->get('order_by', []);

        $orderBy = is_string($orderBy) ? json_decode($orderBy) : $orderBy;
        $groupBy = is_string($groupBy) ? json_decode($groupBy) : $groupBy;

        $data = $repository->list(
            query: $query,
            paginate: true,
            perPage: $request->get('size', 10),
            groupBy: $groupBy,
            orderBy: $orderBy,
        );

        return $this->catch(
            callable: fn() => match (true) {
                $isCollection => new $resource($data),
                !$isCollection => $resource::collection($data)
            },
            expectResponse: true
        );
    }
}
