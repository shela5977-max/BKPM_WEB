<?php

require_once __DIR__ . '/../Core/Database.php';

class Prodi
{
    public function all()
    {
        $stmt = Database::connect()->prepare('SELECT * FROM prodi ORDER BY id ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = Database::connect()->prepare('SELECT * FROM prodi WHERE id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = Database::connect()->prepare(
            'INSERT INTO prodi (kode, nama) VALUES (?, ?)'
        );

        return $stmt->execute([$data['kode'], $data['nama']]);
    }

    public function update($id, $data)
    {
        $stmt = Database::connect()->prepare(
            'UPDATE prodi SET kode = ?, nama = ? WHERE id = ?'
        );

        return $stmt->execute([$data['kode'], $data['nama'], $id]);
    }

    public function delete($id)
    {
        $stmt = Database::connect()->prepare('DELETE FROM prodi WHERE id = ?');

        return $stmt->execute([$id]);
    }
}