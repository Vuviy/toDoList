<?php

use App\Controllers\MainController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controller = new MainController();

if ($uri === '/' || $uri === '/home') {
    $controller->index();
} elseif ($uri === '/form/submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->submitForm();
} elseif ($uri === '/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->delete();
} elseif ($uri === '/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->update();
} else {
    http_response_code(404);
    echo "404 Not Found";
}