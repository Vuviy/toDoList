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

            $filters = [];
            $pagination = [];
            if($_GET){
                $filters = $this->validateFilters($_GET);
            }

            if($_GET && array_key_exists('page', $_GET)){
                $pagination['page'] = (int) $_GET['page'];
            }

            $tasks = new Task();
            $result = $tasks->getAll($filters, $pagination);

            include $viewFile;
        } else {
            echo "View not found";
        }
    }

    private function validateFilters(array $filters): array
    {
        $validatetFilters = [];

        if(array_key_exists('search', $filters) && '' !== $filters['search']) {
            $validatetFilters['search'] = htmlspecialchars(trim($filters['search']), ENT_QUOTES, 'UTF-8');
        }

        if(array_key_exists('filterCategory', $filters) && '' !== $filters['filterCategory']) {
            $validatetFilters['filterCategory'] = (int)$filters['filterCategory'];
        }

        if(array_key_exists('filterPriority', $filters) && '' !== $filters['filterPriority']) {
            $validatetFilters['filterPriority'] = (int)$filters['filterPriority'];
        }

        $allowedSorts = ['priority_desc', 'priority_asc', 'date_desc', 'date_asc', 'title_desc', 'title_asc'];
        if(array_key_exists('sort', $filters) && in_array($filters['sort'], $allowedSorts, true)){
            $validatetFilters['sort'] = $filters['sort'];
        }

        return $validatetFilters;
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