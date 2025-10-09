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

    public function getAll(Model $model, array $filters, array $pagination): array
    {
        $file = $this->path . $model->getFileName();

        if (!file_exists($file)) {
            return [];
        }

        $rows = json_decode(file_get_contents($file), true) ?? [];

        $models = [];
        foreach ($rows as $row) {

            $include = true;

            if (array_key_exists('search', $filters)) {
                if (!str_contains($row['title'], $filters['search'])) {
                    $include = false;
                }
            }

            if (array_key_exists('filterCategory', $filters)) {
                if ((int)$row['category'] !== (int)$filters['filterCategory']) {
                    $include = false;
                }
            }

            if (array_key_exists('filterPriority', $filters)) {
                if ((int)$row['category'] !== (int)$filters['filterPriority']) {
                    $include = false;
                }
            }

            if ($include) {
                $models[] = new $model(...array_values($row));
            }
        }

        if (array_key_exists('sort', $filters)) {
            usort($models, function ($a, $b) use ($filters) {
                $fieldsA = $a->getFields();
                $fieldsB = $b->getFields();

                switch ($filters['sort']) {
                    case 'priority_desc':
                        return $fieldsB['priority'] <=> $fieldsA['priority'];
                    case 'priority_asc':
                        return $fieldsA['priority'] <=> $fieldsB['priority'];
                    case 'date_desc':
                        return strtotime($fieldsB['date']) <=> strtotime($fieldsA['date']);
                    case 'date_asc':
                        return strtotime($fieldsA['date']) <=> strtotime($fieldsB['date']);
                    case 'title_desc':
                        return strcasecmp($fieldsB['title'], $fieldsA['title']);
                    case 'title_asc':
                        return strcasecmp($fieldsA['title'], $fieldsB['title']);
                    default:
                        return 0;
                }
            });
        }

        $page = array_key_exists('page', $pagination) && (int)$pagination['page'] > 0 ? (int)$pagination['page'] : 1;
        $perPage = 2;

        $total = count($models);

        $pages = max(1, ceil($total / $perPage));

        if ($page > $pages) {
            $page = $pages;
        }
        $offset = ($page - 1) * $perPage;

        $models = array_slice($models, $offset, $perPage);

        return [
            'data' => $models,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'pages' => ceil($total / $perPage),
        ];

//        return $models;
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