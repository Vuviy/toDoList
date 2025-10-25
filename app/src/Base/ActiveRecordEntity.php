<?php

namespace App\Base;

use App\Services\Storage;
use App\ValueObjects\Task\Id;
use ReflectionClass;

abstract class ActiveRecordEntity
{
//    protected  $id;
//    protected Id $id;
    protected array $data = [];
    protected array $fields;
    protected array $pagination = [];

    private array $immutableFields = ['fields'];

    abstract static function getFileName(): string;
    abstract function getSearchFields(): array;

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

    public function __set(string $name, $value)
    {
        if (in_array($name, $this->immutableFields)) {
            return \Exception::class;
        }

        if (!property_exists($this, $name)) {
            return \Exception::class;
        }

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

        $class = new ReflectionClass(static::class);
        $props = $class->getProperties();

        $propertiesClass = [];
        foreach ($props as $prop) {
            $class = $prop->getType()->getName();
            $name = $prop->getName();
            $propertiesClass[$name] = $class;
        }

        foreach ($rows as $row) {
            $model = new static();

            foreach ($row as $item => $value) {
                $class = $propertiesClass[$item];
                $model->{$item} = new $class($value);
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

        $class = new ReflectionClass(static::class);
        $props = $class->getProperties();

        $propertiesClass = [];
        foreach ($props as $prop) {
            $class = $prop->getType()->getName();
            $name = $prop->getName();
            $propertiesClass[$name] = $class;
        }

        foreach ($row as $item => $value) {
            $class = $propertiesClass[$item];
            $model->{$item} = new $class($value);
        }

        return $model;
    }

    public function delete(string $id): bool
    {
        $storage = new Storage();
        return $storage->delete($this, $id);

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

        $class = new ReflectionClass(static::class);
        $props = $class->getProperties();

        $propertiesClass = [];
        foreach ($props as $prop) {
            $class = $prop->getType()->getName();
            $name = $prop->getName();
            $propertiesClass[$name] = $class;
        }

        foreach ($this->data as $row) {
            $model = new static();
            foreach ($row as $item => $value) {
                $class = $propertiesClass[$item];
                $model->{$item} = new $class($value);
            }
            $models[] = $model;
        }
        return [
            'data' => $models,
            'pagination' => $this->pagination,
        ];
    }

    public function filter(array $filters): static
    {
        $searchFields = $this->getSearchFields();

        $this->data = array_filter($this->data, function ($row) use ($filters, $searchFields) {
            if (array_key_exists('search', $filters) && '' !== $filters['search']) {
                $searchValue = mb_strtolower($filters['search']);
                $found = false;

                foreach ($searchFields as $field) {
                    if (array_key_exists($field, $row) && str_contains(mb_strtolower($row[$field]), $searchValue)) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    return false;
                }
            }

            foreach ($filters as $key => $value) {
                if ($key === 'search' || !array_key_exists( str_replace('filter_', '', $key), $row)) {
                    continue;
                }

                if ((string)$row[str_replace('filter_', '', $key)] !== (string)$value) {
                    return false;
                }
            }

            return true;
        });

        return $this;
    }


    public function sort(array $filters): static
    {
        if (array_key_exists('sort', $filters)) {
            $sort = $filters['sort'];
            $direction = 'asc';

            if (str_ends_with($sort, '_desc')) {
                $direction = 'desc';
                $field = substr($sort, 0, -5);
            } elseif (str_ends_with($sort, '_asc')) {
                $field = substr($sort, 0, -4);
            } else {
                $field = $sort;
            }

            usort($this->data, function ($a, $b) use ($field, $direction) {
                $valA = $a[$field] ?? null;
                $valB = $b[$field] ?? null;

                if ($valA === null && $valB === null) {
                    return 0;
                }

                if (is_string($valA) && strtotime($valA) !== false) {
                    $valA = strtotime($valA);
                }
                if (is_string($valB) && strtotime($valB) !== false) {
                    $valB = strtotime($valB);
                }
                $result = $valA <=> $valB;

                return $direction === 'desc' ? -$result : $result;
            });
        }
        return $this;
    }
}