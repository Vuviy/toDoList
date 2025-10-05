<?php

namespace App\Controllers;

use App\DTO\FormData;
use App\Models\Task;
use App\Services\PasswordMakerService;
use App\Services\PasswordVerifyService;

class MainController
{
    public function index()
    {
        $viewFile = __DIR__ . '/../../views/home.php';

        if (file_exists($viewFile)) {

            $tasks = new Task();
            $tasks = $tasks->getAll();
            include $viewFile;
        } else {
            echo "View not found";
        }
    }

    public function delete()
    {
        $data = $_POST;

        if ('' === $data['id'] || null === $data['id']) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'id not found']);
            exit;
        }

        $task = new Task();
        $task->delete($data['id']);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => 'task deleted']);
        exit;

    }

    public function update()
    {
        $data = $_POST;

        if (array_key_exists('id', $data)) {
            $task = new Task(
                id: $data['id'],
                title: $data['title'],
                priority: $data['priority'],
                category: $data['category'],
                date: $data['date'],
                status: 'old',
            );
            $task->update();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => 'task updated']);
            exit;
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'id not found']);
            exit;
        }

    }


    public function submitForm()
    {
        $data = $_POST;

        $task = new Task(
            title: $data['title'],
            priority: $data['priority'],
            category: $data['category'],
            date: $data['date'],
            status: 'new',
        );

        $task->save();


        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([]);
        exit;

    }

}