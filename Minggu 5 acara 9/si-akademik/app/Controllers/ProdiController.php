<?php

require_once __DIR__ . '/../Models/Prodi.php';

class ProdiController
{
    private $model;

    public function __construct()
    {
        $this->model = new Prodi();
    }

    public function index()
    {
        $prodi = $this->model->all();
        $content = __DIR__ . '/../Views/prodi/table.php';

        require __DIR__ . '/../Views/prodi/index.php';
    }

    public function create()
    {
        require __DIR__ . '/../Views/prodi/create.php';
    }

    public function store()
    {
        $this->model->create([
            'kode' => $_POST['kode'] ?? '',
            'nama' => $_POST['nama'] ?? ''
        ]);

        header('Location: ' . app_url('prodi'));
        exit;
    }

    public function edit()
    {
        $prodi = $this->model->find($_GET['id'] ?? 0);

        if (!$prodi) {
            http_response_code(404);
            echo 'Data prodi tidak ditemukan';
            return;
        }

        require __DIR__ . '/../Views/prodi/edit.php';
    }

    public function update()
    {
        $this->model->update($_POST['id'] ?? 0, [
            'kode' => $_POST['kode'] ?? '',
            'nama' => $_POST['nama'] ?? ''
        ]);

        header('Location: ' . app_url('prodi'));
        exit;
    }

    public function destroy()
    {
        $this->model->delete($_POST['id'] ?? 0);

        header('Location: ' . app_url('prodi'));
        exit;
    }
}