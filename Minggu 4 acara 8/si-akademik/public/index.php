<?php

session_name('SI_AKADEMIK_MINGGU4_ACARA8');
session_start();

function app_url($path = '')
{
    $scriptName = str_replace('\\', '/', rawurldecode($_SERVER['SCRIPT_NAME']));
    $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    $basePath = $basePath === '.' ? '' : $basePath;
    $basePath = implode('/', array_map('rawurlencode', explode('/', $basePath)));
    $path = implode('/', array_map('rawurlencode', explode('/', trim($path, '/'))));

    return $basePath . '/' . $path;
}

require_once __DIR__ . '/../routes/web.php';

require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/ProdiController.php';
require_once __DIR__ . '/../app/Controllers/MataKuliahController.php';

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Middleware/AuthMiddleware.php';

require_once __DIR__ . '/../app/Models/Mahasiswa.php';
require_once __DIR__ . '/../app/Models/Prodi.php';
require_once __DIR__ . '/../app/Models/MataKuliah.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = rawurldecode($uri);

$base = str_replace('\\', '/', rawurldecode(dirname($_SERVER['SCRIPT_NAME'])));

if ($base !== '/' && $base !== '.' && $base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}

if ($uri === '' || $uri === '/index.php') {
    $uri = '/';
}

$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| Middleware Auth
|--------------------------------------------------------------------------
*/

if (
    $uri === '/' ||
    str_starts_with($uri, '/mahasiswa') ||
    str_starts_with($uri, '/prodi') ||
    str_starts_with($uri, '/matakuliah')
) {

    $middleware = new AuthMiddleware();

    $middleware->handle();
}

/*
|--------------------------------------------------------------------------
| Route Dinamis
|--------------------------------------------------------------------------
*/

if (
    $method === 'GET' &&
    preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)
) {

    $controller = new MahasiswaController();

    $controller->show($matches[1]);

    exit;
}

/*
|--------------------------------------------------------------------------
| Route Biasa
|--------------------------------------------------------------------------
*/

if (isset($routes[$method][$uri])) {

    [$controllerName, $action] = $routes[$method][$uri];

    $controller = new $controllerName();

    $controller->$action();

} else {

    http_response_code(404);

    echo "<h1>404 - Halaman tidak ditemukan</h1>";
}