<?php

require_once __DIR__ . '/../Models/Mahasiswa.php';
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
        try {

            $errors = $this->validate($mahasiswa);

            if (!empty($errors)) {
                return [
                    'success' => false,
                    'errors' => $errors
                ];
            }

            if ($this->repository->existsByNim($mahasiswa->getNim())) {
                return [
                    'success' => false,
                    'errors' => ['NIM sudah terdaftar']
                ];
            }

            $this->repository->create($mahasiswa);

            return [
                'success' => true
            ];

        } catch (Exception $e) {

            error_log(
                date('Y-m-d H:i:s') .
                ' - ' .
                $e->getMessage() .
                PHP_EOL,
                3,
                __DIR__ . '/../storage/logs/app.log'
            );

            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    public function update($id, Mahasiswa $mahasiswa)
    {
        try {

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

        } catch (Exception $e) {

            error_log(
                date('Y-m-d H:i:s') .
                ' - ' .
                $e->getMessage() .
                PHP_EOL,
                3,
                __DIR__ . '/../storage/logs/app.log'
            );

            return [
                'success' => false,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    private function validate(Mahasiswa $mahasiswa)
    {
        $errors = [];

        if (empty($mahasiswa->getNim())) {
            $errors[] = 'NIM wajib diisi';
        }

        if (empty($mahasiswa->getNama())) {
            $errors[] = 'Nama wajib diisi';
        }

        if (empty($mahasiswa->getEmail())) {
            $errors[] = 'Email wajib diisi';
        }

        return $errors;
    }
}