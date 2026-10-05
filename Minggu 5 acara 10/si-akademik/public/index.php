<?php

session_start();

function app_url($path = '')
{
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $path = ltrim($path, '/');

    return $basePath . ($path === '' ? '/' : '/' . $path);
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

function makeController($controllerName)
{
    if ($controllerName === 'MahasiswaController') {
        return new MahasiswaController(new MahasiswaRepository(new Database()));
    }

    return new $controllerName();
}

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

/*
|--------------------------------------------------------------------------
| Middleware Auth
|--------------------------------------------------------------------------
*/

if (
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

    $controller = makeController('MahasiswaController');

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

    $controller = makeController($controllerName);

    $controller->$action();

} else {

    http_response_code(404);

    echo "<h1>404 - Halaman tidak ditemukan</h1>";
}