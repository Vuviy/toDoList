<?php

namespace App\Models;

use App\Base\Model;

class Task extends Model
{
    protected array $fields;

    public function __construct(string $id = '', string $title = '', string $priority = '', string $category = '', string $date = '', string $status = '')
    {
        parent::__construct();

        $this->fields = [
            'id'       => '' !== $id ? $id : uniqid(),
            'title'    => $title,
            'priority' => $priority,
            'category' => $category,
            'date' => $date,
            'status' => $status,
        ];
    }


}