<?php

class AuthMiddleware
{
    public function handle()
    {
        if (
            !isset($_SESSION['logged_in']) ||
            $_SESSION['logged_in'] !== true
        ) {

            header(
                'Location: /Minggu%204%20acara%208/si-akademik/public/login'
            );

            exit;
        }
    }
}