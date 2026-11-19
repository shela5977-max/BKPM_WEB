# Postman - Acara 25 (Authentication, Authorization, RBAC)
1. Import file `Acara25_RBAC.postman_collection.json` dan `Acara25.postman_environment.json`, pilih Environment "Acara 25".
2. Daftarkan akun admin1 dan budi (Register), lalu jalankan `database/seed_admin.sql` agar admin1 berole admin.
3. Jalankan folder berurutan: Login admin -> Login user -> uji RBAC.
4. Hasil yang diharapkan:
   - POST /mahasiswa dengan token **user**  -> 403 "Akses ditolak, hanya admin yang diizinkan"
   - POST /mahasiswa tanpa token           -> 401 "Token tidak ditemukan"
   - POST /mahasiswa dengan token **admin** -> 201
   - GET /mahasiswa tanpa token             -> 200 (GET tetap publik)
5. Token berlaku 5 menit (Auth::TOKEN_TTL = 300); setelah itu login ulang.
