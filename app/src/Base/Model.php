<?php

namespace App\Base;

use App\Services\Storage;

abstract class Model
{
    protected string $fileName;
    protected array $fields = [];

    public function __construct()
    {
        $class = strtolower((new \ReflectionClass($this))->getShortName());
        $this->fileName = $class . 's.json';
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function get(string $id): Model
    {
        $storage = new Storage();
        return $storage->get($this, $id);

    }

    public function getAll(array $filters, array $pagination): array
    {
        $storage = new Storage();
        return $storage->getAll($this, $filters, $pagination);
    }

    public function save(): void
    {
        $storage = new Storage();
        $storage->save($this);

    }

    public function update(): bool
    {
        $storage = new Storage();
        return $storage->update($this);

    }

    public function delete(string $id): bool
    {
        $storage = new Storage();
        return $storage->delete($this, $id);

    }


}