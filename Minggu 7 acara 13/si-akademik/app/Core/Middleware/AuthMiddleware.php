<?php

class AuthMiddleware
{
    public function handle()
    {
        if (
            !isset($_SESSION['logged_in']) ||
            $_SESSION['logged_in'] !== true
        ) {

            header('Location: ' . app_url('login'));

            exit;
        }
    }
}