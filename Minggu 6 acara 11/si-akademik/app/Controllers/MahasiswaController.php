<?php

require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/Prodi.php';
require_once __DIR__ . '/BaseController.php';

class MahasiswaController extends BaseController
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

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa,
            'search' => $search
        ]);
    }

    public function create()
    {
        $prodi = $this->prodiModel->all();
        $this->view('mahasiswa/create', ['prodi' => $prodi]);
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

        $this->redirect('mahasiswa');
    }

    public function edit()
    {
        $id = $_GET['id'];

        $mahasiswa = $this->model->find($id);
        $prodi = $this->prodiModel->all();

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodi' => $prodi
        ]);
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

        $this->redirect('mahasiswa');
    }

    public function destroy()
    {
        $id = $_POST['id'] ?? '';

        $this->model->delete($id);

        $this->redirect('mahasiswa');
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