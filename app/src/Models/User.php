<?php

namespace App\Models;

use App\Base\Model;

class User extends Model
{
    protected array $fields;

    public function __construct(string $id = '', string $name = '', string $last_name = '')
    {
        parent::__construct();

        $this->fields = [
            'id'       => '' !== $id ? $id : uniqid(),
            'name'    => $name,
            'last_name' => $last_name,
        ];
    }

    public function filter(array $filters): static
    {

        $this->data = array_filter($this->data, function ($row) use ($filters) {
            $include = true;

            if (array_key_exists('search', $filters) && !str_contains($row['name'], $filters['search']) && !str_contains($row['last_name'], $filters['search'])) {
                $include = false;
            }

            return $include;
        });

        return $this;
    }

    public function sort( array $filters): static
    {
        if (array_key_exists('sort', $filters)) {
            usort($this->data, function ($a, $b) use ($filters) {
                $fieldsA = $a;
                $fieldsB = $b;

                switch ($filters['sort']) {
                    case 'name_desc':
                        return $fieldsB['name'] <=> $fieldsA['name'];
                    case 'name_asc':
                        return $fieldsA['name'] <=> $fieldsB['name'];
                    case 'last_name_desc':
                        return strtotime($fieldsB['last_name']) <=> strtotime($fieldsA['last_name']);
                    case 'last_name_asc':
                        return strtotime($fieldsA['last_name']) <=> strtotime($fieldsB['last_name']);
                    default:
                        return 0;
                }
            });
        }

        return $this;
    }


}