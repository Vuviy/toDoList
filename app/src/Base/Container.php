<?php

namespace App\Base;

class Container
{
    public function view(string $template): View
    {
        return new View($template);
    }

}