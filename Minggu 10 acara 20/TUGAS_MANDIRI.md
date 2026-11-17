# Tugas Praktik Mandiri - Acara 20

## 1. Mengapa JWT stateless tidak punya logout di sisi server?
JWT bersifat *self-contained*: semua informasi (id, role, exp) dan tanda tangannya ada di dalam token.
Server hanya memverifikasi signature + exp pada tiap request dan **tidak menyimpan apa pun**
(tidak ada tabel sesi). Akibatnya server tidak punya "catatan login" yang bisa dihapus,
sehingga token yang sudah diterbitkan tetap sah sampai `exp`, walaupun user menekan "logout"
(logout hanya menghapus token di sisi client).

Jika token bocor, penyerang tetap dapat memakainya sampai kedaluwarsa. Solusi tambahan:
- **Blacklist token** (simpan token/`jti` yang dicabut di DB/cache, cek tiap request) - ini mengorbankan sifat stateless;
- **Masa berlaku pendek** (mis. 5 menit) + *refresh token* yang bisa dicabut;
- Mengubah SECRET (mencabut seluruh token sekaligus, dampaknya ke semua user).

**Kesimpulan:** stateless = skalabel dan sederhana, tetapi pencabutan token sebelum `exp` butuh
mekanisme tambahan; karena itu masa berlaku token sebaiknya singkat.

## 2. Token 5 menit
Diterapkan pada `Auth::TOKEN_TTL = 300` (sebelumnya 3600). Response login juga menyertakan `expires_in: 300`.
Pengamatan: request dengan token yang berumur >5 menit menghasilkan
`401 - Token tidak valid atau kedaluwarsa`, sehingga client harus login ulang.
