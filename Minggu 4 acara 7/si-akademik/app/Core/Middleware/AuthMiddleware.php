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
                'Location: /Minggu%203%20acara%206/si-akademik/public/login'
            );

            exit;
        }
    }
}