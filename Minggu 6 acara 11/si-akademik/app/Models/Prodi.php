<?php

require_once __DIR__ . '/BaseModel.php';

class Prodi extends BaseModel
{
    public function all()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM prodi ORDER BY id ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM prodi WHERE id = ?');
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO prodi (kode, nama) VALUES (?, ?)'
        );

        return $stmt->execute([$data['kode'], $data['nama']]);
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare(
            'UPDATE prodi SET kode = ?, nama = ? WHERE id = ?'
        );

        return $stmt->execute([$data['kode'], $data['nama'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM prodi WHERE id = ?');

        return $stmt->execute([$id]);
    }
}