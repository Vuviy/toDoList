<?php

namespace App\Controllers;

use App\DTO\FormData;
use App\Services\PasswordMakerService;
use App\Services\PasswordVerifyService;

class MainController
{
    public function index()
    {
        $viewFile = __DIR__ . '/../../views/home.php';

        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View not found";
        }
    }


    public function submitForm()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([]);
        exit;

    }

}