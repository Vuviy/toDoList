<?php

namespace App\Base;

use App\Services\Storage;

abstract class Model
{
    protected string $fileName;
    protected array $fields = [];
    protected array $data = [];
    protected array $pagination = [];

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

    public function getOne(string $id): Model
    {
        $storage = new Storage();
        $row =  $storage->get($this->fileName, $id);

        return new static(...array_values($row));
    }

    public function query(): static
    {
        $storage = new Storage();
        $this->data = $storage->getAll($this->fileName);
        return $this;

    }

    public function getAll(): array
    {
        $storage = new Storage();
        $rows =   $storage->getAll($this->fileName);

        $models = [];
        foreach ($rows as $row) {
            $models[] = new static(...array_values($row));
        }

        return $models;
    }

    public function paginate(int $page = 1, int $perPage = 3): static
    {
        $total = count($this->data);
        $pages = max(1, ceil($total / $perPage));

        $page = min(max($page, 1), $pages);
        $offset = ($page - 1) * $perPage;

        $this->pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'pages' => $pages,
        ];

        $this->data = array_slice($this->data, $offset, $perPage);

        return $this;
    }

    public function get(): array
    {
        $models = [];
        foreach ($this->data as $row) {
            $models[] = new static(...array_values($row));
        }

        return [
            'data' => $models,
            'pagination' => $this->pagination,
        ];
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