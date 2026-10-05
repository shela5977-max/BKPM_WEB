<?php

require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/Prodi.php';

class MahasiswaController
{
    private $model;
    private $prodiModel;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->model = $repository;
        $this->prodiModel = new \Prodi();
    }

    public function index()
    {
        $search = trim($_GET['search'] ?? '');
        $mahasiswa = $this->model->all($search);

        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    public function create()
    {
        $prodi = $this->prodiModel->all();
        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    public function store()
    {
        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? '',
            'status' => $_POST['status'] ?? 'aktif'
        ];

        $this->model->create($this->makeMahasiswa($data));

        header('Location: ' . app_url('mahasiswa'));
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'];

        $mahasiswa = $this->model->find($id);
        $prodi = $this->prodiModel->all();

        require __DIR__ . '/../Views/mahasiswa/edit.php';
    }

    public function update()
    {
        $id = $_POST['id'] ?? '';

        $data = [
            'nim' => $_POST['nim'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? '',
            'status' => $_POST['status'] ?? 'aktif'
        ];

        $this->model->update($id, $this->makeMahasiswa($data));

        header('Location: ' . app_url('mahasiswa'));
        exit;
    }

    public function destroy()
    {
        $id = $_POST['id'] ?? '';

        $this->model->delete($id);

        header('Location: ' . app_url('mahasiswa'));
        exit;
    }

    public function show($id)
    {
        echo "Detail Mahasiswa ID : " . $id;
    }

    private function makeMahasiswa($data)
    {
        $mahasiswa = new Mahasiswa();
        $mahasiswa->setNim($data['nim']);
        $mahasiswa->setNama($data['nama']);
        $mahasiswa->setEmail($data['email']);
        $mahasiswa->setProdiId($data['prodi_id']);
        $mahasiswa->setAngkatan($data['angkatan']);
        $mahasiswa->setStatus($data['status']);

        return $mahasiswa;
    }
}