<?php

require_once __DIR__ . '/../Repositories/MahasiswaRepository.php';

class MahasiswaService
{
    private $repository;

    public function __construct(MahasiswaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Mahasiswa $mahasiswa)
    {
        $errors = $this->validate($mahasiswa);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $this->repository->create($mahasiswa);

        return [
            'success' => true
        ];
    }

    public function update($id, Mahasiswa $mahasiswa)
    {
        $errors = $this->validate($mahasiswa);

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $this->repository->update($id, $mahasiswa);

        return [
            'success' => true
        ];
    }

    private function validate(Mahasiswa $mahasiswa)
    {
        $errors = [];

        if (empty($mahasiswa->getNim())) {
            $errors[] = "NIM wajib diisi";
        }

        if (empty($mahasiswa->getNama())) {
            $errors[] = "Nama wajib diisi";
        }

        if (empty($mahasiswa->getEmail())) {
            $errors[] = "Email wajib diisi";
        }

        return $errors;
    }
}