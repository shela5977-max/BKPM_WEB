<?php

require_once __DIR__ . '/../Core/Database.php';

class Mahasiswa
{
    public function all($search = '')
    {
        $db = Database::connect();

        $stmt = $db->prepare(" 
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
        $db = Database::connect();

        $stmt = $db->prepare("
            SELECT *
            FROM mahasiswa
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['nim'],
            $data['nama'],
            $data['email'],
            $data['prodi_id'],
            $data['angkatan'],
            $data['status']
        ]);
    }

    public function update($id, $data)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            UPDATE mahasiswa
            SET nim = ?, nama = ?, email = ?, prodi_id = ?, angkatan = ?, status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['nim'],
            $data['nama'],
            $data['email'],
            $data['prodi_id'],
            $data['angkatan'],
            $data['status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $db = Database::connect();

        $stmt = $db->prepare("
            DELETE FROM mahasiswa
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }
}