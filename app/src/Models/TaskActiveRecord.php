<?php

namespace App\Models;

use App\Base\ActiveRecordEntity;
use App\ValueObjects\Task\Category;
use App\ValueObjects\Task\Date;
use App\ValueObjects\Task\Id;
use App\ValueObjects\Task\Priority;
use App\ValueObjects\Task\Status;
use App\ValueObjects\Task\Title;

class TaskActiveRecord extends ActiveRecordEntity
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
        return 'tasks.json';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

//    public function filter(array $filters): static
//    {
//        $this->data = array_filter($this->data, function ($row) use ($filters) {
//            $include = true;
//
//            if (array_key_exists('search', $filters) && !str_contains($row['title'], $filters['search'])) {
//                $include = false;
//            }
//
//            if (array_key_exists('filter_category', $filters)) {
//                if ((int)$row['category'] !== (int)$filters['filter_category']) {
//                    $include = false;
//                }
//            }
//
//            if (array_key_exists('filter_priority', $filters)) {
//                if ((int)$row['priority'] !== (int)$filters['filter_priority']) {
//                    $include = false;
//                }
//            }
//
//            return $include;
//        });
//        return $this;
//    }

//    public function sort(array $filters): static
//    {
//        if (array_key_exists('sort', $filters)) {
//            usort($this->data, function ($a, $b) use ($filters) {
//                $fieldsA = $a->getFields();
//                $fieldsB = $b->getFields();
//
//                switch ($filters['sort']) {
//                    case 'priority_desc':
//                        return $fieldsB['priority'] <=> $fieldsA['priority'];
//                    case 'priority_asc':
//                        return $fieldsA['priority'] <=> $fieldsB['priority'];
//                    case 'date_desc':
//                        return strtotime($fieldsB['date']) <=> strtotime($fieldsA['date']);
//                    case 'date_asc':
//                        return strtotime($fieldsA['date']) <=> strtotime($fieldsB['date']);
//                    case 'title_desc':
//                        return strcasecmp($fieldsB['title'], $fieldsA['title']);
//                    case 'title_asc':
//                        return strcasecmp($fieldsA['title'], $fieldsB['title']);
//                    default:
//                        return 0;
//                }
//            });
//        }
//        return $this;
//    }

    function getSearchFields(): array
    {
        return ['title'];
    }
}