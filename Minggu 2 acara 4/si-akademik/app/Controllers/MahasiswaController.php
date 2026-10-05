<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';

use App\Models\Mahasiswa;

class MahasiswaController
{
    public function index()
    {
        $mahasiswa = [
            new Mahasiswa('2301001', 'Ingka Jivanda'),
            new Mahasiswa('2401002', 'Sheryn Febrylia'),
            new Mahasiswa('2501003', 'NIla Agustin')
        ];

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }
}