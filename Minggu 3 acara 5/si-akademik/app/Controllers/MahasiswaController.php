<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';

use App\Models\Mahasiswa;

class MahasiswaController
{
    public function index()
    {
        $mahasiswa = [

            new Mahasiswa(
                '2301001',
                'Ingka Jivanda'
            ),

            new Mahasiswa(
                '2401002',
                'Sheryn Febrylia'
            ),

            new Mahasiswa(
                '2501003',
                'Nila Agustin'
            )

        ];

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    public function create()
    {
        $content = __DIR__ . '/../Views/mahasiswa/create.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store()
    {
        echo "Data Mahasiswa berhasil disimpan";
    }

    public function show($id)
    {
        echo "<h2>Detail Mahasiswa</h2>";
        echo "<p>ID Mahasiswa : $id</p>";
    }
}