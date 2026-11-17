<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Validasi: username tidak boleh duplikat
    public function usernameExists(string $username): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // Validasi: email tidak boleh duplikat
    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): string {
        // Tugas mandiri 1: email harus berformat valid sebelum data disimpan
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid');
        }
        if ($this->usernameExists($data['username'])) {
            throw new DomainException('Username sudah terdaftar');
        }
        if ($this->emailExists($data['email'])) {
            throw new DomainException('Email sudah terdaftar');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
        );
        $stmt->execute([
            ':u' => $data['username'],
            ':e' => $data['email'],
            ':p' => $data['password'],
        ]);
        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }
}
