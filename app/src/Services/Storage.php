<?php

namespace App\Services;

use App\Base\Model;

class Storage
{

    private string $path;

    public function __construct(string $path = __DIR__ . '/../../fileStorage/')
    {
        $this->path = rtrim($path, '/') . '/';
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function getAll(Model $model): array
    {
        $file = $this->path . $model->getFileName();

        if (!file_exists($file)) {
            return [];
        }

        $rows = json_decode(file_get_contents($file), true) ?? [];

        $models = [];
        foreach ($rows as $row) {
            $models[] = new $model(...array_values($row));
        }

        return $models;
    }

    public function get(Model $model, string $id): ?Model
    {
        $all = $this->getAll($model);

        foreach ($all as $model) {
            if ($model->getFields()['id'] === $id) {
                return $model;
            }
        }

        return null;
    }

    public function save(Model $model): void
    {

        $file = $this->path . $model->getFileName();

        $data = [];
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true) ?? [];
        }

        $data[] = $model->getFields();

        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function update(Model $model): bool
    {
        $file = $this->path . $model->getFileName();

        if (!file_exists($file)) {
            return false;
        }

        $rows = json_decode(file_get_contents($file), true) ?? [];

        $newRows = [];
        foreach ($rows as $row) {
            if ($row['id'] === $model->getFields()['id']) {
                $newRows[] = $model->getFields();
            } else {
                $newRows[] = $row;
            }

        }
        file_put_contents($file, json_encode($newRows, JSON_PRETTY_PRINT));

        return true;
    }

    public function delete(Model $model, string $id): bool
    {
        $file = $this->path . $model->getFileName();

        if (!file_exists($file)) {
            return false;
        }

        $rows = json_decode(file_get_contents($file), true) ?? [];

        $newRows = [];
        foreach ($rows as $row) {
            if ($row['id'] !== $id) {
                $newRows[] = $row;
            }

        }
        file_put_contents($file, json_encode($newRows, JSON_PRETTY_PRINT));

        return true;
    }

}