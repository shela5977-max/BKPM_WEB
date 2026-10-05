<?php

class HomeController
{
    public function index()
    {
        $content = __DIR__ . '/../Views/partials/home-content.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }
}