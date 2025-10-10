<?php

namespace App\Enums;

enum Sort: string
{
    case PRIORITY_DESC = 'priority_desc';
    case PRIORITY_ASC  = 'priority_asc';
    case DATE_DESC     = 'date_desc';
    case DATE_ASC      = 'date_asc';
    case TITLE_DESC    = 'title_desc';
    case TITLE_ASC     = 'title_asc';
}