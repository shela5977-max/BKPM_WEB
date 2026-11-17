
<?php

session_start();

/*
|--------------------------------------------------------------------------
| URL DAN BASE PATH
|--------------------------------------------------------------------------
*/

$uri = parse_url(
    $_SERVER['REQUEST_URI'] ?? '/',
    PHP_URL_PATH
);

$uri = is_string($uri) ? rawurldecode($uri) : '/';

/*
|--------------------------------------------------------------------------
| MENENTUKAN BASE PATH PROJECT
|--------------------------------------------------------------------------
*/

$projectPath = str_replace('\\', '/', realpath(__DIR__));
$documentRoot = str_replace(
    '\\',
    '/',
    realpath($_SERVER['DOCUMENT_ROOT'] ?? '')
);

$base = '';

if (
    $projectPath !== false
    && $documentRoot !== false
    && str_starts_with(
        strtolower($projectPath),
        strtolower($documentRoot)
    )
) {
    $base = substr($projectPath, strlen($documentRoot));
}

$base = rtrim($base, '/');

if (
    $base !== ''
    && (
        $uri === $base
        || str_starts_with($uri, $base . '/')
    )
) {
    $uri = substr($uri, strlen($base));
}

$uri = $uri ?: '/';

/*
|--------------------------------------------------------------------------
| API MAHASISWA - RESTFUL API
|--------------------------------------------------------------------------
| GET    /api/mahasiswa
| GET    /api/mahasiswa/1
| POST   /api/mahasiswa
| PUT    /api/mahasiswa/1
| DELETE /api/mahasiswa/1
|--------------------------------------------------------------------------
*/

if (
    preg_match(
        '#^/api/mahasiswa(?:\.php)?(?:/([^/]+))?/?$#',
        $uri,
        $matches
    ) === 1
) {
    if (isset($matches[1]) && $matches[1] !== '') {
        $_GET['id'] = $matches[1];
    }

    require_once __DIR__ . '/../app/api/mahasiswa.php';
    exit;
}

/*
|--------------------------------------------------------------------------
| CORE
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/BaseController.php';
require_once __DIR__ . '/../app/Core/BaseModel.php';
require_once __DIR__ . '/../app/Core/Controller.php';

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/repositories/ProdiRepository.php';

/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/services/MahasiswaService.php';

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../app/controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/controllers/ProdiController.php';
require_once __DIR__ . '/../app/controllers/MatakuliahController.php';

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

$database = new Database();
$pdo = $database->getConnection();

/*
|--------------------------------------------------------------------------
| REPOSITORIES
|--------------------------------------------------------------------------
*/

$mahasiswaRepository = new MahasiswaRepository($pdo);
$prodiRepository = new ProdiRepository($pdo);

/*
|--------------------------------------------------------------------------
| SERVICE
|--------------------------------------------------------------------------
*/

$mahasiswaService = new MahasiswaService(
    $mahasiswaRepository,
    $prodiRepository
);

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

$controller = new MahasiswaController(
    $mahasiswaRepository,
    $mahasiswaService
);

$prodiController = new ProdiController();
$matakuliahController = new MatakuliahController();

/*
|--------------------------------------------------------------------------
| HTTP METHOD
|--------------------------------------------------------------------------
*/

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

/*
|--------------------------------------------------------------------------
| MAHASISWA
|--------------------------------------------------------------------------
*/

if ($uri === '/mahasiswa' && $method === 'GET') {
    $controller->index();
    exit;
}

if ($uri === '/mahasiswa/create' && $method === 'GET') {
    $controller->create();
    exit;
}

if ($uri === '/mahasiswa' && $method === 'POST') {
    $controller->store();
    exit;
}

if (
    preg_match('#^/mahasiswa/edit/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'GET'
) {
    $controller->edit((int) $matches[1]);
    exit;
}

if (
    preg_match('#^/mahasiswa/update/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'POST'
) {
    $controller->update((int) $matches[1]);
    exit;
}

if (
    preg_match('#^/mahasiswa/delete/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'POST'
) {
    $controller->destroy((int) $matches[1]);
    exit;
}

/*
|--------------------------------------------------------------------------
| PRODI
|--------------------------------------------------------------------------
*/

if ($uri === '/prodi' && $method === 'GET') {
    $prodiController->index();
    exit;
}

if ($uri === '/prodi/create' && $method === 'GET') {
    $prodiController->create();
    exit;
}

if ($uri === '/prodi' && $method === 'POST') {
    $prodiController->store();
    exit;
}

if (
    preg_match('#^/prodi/edit/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'GET'
) {
    $prodiController->edit((int) $matches[1]);
    exit;
}

if (
    preg_match('#^/prodi/update/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'POST'
) {
    $prodiController->update((int) $matches[1]);
    exit;
}

if (
    preg_match('#^/prodi/delete/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'POST'
) {
    $prodiController->destroy((int) $matches[1]);
    exit;
}

/*
|--------------------------------------------------------------------------
| MATA KULIAH
|--------------------------------------------------------------------------
*/

if ($uri === '/matakuliah' && $method === 'GET') {
    $matakuliahController->index();
    exit;
}

if ($uri === '/matakuliah/create' && $method === 'GET') {
    $matakuliahController->create();
    exit;
}

if ($uri === '/matakuliah' && $method === 'POST') {
    $matakuliahController->store();
    exit;
}

if (
    preg_match('#^/matakuliah/edit/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'GET'
) {
    $matakuliahController->edit((int) $matches[1]);
    exit;
}

if (
    preg_match('#^/matakuliah/update/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'POST'
) {
    $matakuliahController->update((int) $matches[1]);
    exit;
}

if (
    preg_match('#^/matakuliah/delete/([0-9]+)$#', $uri, $matches) === 1
    && $method === 'POST'
) {
    $matakuliahController->destroy((int) $matches[1]);
    exit;
}

/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
*/

http_response_code(404);

echo '404 - Halaman tidak ditemukan';
