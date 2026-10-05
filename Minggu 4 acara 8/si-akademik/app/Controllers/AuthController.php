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

            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';

            header('Location: ' . app_url());
            exit;
        }

        echo "<h3>Username atau Password salah</h3>";

        echo "<a href='" . app_url('login') . "'>Kembali Login</a>";
    }

    public function logout()
    {
        session_destroy();

        header('Location: ' . app_url('login'));

        exit;
    }
}