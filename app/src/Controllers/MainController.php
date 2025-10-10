<?php

namespace App\Controllers;

use App\DTO\FormData;
use App\Enums\Sort;
use App\Http\Request;
use App\Models\Task;
use App\Services\PasswordMakerService;
use App\Services\PasswordVerifyService;

class MainController
{
    public function index(Request $request)
    {
        $viewFile = __DIR__ . '/../../views/home.php';

        if (file_exists($viewFile)) {

            $filters = [];
            $pagination = [];

            if ($request->get()) {
                $filters = $this->validateFilters($request->get());
            }

            if (array_key_exists('page', $request->get())) {
                $pagination['page'] = (int)$request->input('page');
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

        if (array_key_exists('search', $filters) && '' !== $filters['search']) {
            $validatetFilters['search'] = htmlspecialchars(trim($filters['search']), ENT_QUOTES, 'UTF-8');
        }

        if (array_key_exists('filter_category', $filters) && '' !== $filters['filter_category']) {
            $validatetFilters['filter_category'] = (int)$filters['filter_category'];
        }

        if (array_key_exists('filter_priority', $filters) && '' !== $filters['filter_priority']) {
            $validatetFilters['filter_priority'] = (int)$filters['filter_priority'];
        }

        if (array_key_exists('sort', $filters)) {
            $sortEnum = Sort::from($filters['sort']);
            $validatetFilters['sort'] = $sortEnum->value;
        }

        return $validatetFilters;
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int',
        ]);


        $task = new Task();
        $task->delete($data['id']);

        header('Content-Type: application/json; charset=utf-8', true, 200);
        echo json_encode(['success' => 'task deleted']);
        exit;

    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int',
            'title' => 'required|string',
            'priority' => 'required|int',
            'category' => 'required|int',
            'date' => 'required|date',
        ]);

        $task = new Task(
            id: $data['id'],
            title: $data['title'],
            priority: $data['priority'],
            category: $data['category'],
            date: $data['date'],
            status: 'old',
        );
        $task->update();

        header('Content-Type: application/json; charset=utf-8', true, 200);
        echo json_encode(['success' => 'task updated']);
        exit;

    }


    public function save(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'priority' => 'required|int',
            'category' => 'required|int',
            'date' => 'required|date',
        ]);

        $task = new Task(
            title: $data['title'],
            priority: $data['priority'],
            category: $data['category'],
            date: $data['date'],
            status: 'new',
        );

        $task->save();

        header('Content-Type: application/json; charset=utf-8', true, 201);
        echo json_encode([]);
        exit;

    }

}