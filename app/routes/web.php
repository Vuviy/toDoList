<?php

use App\Controllers\MainController;
use App\Http\Request;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controller = new MainController();

$request = Request::create();

if ($uri === '/' || $uri === '/home') {
    $controller->index($request);
} elseif ($uri === '/save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->save($request);
} elseif ($uri === '/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->delete($request);
} elseif ($uri === '/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update($request);
} else {
    http_response_code(404);
    echo "404 Not Found";
}