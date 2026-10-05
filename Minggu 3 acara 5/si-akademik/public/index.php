<?php

require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = rawurldecode($uri);

$base = dirname($_SERVER['SCRIPT_NAME']);

if ($base !== '/' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

if ($uri === '' || $uri === '/index.php') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];


if (
    $method === 'GET' &&
    preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)
) {

    $controller = new MahasiswaController();

    $controller->show($matches[1]);

    exit;
}


if (isset($routes[$method][$uri])) {

    [$controllerName, $action] = $routes[$method][$uri];

    $controller = new $controllerName();

    $controller->$action();

} else {

    http_response_code(404);

    echo "<h1>404 - Halaman tidak ditemukan</h1>";
}