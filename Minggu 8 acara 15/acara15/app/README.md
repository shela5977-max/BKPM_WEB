# API Mahasiswa

API tersedia melalui `/api/mahasiswa`:

- `GET /api/mahasiswa` mengambil seluruh data mahasiswa.
- `GET /api/mahasiswa?id=1` mengambil data berdasarkan ID.
- `POST /api/mahasiswa` menambahkan mahasiswa dengan body JSON:

```json
{
  "nim": "23004",
  "nama": "Dewi",
  "email": "dewi@gmail.com"
}
```

Semua response menggunakan format JSON dengan properti `success`, `message`,
dan `data`. Pada skema database acara 15, `prodi_id` dan `angkatan` juga
diperlukan. Keduanya dapat dikirim sebagai field tambahan; jika tidak dikirim,
API memakai program studi pertama yang tersedia dan tahun berjalan.

Pastikan MySQL aktif dan konfigurasi database pada `../config/database.php`
menunjuk ke database yang tersedia.
Untuk pengujian lokal dengan PHP development server, jalankan dari folder
proyek:

```powershell
php -S 127.0.0.1:8000 -t public public/index.php
```

Kemudian buka `http://127.0.0.1:8000/api/mahasiswa` atau gunakan URL yang sama
di Postman.