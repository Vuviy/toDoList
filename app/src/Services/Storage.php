<?php

namespace App\Services;

use App\Base\ActiveRecordEntity;

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

    public function getAll(string $filename): array
    {
        $file = $this->path . $filename;

        if (!file_exists($file)) {
            return [];
        }

        return json_decode(file_get_contents($file), true) ?? [];
    }

    public function get(string $filename, string $id): ?array
    {
        $all = $this->getAll($filename);

        foreach ($all as $row) {
            if ($row['id'] === $id) {
                return $row;
            }
        }

        return null;
    }

    public function save(ActiveRecordEntity $model): bool
    {
        $file = $this->path . $model->getFileName();

        $data = [];
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true) ?? [];
        }

        $data[] = $model->getFields();

        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return true;
    }

    public function update(ActiveRecordEntity $model): bool
    {
        $file = $this->path . $model->getFileName();

        if (!file_exists($file)) {
            return false;
        }
        $rows = json_decode(file_get_contents($file), true) ?? [];
        $newRows = [];
        foreach ($rows as $row) {

            if ($row['id'] === (string)$model->getFields()['id']) {

                $newRows[] = $model->getFields();
            } else {
                $newRows[] = $row;
            }
        }
        file_put_contents($file, json_encode($newRows, JSON_PRETTY_PRINT));

        return true;
    }

    public function delete(ActiveRecordEntity $model, string $id): bool
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