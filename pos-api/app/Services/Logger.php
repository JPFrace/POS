<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class Logger
{
    private array $data = [];

    public static function log(): self
    {
        return new self;
    }

    public function __call($name, $arguments)
    {
        throw new \Exception('Not implemented');
    }

    public function page(string $name): self
    {
        $this->data['page'] = $name;

        return $this;
    }

    public function action(string $name): self
    {
        $this->data['action'] = $name;

        return $this;
    }

    public function path(string $name): self
    {
        $this->data['path'] = $name;

        return $this;
    }

    public function method(string $name): self
    {
        $this->data['method'] = $name;

        return $this;
    }
    public function uri(string $name): self
    {
        $this->data['uri'] = $name;

        return $this;
    }

    public function reference(array $reference): self
    {
        $this->data['reference'] = $reference;

        return $this;
    }

    public function request(array $request): self
    {
        $this->data['request'] = $request;

        return $this;
    }

    public function create(): void
    {
        $this->data['creator_id'] = \Auth::user()?->id;
        $this->data['ip_address'] = request()->ip();
        $this->data['uri'] = request()->fullUrl();
        $all = request()->all();

        foreach ($all as $key => $value) {
            if ($value instanceof UploadedFile) {
                unset($all[$key]);
            }
        }

        \App\Models\Logger::create([
            'page' => $this->data['page'] ?? null,
            'action' => $this->data['action'] ?? null,
            'path' => $this->data['path'] ?? '',
            'method' => $this->data['method'] ?? '',
            'uri' => $this->data['uri'] ?? null,
            'reference' => $this->data['reference'] ?? null,
            'request' => $this->data['request'] ?? $all,
            'creator_id' => $this->data['creator_id'] ?? null,
            'ip_address' => $this->data['ip_address'] ?? null,
            'device' => $this->data['device'] ?? '',
        ]);
    }
}