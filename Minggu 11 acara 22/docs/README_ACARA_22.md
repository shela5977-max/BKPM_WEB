# Acara 22 — Dokumentasi Endpoint dengan OpenAPI 3.0

Folder ini merupakan kelanjutan proyek Acara 21. File aplikasi PHP, database, dan dokumentasi Markdown Acara 21 dipertahankan. Penambahan utama Acara 22 adalah `docs/openapi.yaml`.

## Isi pekerjaan
- Membuat spesifikasi OpenAPI 3.0.
- Mendokumentasikan endpoint Auth: `POST /register` dan `POST /login`.
- Mendokumentasikan CRUD Mahasiswa: `GET`, `POST`, `PUT`, `DELETE /mahasiswa`.
- Mendokumentasikan CRUD Buku: `GET`, `POST`, `PUT`, `DELETE /buku`.
- Menambahkan `components.securitySchemes` HTTP Bearer JWT.
- Menambahkan skema request, parameter query `id`, response, dan contoh JSON.

## Cara membuka Swagger Editor
1. Jalankan proyek di Laragon seperti Acara 21.
2. Buka https://editor.swagger.io
3. Buka file `docs/openapi.yaml` di VS Code.
4. Salin seluruh isi `openapi.yaml`.
5. Tempelkan ke panel kiri Swagger Editor.
6. Periksa panel kanan. Jika spesifikasi valid, endpoint akan tampil dalam kelompok Auth, Mahasiswa, dan Buku.
7. Simpan screenshot tampilan Swagger Editor ke folder `docs/` sebagai bukti praktikum.

## Base URL
`http://localhost/BKPM_WEB/Minggu%2011%20acara%2022`

Jika nama folder proyek di komputer berbeda, sesuaikan nilai `servers.url` di `openapi.yaml`.

## Catatan autentikasi
- `POST /mahasiswa`, `PUT /mahasiswa?id={id}`, dan `DELETE /mahasiswa?id={id}` memerlukan JWT Bearer.
- Dapatkan token dari `POST /login`.
- Token berlaku 300 detik (5 menit), sesuai `Auth::TOKEN_TTL`.
- Endpoint Buku dan Auth bersifat publik sesuai implementasi controller Acara 21.

## Catatan tugas mandiri BKPM
BKPM meminta melengkapi `PUT` dan `DELETE /mahasiswa`, `POST /register`, serta menambahkan `securitySchemes` HTTP/Bearer. Semua bagian tersebut sudah dimasukkan. Endpoint Buku juga didokumentasikan karena tersedia di proyek Acara 21.
