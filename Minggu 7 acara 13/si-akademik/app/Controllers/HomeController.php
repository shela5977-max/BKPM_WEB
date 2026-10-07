<?php

class HomeController
{
    public function index()
    {
        if (($_SESSION['logged_in'] ?? false) !== true) {
            header('Location: ' . app_url('login'));
            exit;
        }

        $content = __DIR__ . '/../Views/partials/home-content.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}