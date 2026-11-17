# Dokumentasi API — Sistem Informasi Akademik (Smart Village)

Dokumentasi endpoint untuk web backend PHP native (arsitektur MVC) pada **Acara 21 — Dokumentasi Endpoint**.
Dokumen ini disusun dari seluruh Controller yang sudah dibangun pada acara 18–20:
`AuthController` (register, login), `MahasiswaController` (index, store, update, destroy),
dan `BukuController` (index, store, update, destroy).

| Item | Nilai |
|---|---|
| Base URL (lokal) | `http://localhost/BKPM_WEB/Minggu%2011%20acara%2021` |
| Format data | JSON (`Content-Type: application/json`) |
| Autentikasi | JWT HS256 — header `Authorization: Bearer <token>` |
| Masa berlaku token | 300 detik (5 menit) |
| Database | MySQL `si-akademik1` (tabel `mahasiswa`, `prodi`, `users`, `buku`) |

---

## 1. Peta Router dan Ringkasan Endpoint

### 1.1 Peta Router (Resource → Controller)

Router (`app/core/Router.php`) membaca segmen terakhir URL sebagai *resource*, lalu meneruskannya ke Controller.

| Resource (URL) | Controller | Keterangan |
|---|---|---|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (`index` / `store` / `update` / `destroy`) |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token JWT |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru |
| `/buku` | `BukuController` | CRUD data buku (`index` / `store` / `update` / `destroy`) |
| `/` , `/ping` | `Router::dispatch` (tanpa Controller) | Cek koneksi — mengembalikan daftar endpoint |

Pemetaan HTTP method → method Controller untuk resource CRUD (`/mahasiswa` dan `/buku`):

| HTTP Method | Method Controller | Keterangan |
|---|---|---|
| `GET` | `index()` | Ambil semua data, atau satu data jika ada `?id=` |
| `POST` | `store()` | Tambah data baru |
| `PUT` | `update($id)` | Ubah data (id dari query string `?id=`) |
| `DELETE` | `destroy($id)` | Hapus data (id dari query string `?id=`) |

### 1.2 Tabel Ringkasan Seluruh Endpoint (Tugas Praktik Mandiri)

| No | Method | URL | Controller::method | Auth | Fungsi | Status sukses |
|---|---|---|---|---|---|---|
| 1 | `POST` | `/register` | `AuthController::register()` | Publik | Mendaftarkan pengguna baru | 201 |
| 2 | `POST` | `/login` | `AuthController::login()` | Publik | Login, menghasilkan token JWT | 200 |
| 3 | `GET` | `/mahasiswa` | `MahasiswaController::index()` | Publik | Daftar semua mahasiswa | 200 |
| 4 | `GET` | `/mahasiswa?id={id}` | `MahasiswaController::index()` | Publik | Detail satu mahasiswa | 200 |
| 5 | `POST` | `/mahasiswa` | `MahasiswaController::store()` | **Terproteksi** | Menambah mahasiswa | 201 |
| 6 | `PUT` | `/mahasiswa?id={id}` | `MahasiswaController::update()` | **Terproteksi** | Mengubah mahasiswa | 200 |
| 7 | `DELETE` | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | **Terproteksi** | Menghapus mahasiswa | 200 |
| 8 | `GET` | `/buku` | `BukuController::index()` | Publik | Daftar semua buku | 200 |
| 9 | `GET` | `/buku?id={id}` | `BukuController::index()` | Publik | Detail satu buku | 200 |
| 10 | `POST` | `/buku` | `BukuController::store()` | Publik | Menambah buku | 201 |
| 11 | `PUT` | `/buku?id={id}` | `BukuController::update()` | Publik | Mengubah buku | 200 |
| 12 | `DELETE` | `/buku?id={id}` | `BukuController::destroy()` | Publik | Menghapus buku | 200 |

> **Keterangan Auth:** *Terproteksi* = wajib header `Authorization: Bearer <token>`
> (dicek oleh `Auth::requireAuth()` di awal method). *Publik* = tidak memerlukan token.

---

## 2. Konvensi Umum

### 2.1 Format Response (Envelope)

Semua response (sukses maupun gagal) memakai format yang sama, dibentuk oleh `Response::json()`:

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

| Field | Tipe | Keterangan |
|---|---|---|
| `success` | boolean | `true` jika request berhasil |
| `message` | string | Pesan singkat untuk client |
| `data` | object / array / null | Data hasil; `null` jika tidak ada |
| `errors` | object / array | Kesalahan validasi per field, mis. `{"nim": ["..."]}`; `[]` jika tidak ada |
| `timestamp` | string | Waktu response, format ISO 8601 |

> Pada mode `development` JSON dicetak rapi (*pretty print*); pada `production` dicetak ringkas.

### 2.2 Header

| Header | Wajib pada | Nilai |
|---|---|---|
| `Content-Type` | `POST`, `PUT` | `application/json` |
| `Accept` | (disarankan) | `application/json` |
| `Authorization` | `POST`/`PUT`/`DELETE` `/mahasiswa` | `Bearer <token>` |

### 2.3 Kode Status HTTP

| Kode | Arti | Contoh penyebab |
|---|---|---|
| 200 | OK | Data berhasil diambil / diperbarui / dihapus; login berhasil |
| 201 | Created | Data atau akun berhasil dibuat |
| 400 | Bad Request | Body bukan JSON valid, atau field wajib kosong |
| 401 | Unauthorized | Token tidak ada / tidak valid / kedaluwarsa; login salah |
| 404 | Not Found | Data dengan `id` tidak ada; endpoint tidak dikenal |
| 405 | Method Not Allowed | Method HTTP tidak didukung endpoint |
| 409 | Conflict | Data duplikat (NIM, username, email) |
| 422 | Unprocessable Entity | Format/aturan data tidak valid |
| 500 | Internal Server Error | Kegagalan tak terduga / database |

### 2.3.1 Response Kesalahan Umum (berlaku untuk semua endpoint)

**404 Not Found** — resource tidak dikenal (mis. `GET /xyz`):

```json
{ "success": false, "message": "Endpoint tidak ditemukan", "data": null, "errors": [], "timestamp": "..." }
```

**405 Method Not Allowed** — method di luar `GET/POST/PUT/DELETE` (mis. `PATCH /mahasiswa`):

```json
{ "success": false, "message": "Method tidak diizinkan", "data": null, "errors": [], "timestamp": "..." }
```

**500 Internal Server Error** — kegagalan tak terduga atau database. Pada mode `development`,
detail teknis ada di `errors`; pada `production` `errors` kosong.

```json
{
  "success": false,
  "message": "Terjadi kesalahan pada server",
  "data": null,
  "errors": { "database": ["SQLSTATE[HY000] [2002] ..."] },
  "timestamp": "..."
}
```

### 2.4 Autentikasi (JWT)

1. Daftar via `POST /register`, lalu login via `POST /login` untuk mendapat `token`.
2. Kirim token pada endpoint terproteksi: `Authorization: Bearer <token>`.
3. Token berisi payload `id`, `role`, dan `exp`; berlaku **300 detik**. Setelah itu server membalas
   `401` dan client harus login ulang.

Response `401` pada endpoint terproteksi:

| Kondisi | `message` |
|---|---|
| Header `Authorization` tidak ada / bukan format `Bearer` | `Token tidak ditemukan` |
| Tanda tangan salah, token diubah, atau kedaluwarsa | `Token tidak valid atau kedaluwarsa` |

```json
{ "success": false, "message": "Token tidak ditemukan", "data": null, "errors": [], "timestamp": "..." }
```

---

## 3. Endpoint Autentikasi

### 3.1 POST /register

**Controller:** `AuthController::register()` &nbsp;|&nbsp; **Autentikasi:** Publik

Mendaftarkan pengguna baru. Password disimpan dalam bentuk hash (`password_hash`), bukan plain text.
Role bawaan akun baru adalah `user`.

**Headers:**

```
Content-Type: application/json
```

**Request Body:**

| Field | Tipe | Wajib | Aturan |
|---|---|---|---|
| `username` | string | Ya | Unik |
| `email` | string | Ya | Format email valid, unik |
| `password` | string | Ya | — |

```json
{ "username": "andi", "email": "andi@example.com", "password": "rahasia123" }
```

**Response 201 Created:**

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": { "id": "1" },
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

**Response 400 Bad Request** — field kosong:

```json
{
  "success": false,
  "message": "Username, email, dan password wajib diisi",
  "data": null,
  "errors": {
    "email": ["Field email wajib diisi"],
    "password": ["Field password wajib diisi"]
  },
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

Body yang bukan JSON valid juga menghasilkan `400` dengan `message`: `Body request harus berupa JSON yang valid`.

**Response 422 Unprocessable Entity** — format email tidak valid:

```json
{
  "success": false,
  "message": "Format email tidak valid",
  "data": null,
  "errors": { "email": ["Format email tidak valid"] },
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

**Response 409 Conflict** — username atau email sudah dipakai:

```json
{
  "success": false,
  "message": "Username sudah terdaftar",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

`message` dapat berupa `Username sudah terdaftar` atau `Email sudah terdaftar`.

---

### 3.2 POST /login

**Controller:** `AuthController::login()` &nbsp;|&nbsp; **Autentikasi:** Publik

Memverifikasi username dan password, lalu menghasilkan token JWT (HS256) untuk endpoint terproteksi.
Method selain `POST` ditolak dengan `405`.

**Headers:**

```
Content-Type: application/json
```

**Request Body:**

| Field | Tipe | Wajib |
|---|---|---|
| `username` | string | Ya |
| `password` | string | Ya |

```json
{ "username": "andi", "password": "rahasia123" }
```

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjEiLCJyb2xlIjoidXNlciIsImV4cCI6MTc4MzA2NjUwMH0.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "role": "user",
    "expires_in": 300
  },
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

| Field `data` | Keterangan |
|---|---|
| `token` | Token JWT; dikirim sebagai `Bearer <token>` |
| `role` | Role pengguna (`admin` / `user`) |
| `expires_in` | Masa berlaku token dalam detik (300) |

**Response 400 Bad Request** — field kosong:

```json
{
  "success": false,
  "message": "Username dan password wajib diisi",
  "data": null,
  "errors": {
    "username": ["Field username wajib diisi"],
    "password": ["Field password wajib diisi"]
  },
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

**Response 401 Unauthorized** — username atau password salah. Pesan sengaja sama untuk kedua kasus
agar tidak membocorkan informasi akun:

```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

**Response 405 Method Not Allowed** — dikirim dengan method selain `POST` (mis. `GET /login`):

```json
{ "success": false, "message": "Method tidak diizinkan", "data": null, "errors": [], "timestamp": "..." }
```

---

## 4. Endpoint Mahasiswa

Controller: `MahasiswaController`. Method `GET` bersifat **publik**; `POST`, `PUT`, dan `DELETE`
**terproteksi** (`Auth::requireAuth()`) sehingga wajib header `Authorization: Bearer <token>`.

**Struktur data mahasiswa:**

| Field | Tipe | Wajib (POST/PUT) | Aturan validasi |
|---|---|---|---|
| `nim` | string | Ya | Tepat 9 karakter; unik |
| `nama` | string | Ya | — |
| `email` | string | Ya | Format email valid |
| `prodi_id` | integer | Ya | Angka; harus ada pada tabel `prodi` |
| `angkatan` | integer | Ya | Angka |

### 4.1 GET /mahasiswa

**Controller:** `MahasiswaController::index()` &nbsp;|&nbsp; **Autentikasi:** Publik

Mengambil seluruh data mahasiswa (diurutkan dari `id` terbaru) beserta nama program studi (`prodi_nama`).

**Headers:** `Accept: application/json`

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 1,
      "nim": "E41230001",
      "nama": "Andi Pratama",
      "email": "andi@example.com",
      "prodi_id": 1,
      "angkatan": 2023,
      "prodi_nama": "Teknik Informatika"
    }
  ],
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

> Nilai pada contoh (mis. `prodi_nama`) bergantung pada isi database; kolom lain pada tabel
> `mahasiswa` ikut tampil karena query memakai `m.*`.

---

### 4.2 GET /mahasiswa?id={id}

**Controller:** `MahasiswaController::index()` &nbsp;|&nbsp; **Autentikasi:** Publik

Mengambil satu mahasiswa berdasarkan `id`.

| Query | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | ID mahasiswa |

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {
    "id": 1,
    "nim": "E41230001",
    "nama": "Andi Pratama",
    "email": "andi@example.com",
    "prodi_id": 1,
    "angkatan": 2023
  },
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

**Response 404 Not Found:**

```json
{ "success": false, "message": "Data tidak ditemukan", "data": null, "errors": [], "timestamp": "2026-11-17T09:00:00+07:00" }
```

---

### 4.3 POST /mahasiswa

**Controller:** `MahasiswaController::store()` &nbsp;|&nbsp; **Autentikasi:** **Terproteksi** (`Auth::requireAuth()`)

Menambah data mahasiswa baru.

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**

```json
{
  "nim": "E41230001",
  "nama": "Andi Pratama",
  "email": "andi@example.com",
  "prodi_id": 1,
  "angkatan": 2023
}
```

**Response 201 Created:**

```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": { "id": "1" },
  "errors": [],
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

**Response 401 Unauthorized** — token tidak ada / tidak valid / kedaluwarsa (lihat bagian 2.4).

**Response 400 Bad Request** — field wajib kosong atau body bukan JSON:

```json
{
  "success": false,
  "message": "Data yang dikirim tidak lengkap",
  "data": null,
  "errors": {
    "nim": ["Field nim wajib diisi"],
    "nama": ["Field nama wajib diisi"]
  },
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

**Response 422 Unprocessable Entity** — format data tidak valid:

```json
{
  "success": false,
  "message": "Validasi data gagal",
  "data": null,
  "errors": {
    "nim": ["Format NIM tidak valid, harus 9 digit"],
    "email": ["Format email tidak valid"]
  },
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

`422` juga dikirim dengan `message`: `Program studi tidak ditemukan` bila `prodi_id` tidak ada di tabel `prodi`.

**Response 409 Conflict** — NIM sudah terdaftar:

```json
{ "success": false, "message": "NIM sudah terdaftar", "data": null, "errors": [], "timestamp": "2026-11-17T09:00:00+07:00" }
```

---

### 4.4 PUT /mahasiswa?id={id}

**Controller:** `MahasiswaController::update()` &nbsp;|&nbsp; **Autentikasi:** **Terproteksi** (`Auth::requireAuth()`)

Mengubah data mahasiswa. Seluruh field wajib dikirim ulang (bukan *partial update*).

| Query | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | ID mahasiswa yang diubah |

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:** sama dengan `POST /mahasiswa`.

```json
{
  "nim": "E41230001",
  "nama": "Andi Pratama Putra",
  "email": "andi@example.com",
  "prodi_id": 1,
  "angkatan": 2023
}
```

**Response 200 OK:**

```json
{ "success": true, "message": "Data berhasil diperbarui", "data": null, "errors": [], "timestamp": "2026-11-17T09:00:00+07:00" }
```

**Response gagal:**

| Kode | `message` | Penyebab |
|---|---|---|
| 401 | `Token tidak ditemukan` / `Token tidak valid atau kedaluwarsa` | Token bermasalah |
| 404 | `Data tidak ditemukan` | `id` kosong atau tidak ada |
| 400 | `Data yang dikirim tidak lengkap` | Field wajib kosong / body bukan JSON |
| 422 | `Validasi data gagal` / `Program studi tidak ditemukan` | Format data salah |
| 409 | `NIM sudah terdaftar` | NIM dipakai mahasiswa lain |

Bentuk response gagal sama dengan contoh pada bagian 4.3.

---

### 4.5 DELETE /mahasiswa?id={id}

**Controller:** `MahasiswaController::destroy()` &nbsp;|&nbsp; **Autentikasi:** **Terproteksi** (`Auth::requireAuth()`)

Menghapus data mahasiswa.

| Query | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | ID mahasiswa yang dihapus |

**Headers:**

```
Authorization: Bearer <token>
```

**Response 200 OK:**

```json
{ "success": true, "message": "Data berhasil dihapus", "data": null, "errors": [], "timestamp": "2026-11-17T09:00:00+07:00" }
```

**Response gagal:** `401` (token bermasalah, lihat 2.4) dan `404` `Data tidak ditemukan` (id kosong / tidak ada).

---

## 5. Endpoint Buku

Controller: `BukuController`. Seluruh method **publik** (tidak memerlukan token).

**Struktur data buku:**

| Field | Tipe | Wajib (POST/PUT) | Aturan validasi |
|---|---|---|---|
| `judul` | string | Ya | — |
| `penulis` | string | Ya | — |
| `tahun_terbit` | integer | Ya | Angka, rentang 1901–2155 |

| Method & URL | Controller::method | Response sukses | Response gagal |
|---|---|---|---|
| `GET /buku` | `BukuController::index()` | 200 — array buku | — |
| `GET /buku?id={id}` | `BukuController::index()` | 200 — satu buku | 404 `Data tidak ditemukan` |
| `POST /buku` | `BukuController::store()` | 201 — `{ "id": "..." }` | 400, 422 |
| `PUT /buku?id={id}` | `BukuController::update()` | 200 | 404, 400, 422 |
| `DELETE /buku?id={id}` | `BukuController::destroy()` | 200 | 404 |

**Contoh Request Body (POST / PUT):**

```json
{ "judul": "Pemrograman Web dengan PHP", "penulis": "Budi Santoso", "tahun_terbit": 2024 }
```

**Contoh Response 201 Created:**

```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": "1" }, "errors": [], "timestamp": "2026-11-17T09:00:00+07:00" }
```

**Contoh Response 422** — `tahun_terbit` di luar rentang:

```json
{
  "success": false,
  "message": "Validasi data gagal",
  "data": null,
  "errors": { "tahun_terbit": ["tahun_terbit harus berupa tahun yang valid (1901-2155)"] },
  "timestamp": "2026-11-17T09:00:00+07:00"
}
```

---

## 6. Alur Penggunaan (Contoh Skenario)

1. `POST /register` → akun dibuat (201).
2. `POST /login` → menerima `token` (200).
3. `POST /mahasiswa` dengan header `Authorization: Bearer <token>` → data ditambahkan (201).
4. `GET /mahasiswa` → melihat daftar (200, tanpa token).
5. `PUT /mahasiswa?id=1` / `DELETE /mahasiswa?id=1` dengan token → ubah / hapus (200).
6. Setelah lebih dari 5 menit, request terproteksi dijawab `401 Token tidak valid atau kedaluwarsa` → ulangi langkah 2.
