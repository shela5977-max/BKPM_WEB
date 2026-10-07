<?php

class AuthController
{
    public function loginForm()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (
            $username === 'admin' &&
            $password === 'admin123'
        ) {

            session_regenerate_id(true);
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';
            $_SESSION['flash_message'] = 'Selamat datang, Admin';

            header('Location: ' . $this->basePath() . '/dashboard');
            exit;
        }

        echo "<h3>Username atau Password salah</h3>";

        echo "<a href='/Minggu%203%20acara%206/si-akademik/public/login'>
                Kembali Login
              </a>";
    }

    public function logout()
    {
        $_SESSION = [];
        $_SESSION['flash_message'] = 'Anda telah logout';

        header('Location: ' . $this->basePath() . '/login');

        exit;
    }

    private function basePath(): string
    {
        if (defined('APP_BASE_PATH')) {
            return (string) constant('APP_BASE_PATH');
        }

        return '';
    }
}