<?php

require_once __DIR__ . '/../Core/Database.php';

class MataKuliah
{
    public function all()
    {
        $stmt = Database::connect()->prepare(
            'SELECT m.*, p.nama AS prodi_nama
             FROM matakuliah m
             JOIN prodi p ON m.prodi_id = p.id
             ORDER BY m.id ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = Database::connect()->prepare('SELECT * FROM matakuliah WHERE id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES (?, ?, ?, ?)'
        );

        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['sks'],
            $data['prodi_id']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = Database::connect()->prepare(
            'UPDATE matakuliah SET kode = ?, nama = ?, sks = ?, prodi_id = ? WHERE id = ?'
        );

        return $stmt->execute([
            $data['kode'],
            $data['nama'],
            $data['sks'],
            $data['prodi_id'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = Database::connect()->prepare('DELETE FROM matakuliah WHERE id = ?');

        return $stmt->execute([$id]);
    }
}