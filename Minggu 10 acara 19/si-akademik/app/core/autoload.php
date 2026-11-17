<?php
// Muat Database.php lebih awal agar konstanta APP_ENV sudah tersedia
// sebelum Response / Router dipakai.
require_once __DIR__ . '/../config/Database.php';

spl_autoload_register(function ($class) {
    $folders = ['config', 'core', 'models', 'controllers'];
    foreach ($folders as $folder) {
        $path = __DIR__ . "/../{$folder}/{$class}.php";
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});
