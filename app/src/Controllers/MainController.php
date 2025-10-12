<?php

namespace App\Controllers;

use App\Base\Container;
use App\Base\Filter;
use App\Http\Request;
use App\Models\Task;

class MainController
{
    public function index(Request $request)
    {
        $filters = [];
        $pagination = 1;

        if ($request->get()) {
            $filters = Filter::getFilters($request->get());
        }

        if (array_key_exists('page', $request->get())) {
            $pagination = (int)$request->input('page');
        }

        $taskObj = new Task();

        $result = $taskObj
            ->query()
            ->filter($filters)
            ->sort($filters)
            ->paginate($pagination)
            ->get();
        $container = new Container();

        $view = $container->view('home');
        $view->render($result);
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
            'id' => 'required|string',
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