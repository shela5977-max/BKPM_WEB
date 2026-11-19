-- Acara 25: menyiapkan akun untuk pengujian RBAC.
-- Jalankan di phpMyAdmin pada database si-akademik1.
-- Kolom role sudah ada sejak acara 19 (ENUM 'admin','user', default 'user').
--
-- Langkah:
-- 1) Daftarkan dua akun lewat API (Postman: POST /register), mis.
--      admin1 / admin123  (akan menjadi admin)
--      budi   / rahasia123 (tetap role user)
--    Password di-hash oleh AuthController::register(), jadi jangan di-INSERT manual.
-- 2) Naikkan role akun admin1 menjadi admin:
USE `si-akademik1`;

UPDATE users SET role = 'admin' WHERE username = 'admin1';

-- 3) Cek hasilnya:
SELECT id, username, email, role FROM users;
