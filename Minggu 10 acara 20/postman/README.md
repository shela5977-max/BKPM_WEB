# Postman - Acara 20
1. Import tiga file .json di folder ini, pilih Environment "Local".
2. Tidak ada tabel baru: pakai database si-akademik1 dan tabel users dari acara 19.
3. Pastikan sudah ada akun (Auth > Register - sukses, username budi / password rahasia123).
4. Auth > Login - sukses: token tersimpan otomatis ke {{token}}.
5. Folder Mahasiswa: POST/PUT/DELETE otomatis membawa header Authorization: Bearer {{token}}.
6. Folder "Uji Autentikasi": tanpa token -> 401, token diubah -> 401, token sah -> sukses.
7. Token berlaku 5 menit (tugas mandiri 2). Tunggu >5 menit lalu kirim ulang untuk melihat 401 kedaluwarsa.
