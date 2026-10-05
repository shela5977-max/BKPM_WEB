<?php

require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function all($search = '')
    {
        $stmt = $this->database->getConnection()->prepare(" 
            SELECT m.*, p.nama AS prodi_nama
            FROM mahasiswa m
            JOIN prodi p ON m.prodi_id = p.id
            WHERE m.nama LIKE ? OR m.nim LIKE ?
            ORDER BY m.id ASC
        ");

        $term = '%' . $search . '%';
        $stmt->execute([$term, $term]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->database->getConnection()->prepare(
            'SELECT * FROM mahasiswa WHERE id = ?'
        );
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(Mahasiswa $mahasiswa)
    {
        $stmt = $this->database->getConnection()->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status) VALUES (?, ?, ?, ?, ?, ?)'
        );

        return $stmt->execute($this->values($mahasiswa));
    }

    public function update($id, Mahasiswa $mahasiswa)
    {
        $stmt = $this->database->getConnection()->prepare(
            'UPDATE mahasiswa SET nim = ?, nama = ?, email = ?, prodi_id = ?, angkatan = ?, status = ? WHERE id = ?'
        );

        return $stmt->execute(array_merge($this->values($mahasiswa), [$id]));
    }

    public function delete($id)
    {
        $stmt = $this->database->getConnection()->prepare(
            'DELETE FROM mahasiswa WHERE id = ?'
        );

        return $stmt->execute([$id]);
    }

    private function values(Mahasiswa $mahasiswa)
    {
        return [
            $mahasiswa->getNim(),
            $mahasiswa->getNama(),
            $mahasiswa->getEmail(),
            $mahasiswa->getProdiId(),
            $mahasiswa->getAngkatan(),
            $mahasiswa->getStatus()
        ];
    }
}