<?php

require_once __DIR__ . '/../Models/MataKuliah.php';
require_once __DIR__ . '/../Models/Prodi.php';

class MataKuliahController
{
    private $model;
    private $prodiModel;

    public function __construct()
    {
        $this->model = new MataKuliah();
        $this->prodiModel = new Prodi();
    }

    public function index()
    {
        $matakuliah = $this->model->all();
        $content = __DIR__ . '/../Views/matakuliah/table.php';

        require __DIR__ . '/../Views/matakuliah/index.php';
    }

    public function create()
    {
        $prodi = $this->prodiModel->all();
        require __DIR__ . '/../Views/matakuliah/create.php';
    }

    public function store()
    {
        $this->model->create([
            'kode' => $_POST['kode'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'sks' => $_POST['sks'] ?? 0,
            'prodi_id' => $_POST['prodi_id'] ?? 0
        ]);

        header('Location: ' . app_url('matakuliah'));
        exit;
    }

    public function edit()
    {
        $matakuliah = $this->model->find($_GET['id'] ?? 0);

        if (!$matakuliah) {
            http_response_code(404);
            echo 'Data mata kuliah tidak ditemukan';
            return;
        }

        $prodi = $this->prodiModel->all();
        require __DIR__ . '/../Views/matakuliah/edit.php';
    }

    public function update()
    {
        $this->model->update($_POST['id'] ?? 0, [
            'kode' => $_POST['kode'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'sks' => $_POST['sks'] ?? 0,
            'prodi_id' => $_POST['prodi_id'] ?? 0
        ]);

        header('Location: ' . app_url('matakuliah'));
        exit;
    }

    public function destroy()
    {
        $this->model->delete($_POST['id'] ?? 0);

        header('Location: ' . app_url('matakuliah'));
        exit;
    }
}