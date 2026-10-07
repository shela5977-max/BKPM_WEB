<?php

$routes = [

    'GET' => [

        '/' => [
            'HomeController',
            'index'
        ],

        '/login' => [
            'AuthController',
            'loginForm'
        ],

        '/logout' => [
            'AuthController',
            'logout'
        ],

        '/mahasiswa' => [
            'MahasiswaController',
            'index'
        ],

        '/mahasiswa/create' => [
            'MahasiswaController',
            'create'
        ],

        '/mahasiswa/edit' => [
            'MahasiswaController',
            'edit'
        ],

        '/prodi' => [
            'ProdiController',
            'index'
        ],

        '/prodi/create' => [
            'ProdiController',
            'create'
        ],

        '/prodi/edit' => [
            'ProdiController',
            'edit'
        ],

        '/matakuliah' => [
            'MataKuliahController',
            'index'
        ],

        '/matakuliah/create' => [
            'MataKuliahController',
            'create'
        ],

        '/matakuliah/edit' => [
            'MataKuliahController',
            'edit'
        ],

    ],

    'POST' => [

        '/login' => [
            'AuthController',
            'login'
        ],

        '/mahasiswa' => [
            'MahasiswaController',
            'store'
        ],

        '/mahasiswa/update' => [
            'MahasiswaController',
            'update'
        ],

        '/mahasiswa/delete' => [
            'MahasiswaController',
            'destroy'
        ],

        '/prodi' => [
            'ProdiController',
            'store'
        ],

        '/prodi/update' => [
            'ProdiController',
            'update'
        ],

        '/prodi/delete' => [
            'ProdiController',
            'destroy'
        ],

        '/matakuliah' => [
            'MataKuliahController',
            'store'
        ],

        '/matakuliah/update' => [
            'MataKuliahController',
            'update'
        ],

        '/matakuliah/delete' => [
            'MataKuliahController',
            'destroy'
        ],

    ],

];