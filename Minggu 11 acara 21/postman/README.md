# Postman - Acara 21 (Dokumentasi Endpoint)

## File yang diimpor
1. `API_Sistem_Informasi_Akademik_MVC.postman_collection.json` — koleksi request seluruh endpoint.
2. `Local.postman_environment.json` — environment Laragon (gunakan ini untuk pengujian lokal).
3. `LocalPHP.postman_environment.json` — environment alternatif PHP lokal.
4. `Staging.postman_environment.json` — environment staging (gunakan hanya jika server staging tersedia).

## Langkah pengujian
1. Jalankan proyek Acara 21 melalui Laragon.
2. Import collection dan `Local.postman_environment.json` ke Postman.
3. Pilih environment **Local**. Pastikan `base_url` menunjuk ke folder Acara 21.
4. Jalankan `Auth > Register - sukses` sekali untuk membuat akun. Jika akun sudah ada, gunakan akun tersebut.
5. Jalankan `Auth > Login - sukses`; token disimpan pada variabel `{{token}}` jika skrip request berhasil.
6. Jalankan request pada folder **Mahasiswa**. Operasi POST, PUT, DELETE mahasiswa memerlukan Bearer token.
7. Jalankan request pada folder **Buku**. Semua operasi Buku bersifat publik dan tidak memerlukan token.
8. Folder **Uji Autentikasi (Acara 20)** berisi pengujian token hilang, token salah, token sah, dan token kedaluwarsa.

## Daftar endpoint
- Auth: `POST /register`, `POST /login`
- Mahasiswa: `GET /mahasiswa`, `GET /mahasiswa?id={id}`, `POST /mahasiswa`, `PUT /mahasiswa?id={id}`, `DELETE /mahasiswa?id={id}`
- Buku: `GET /buku`, `GET /buku?id={id}`, `POST /buku`, `PUT /buku?id={id}`, `DELETE /buku?id={id}`

## Catatan
- Header request JSON menggunakan `Accept: application/json`; request POST/PUT juga menggunakan `Content-Type: application/json`.
- Header `Authorization: Bearer {{token}}` hanya diperlukan untuk POST/PUT/DELETE `/mahasiswa`.
- Contoh response tersimpan pada masing-masing request sebagai Postman Examples. Timestamp pada contoh hanya ilustrasi.
- Klik menu collection lalu **View Documentation** untuk melihat dokumentasi collection.
