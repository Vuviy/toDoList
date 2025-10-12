<?php

namespace App\Base;

use App\Enums\Sort;

class Filter
{
    public static function getFilters(array $params): array
    {
        $validatetFilters = [];

        foreach ($params as $param => $value) {
            if('' !== $value && 'sort' !== $param) {
                $validatetFilters[$param] = htmlspecialchars(trim($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            }
        }

        if (array_key_exists('sort', $params) && '' !== $params['sort']) {
            $sortEnum = Sort::from($params['sort']);
            $validatetFilters['sort'] = $sortEnum->value;
        }

        return $validatetFilters;
    }

}