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

    ],

];