# Acara 25 - Authentication, Authorization dan Dashboard Admin
Melanjutkan proyek Acara 24 (folder ini adalah salinan Acara 24 + perubahan di bawah).

## Perubahan
- app/core/Auth.php                    : + `requireAdmin()` (401 jika token tidak sah, 403 jika bukan admin)
- app/controllers/MahasiswaController  : store/update/destroy memakai `Auth::requireAdmin()`
- public/index.php                     : + jawaban preflight OPTIONS (204)
- public/views/login.html, login.js    : form login, simpan token & role di localStorage
- public/views/dashboard.html, .js     : guard token, ringkasan total, CRUD, Logout, sembunyikan tombol untuk role user
- public/views/config.js, style.css    : BASE_URL dan gaya bersama
- database/seed_admin.sql, postman/*   : persiapan akun admin & uji RBAC

## Cara menjalankan (XAMPP)
1. Salin folder ke htdocs/BKPM_WEB/, nyalakan Apache + MySQL.
2. Daftarkan akun (POST /register), lalu jalankan database/seed_admin.sql.
3. Buka http://localhost/BKPM_WEB/Minggu%2013%20acara%2025/public/views/login.html
