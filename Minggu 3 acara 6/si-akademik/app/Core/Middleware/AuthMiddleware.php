<?php

class AuthMiddleware
{
    public function handle()
    {
        if (
            !isset($_SESSION['logged_in']) ||
            $_SESSION['logged_in'] !== true
        ) {

            header('Location: ' . $this->basePath() . '/login');

            exit;
        }
    }

    private function basePath(): string
    {
        if (defined('APP_BASE_PATH')) {
            return (string) constant('APP_BASE_PATH');
        }

        return '';
    }
}