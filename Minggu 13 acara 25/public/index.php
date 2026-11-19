<?php
// Acara 24 - Langkah 1: header CORS (berlaku untuk semua resource yang lewat Router)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight CORS: browser mengirim OPTIONS sebelum PUT/DELETE atau request ber-header Authorization.
// Dijawab 204 di sini agar tidak ditolak Router dengan 405.
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Hanya berpengaruh jika dijalankan lewat "php -S" (jalankan.bat): sajikan file statis
// seperti /views/index.html apa adanya. Pada Apache/XAMPP baris ini tidak aktif.
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file) && basename($file) !== 'index.php') {
        return false;
    }
}

require_once __DIR__ . '/../app/core/autoload.php';
(new Router())->dispatch();
