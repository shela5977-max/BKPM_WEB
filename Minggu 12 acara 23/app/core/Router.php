<?php
class Router {
    // Peta sederhana: nama resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController',
        'register'  => 'AuthController',
        'login'     => 'AuthController',
    ];

    public function dispatch(): void {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';

        // Halaman cek koneksi: URL berakhir dengan "/", atau /public, /index.php, /ping
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (substr($path, -1) === '/' || in_array($resource, ['public', 'index.php', 'ping'], true)) {
            Response::json(200, true, 'API Sistem Informasi Akademik (Acara 20) berjalan', [
                'endpoints' => [
                    'POST /register', 'POST /login',
                    'GET /mahasiswa', 'POST|PUT|DELETE /mahasiswa (butuh Bearer token)',
                    'GET|POST|PUT|DELETE /buku',
                ],
            ]);
        }

        $controllerName = $this->map[$resource] ?? null;
        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        try {
            $controller = new $controllerName();

            // register bukan resource CRUD biasa -> ditangani khusus (hanya POST)
            if ($resource === 'register') {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    Response::json(405, false, 'Method tidak diizinkan');
                }
                $controller->register();
                return;
            }

            // Acara 20: login juga bukan resource CRUD -> hanya POST
            if ($resource === 'login') {
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    Response::json(405, false, 'Method tidak diizinkan');
                }
                $controller->login();
                return;
            }

            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':    $controller->index(); break;
                case 'POST':   $controller->store(); break;
                case 'PUT':    $controller->update($_GET['id'] ?? null); break;
                case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
                default:
                    Response::json(405, false, 'Method tidak diizinkan');
            }
        } catch (Throwable $e) {
            // Kegagalan tak terduga -> 500 Internal Server Error
            $errors = APP_ENV === 'development' ? ['server' => [$e->getMessage()]] : [];
            Response::json(500, false, 'Terjadi kesalahan pada server', null, $errors);
        }
    }
}
