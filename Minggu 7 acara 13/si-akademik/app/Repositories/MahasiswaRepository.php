<?php

require_once __DIR__ . '/../Models/BaseModel.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaRepository extends BaseModel
{
    public function __construct(Database $database)
    {
        parent::__construct($database->getConnection());
    }

    public function all($search = '')
    {
        $stmt = $this->pdo->prepare(" 
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
        $stmt = $this->pdo->prepare(
            'SELECT * FROM mahasiswa WHERE id = ?'
        );
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(Mahasiswa $mahasiswa)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status) VALUES (?, ?, ?, ?, ?, ?)'
        );

        return $stmt->execute($this->values($mahasiswa));
    }

    public function update($id, Mahasiswa $mahasiswa)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE mahasiswa SET nim = ?, nama = ?, email = ?, prodi_id = ?, angkatan = ?, status = ? WHERE id = ?'
        );

        return $stmt->execute(array_merge($this->values($mahasiswa), [$id]));
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare(
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