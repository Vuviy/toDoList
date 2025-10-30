<?php

namespace App\Base;

use Exception;

class Container
{

    private array $factories = [];
    private array $instances = [];
    private static ?Container $instance = null;


    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;

    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->factories);
    }

    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!$this->has($id)) {
            throw new Exception("Service '{$id}' not found in container");
        }

        $this->instances[$id] = $this->factories[$id]($this);

        return $this->instances[$id];
    }
}