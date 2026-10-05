<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaController
{
    private $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    public function index()
    {
        $mahasiswa = $this->model->all();

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    public function create()
    {
        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    public function store()
    {
        $data = [
            'nim' => $_POST['nim'],
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'prodi_id' => $_POST['prodi_id'],
            'angkatan' => $_POST['angkatan'],
            'status' => $_POST['status']
        ];

        $this->model->create($data);

        header('Location: /mahasiswa');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];

        $mahasiswa = $this->model->find($id);

        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    public function update()
    {
        $id = $_POST['id'];

        $data = [
            'nim' => $_POST['nim'],
            'nama' => $_POST['nama'],
            'email' => $_POST['email'],
            'prodi_id' => $_POST['prodi_id'],
            'angkatan' => $_POST['angkatan'],
            'status' => $_POST['status']
        ];

        $this->model->update($id, $data);

        header('Location: /mahasiswa');
        exit;
    }

    public function destroy()
    {
        $id = $_POST['id'];

        $this->model->delete($id);

        header('Location: /mahasiswa');
        exit;
    }

    public function show($id)
    {
        echo "Detail Mahasiswa ID : " . $id;
    }
}