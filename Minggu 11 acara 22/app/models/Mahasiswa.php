<?php
class Mahasiswa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Response 500 seragam untuk kegagalan database.
    // Detail teknis hanya ditampilkan pada mode development (tidak bocor ke client saat production).
    private function failServer(PDOException $e): void {
        $errors = (defined('APP_ENV') && APP_ENV === 'development')
            ? ['database' => [$e->getMessage()]]
            : [];
        Response::json(500, false, 'Terjadi kesalahan pada server', null, $errors);
    }

    public function getAll(): array {
        return $this->db->query(
            'SELECT m.*, p.nama AS prodi_nama
             FROM mahasiswa m
             JOIN prodi p ON m.prodi_id = p.id
             ORDER BY m.id DESC'
        )->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Validasi: NIM tidak boleh duplikat (abaikan record milik $excludeId saat update)
    public function nimExists(string $nim, ?int $excludeId = null): bool {
        $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
        $params = [':nim' => $nim];

        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params[':id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    // Validasi: prodi_id harus ada pada tabel prodi
    public function prodiExists(int $prodiId): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM prodi WHERE id = :id');
        $stmt->execute([':id' => $prodiId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): string {
        if ($this->nimExists($data['nim'])) {
            throw new DomainException('NIM sudah terdaftar');
        }
        if (!$this->prodiExists((int) $data['prodi_id'])) {
            throw new InvalidArgumentException('Program studi tidak ditemukan');
        }

        // Acara 20 - Langkah 5: kegagalan database ditangkap dan dijadikan response 500
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
                 VALUES (:nim, :nama, :email, :prodi_id, :angkatan)'
            );
            $stmt->execute([
                ':nim'      => $data['nim'],
                ':nama'     => $data['nama'],
                ':email'    => $data['email'],
                ':prodi_id' => (int) $data['prodi_id'],
                ':angkatan' => (int) $data['angkatan'],
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            $this->failServer($e);
        }
    }

    public function update(int $id, array $data): void {
        if ($this->nimExists($data['nim'], $id)) {
            throw new DomainException('NIM sudah terdaftar');
        }
        if (!$this->prodiExists((int) $data['prodi_id'])) {
            throw new InvalidArgumentException('Program studi tidak ditemukan');
        }

        try {
            $stmt = $this->db->prepare(
                'UPDATE mahasiswa
                 SET nim = :nim, nama = :nama, email = :email,
                     prodi_id = :prodi_id, angkatan = :angkatan
                 WHERE id = :id'
            );
            $stmt->execute([
                ':nim'      => $data['nim'],
                ':nama'     => $data['nama'],
                ':email'    => $data['email'],
                ':prodi_id' => (int) $data['prodi_id'],
                ':angkatan' => (int) $data['angkatan'],
                ':id'       => $id,
            ]);
        } catch (PDOException $e) {
            $this->failServer($e);
        }
    }

    public function delete(int $id): void {
        try {
            $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            $this->failServer($e);
        }
    }
}
