-- Jalankan di phpMyAdmin pada database si-akademik1 (database acara sebelumnya)
USE `si-akademik1`;

CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    tahun_terbit YEAR NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Opsional: lapisan pengaman di level database agar NIM tidak bisa duplikat
-- (jalankan hanya jika belum ada UNIQUE pada kolom nim)
-- ALTER TABLE mahasiswa ADD UNIQUE (nim);
