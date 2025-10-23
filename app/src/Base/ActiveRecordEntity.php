<?php

namespace App\Base;

use App\Services\Storage;
use App\ValueObjects\Task\Id;

abstract class ActiveRecordEntity
{
//    protected  $id;
    protected Id $id;
    protected array $data = [];
    protected array $fields;
    protected array $pagination = [];

    abstract static function getFileName(): string;

    public function getFields(): array
    {
        $all = get_object_vars($this);
        $filtered = array_intersect_key($all, array_flip($this->fields));

        foreach ($filtered as $key => $value) {
            if (is_object($value) && method_exists($value, '__toString')) {
                $filtered[$key] = (string)$value;
            }
        }

        return $filtered;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function __set(string $name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    public static function findAll(): array
    {
        $storage = new Storage();

        $rows = $storage->getAll(static::getFileName());

        $models = [];
        foreach ($rows as $row) {
            $model = new static();

            foreach ($row as $item => $value) {
                if ('id' === $item) {
                    $class = 'App\ValueObjects\Task\\' . ucfirst($item);
                    $model->{$item} = new $class($value);
                } else {
                    $model->{$item} = $value;
                }
            }
            $models[] = $model;
        }
        return $models;
    }

    public static function find(string $id): ?self
    {
        $storage = new Storage();
        $row = $storage->get(static::getFileName(), $id);

        $model = new static();

        foreach ($row as $item => $value) {
            if ('id' === $item) {
                $class = 'App\ValueObjects\Task\\' . ucfirst($item);
                $model->{$item} = new $class($value);
            } else {
                $model->{$item} = $value;
            }
        }

        return $model;
    }


    public function save(): bool
    {
        $storage = new Storage();

        $res = false;

        if ('' === $this->getId() || null === $this->getId()) {
            $this->id = new Id();
            $res = $storage->save($this);
        } else {
            $res = $storage->update($this);
        }
        return $res;
    }


    public function query(): static
    {
        $storage = new Storage();
        $this->data = $storage->getAll($this->getFileName());
        return $this;
    }

    public function paginate(int $page = 1, int $perPage = 5): static
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

//        dd($this->data);
//        dd(444444);
        foreach ($this->data as $row) {
            $model = new static();
            foreach ($row as $item => $value) {
                if ('id' === $item) {
                    $class = 'App\ValueObjects\Task\\' . ucfirst($item);

//                    dd($class, $value);

                    if(is_array($value)) {
                        dd($row);
                    }

                    $model->{$item} = new $class($value);
                } else {
                    $model->{$item} = $value;
                }

            }
            $models[] = $model;
        }
        return [
            'data' => $models,
            'pagination' => $this->pagination,
        ];
    }
}