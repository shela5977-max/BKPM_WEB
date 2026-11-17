# Tugas Praktik Mandiri - Acara 21

## 1. Tabel ringkasan seluruh endpoint
Sudah dicantumkan pada `docs/API_DOCUMENTATION.md` bagian 1.2. Tabel memuat 12 endpoint: 2 endpoint autentikasi, 5 endpoint mahasiswa, dan 5 endpoint buku. Setiap baris mencantumkan HTTP method, URL, Controller::method, status autentikasi, fungsi, dan status sukses. Peta Router (resource → Controller) dicantumkan pada bagian 1.1.

## 2. Dokumentasi endpoint
Dokumentasi lengkap tersedia pada `docs/API_DOCUMENTATION.md`, termasuk Base URL, header, format JSON response, kode status HTTP, autentikasi JWT, request body, dan contoh response sukses/gagal.

## 3. Koleksi Postman
Koleksi tersedia pada `postman/API_Sistem_Informasi_Akademik_MVC.postman_collection.json`. Koleksi mencakup folder Auth, Mahasiswa, Uji Autentikasi, dan Buku. Folder Buku dilengkapi lima request (GET semua, GET berdasarkan id, POST, PUT, DELETE) beserta contoh response.

## 4. Environment Postman
Environment lokal tersedia pada `postman/Local.postman_environment.json` dengan `base_url` untuk folder Acara 21 dan variabel `token` untuk pengujian endpoint mahasiswa yang terproteksi.
