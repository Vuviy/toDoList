<?php

namespace App\Controllers;

use App\Base\Container;
use App\Base\Filter;
use App\Http\Request;
use App\Models\TaskActiveRecord;
use App\ValueObjects\Task\Id;

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

        $taskObj = new TaskActiveRecord();

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

        $task = new TaskActiveRecord();
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

        $task = new TaskActiveRecord();

        $task->id = new Id($data['id']);
        $task->title = $data['title'];
        $task->priority = $data['priority'];
        $task->category = $data['category'];
        $task->date = $data['date'];
        $task->status = $data['status'] ?? 'new';

        $task->save();

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

        $task = new TaskActiveRecord();

        $task->title = $data['title'];
        $task->priority = $data['priority'];
        $task->category = $data['category'];
        $task->date = $data['date'];
        $task->status = 'new';

        $task->save();

        header('Content-Type: application/json; charset=utf-8', true, 201);
        echo json_encode([]);
        exit;
    }
}