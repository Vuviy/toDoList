<?php

namespace App\Base;

class View
{
    public function __construct(private string $template)
    {
    }

    public function render(array $data = []): void
    {
        $fileView = __DIR__ . "/../../views/{$this->template}.php";

        if (file_exists($fileView)) {
            extract($data);
            include $fileView;
        } else {
            echo "View not found";
        }

    }
}