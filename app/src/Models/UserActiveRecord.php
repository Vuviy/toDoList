<?php

namespace App\Models;

use App\Base\ActiveRecordEntity;
use App\ValueObjects\Task\Category;
use App\ValueObjects\Task\Date;
use App\ValueObjects\Task\Id;
use App\ValueObjects\Task\Priority;
use App\ValueObjects\Task\Status;
use App\ValueObjects\Task\Title;

class UserActiveRecord extends ActiveRecordEntity
{
    protected Id $id;
    protected Title $title;
    protected Priority $priority;
    protected Category $category;
    protected Date $date;
    protected Status $status;
    protected array $fields = ['id', 'title', 'priority', 'category', 'date', 'status'];

    public static function getFileName(): string
    {
        return 'users.json';
    }

    public function getId(): ?string
    {
        return $this->id;
    }
    function getSearchFields(): array
    {
        return ['name', 'lastName', 'email', 'phone'];
    }
}