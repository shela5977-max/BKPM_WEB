# TEST CASE - Acara 23 (Manual Testing & Blackbox Testing)

Objek uji: kode **Minggu 10 acara 20** (AuthController + MahasiswaController, JWT 5 menit).
Alat: Postman (koleksi `postman/Acara23_Blackbox_Testing.postman_collection.json`), environment **Local**.
Teknik: Equivalence Partitioning.

> Kolom **Prediksi kode** diperoleh dari membaca kode (analisis statis), BUKAN hasil eksekusi.
> Kolom **Hasil Aktual** dan **Status** harus diisi dari hasil run Postman Anda sendiri.

## 1. Ruang lingkup (peta Router)

| Endpoint | Method | Controller::aksi | Auth |
|---|---|---|---|
| /register | POST | AuthController::register | - |
| /login | POST | AuthController::login | - |
| /mahasiswa | GET | MahasiswaController::index | publik |
| /mahasiswa | POST | MahasiswaController::store | Bearer token |
| /mahasiswa?id=N | PUT | MahasiswaController::update | Bearer token |
| /mahasiswa?id=N | DELETE | MahasiswaController::destroy | Bearer token |

## 2. Partisi ekuivalen

| Input | Partisi valid | Partisi tidak valid |
|---|---|---|
| nim | 9 digit angka, belum terdaftar | kosong; bukan angka (mis. `ABCDEFGHI`); duplikat milik data lain |
| id (update/destroy) | angka, data ada | angka, data tidak ada (`999999`); campuran angka+huruf (`5abc`) |
| token | token sah, belum kedaluwarsa | tidak ada; signature diubah |
| password login | sesuai akun | salah |

## 3. Test case

Data uji: A = NIM `220100123`, B = `220100456`, C = `220100789` (prodi_id 1, angkatan 2022).

| ID | Skenario | Data Uji | Hasil Diharapkan | Prediksi kode (sebelum perbaikan) | Hasil Aktual | Status |
|---|---|---|---|---|---|---|
| TC01 | Store NIM valid dan lengkap | nim=220100123 | 201 Created | 201 | | |
| TC02 | Store NIM kosong | nim="" | 400/422 | 400 | | |
| TC03 | Store NIM duplikat | nim milik A | 422 | **409** (tidak sesuai) | | |
| TC04 | Login kredensial benar | budi / rahasia123 | 200 + token | 200 | | |
| TC05 | Login password salah | password salah | 401 | 401 | | |
| TC06 | Store tanpa token | tanpa header Authorization | 401 | 401 | | |
| TC07 | Update data valid | id=A, nama diubah | 200 | 200 | | |
| TC08 | Update NIM kosong | id=B, nim="" | 400 | 400 | | |
| TC09 | Update NIM 9 huruf | id=B, nim=ABCDEFGHI | 422 | **200** (lolos, hanya cek panjang) | | |
| TC10 | Update NIM milik data lain | id=B, nim=milik A | 422 | **409** (tidak sesuai) | | |
| TC11 | Update id tidak ada | id=999999 | 404 | 404 | | |
| TC12 | Update tanpa token | id=A, tanpa token | 401 | 401 | | |
| TC13 | Update dengan NIM milik sendiri | id=A, nim tetap | 200 | 200 | | |
| TC14 | Destroy tanpa token | id=B | 401 | 401 | | |
| TC15 | Destroy id tidak ada | id=999999 | 404 | 404 | | |
| TC16 | Destroy id campuran angka+huruf | id=`{C}abc` | 404, data C tetap ada | **200, data C terhapus** (`(int)"5abc"` = 5) | | |
| TC17 | Destroy token diubah | id=B, signature diubah | 401 | 401 | | |
| TC18 | Destroy id valid | id=B | 200 | 200 | | |
| TC19 | Destroy ulang id yang sama | id=B | 404 | 404 | | |

TC07-TC19 adalah test case baru untuk Tugas Praktik Mandiri 1 (update dan destroy, 13 kasus).
Cara membaca urutan: jalankan koleksi dari atas ke bawah; token berlaku 5 menit, jadi jalankan semuanya dalam satu run.

## 4. Temuan Fail dan perbaikan

Berdasarkan analisis kode, 4 test case diperkirakan Fail. Pastikan dengan menjalankannya dulu di Postman.

| Temuan | Test case | Penyebab | Perbaikan (file) |
|---|---|---|---|
| NIM duplikat memberi 409, bukan 422 | TC03, TC10 | `catch (DomainException)` mengirim 409 | `MahasiswaController::store` dan `::update`: kirim 422 dengan `errors.nim` |
| NIM huruf lolos validasi | TC09 | Hanya `strlen($nim) === 9`, tidak cek angka | `MahasiswaController::validateRules`: tambah `ctype_digit` |
| id `5abc` dibaca sebagai 5 | TC16 | `(int) "5abc"` bernilai 5 | `MahasiswaController::update` dan `::destroy`: tolak id yang bukan angka murni |

Catatan keputusan: HTTP 409 untuk duplikat sebenarnya juga umum dipakai. Perbaikan ke 422 dilakukan karena tabel test case acara 23 menetapkan 422 sebagai hasil yang diharapkan. Jika dosen menerima 409, cukup ubah kolom Hasil Diharapkan, tidak perlu mengubah kode.

## 5. Retest setelah perbaikan

Jalankan ulang koleksi memakai kode di folder ini (sudah diperbaiki). Hasil yang diharapkan: TC01-TC19 semua Pass.
Isi tabel retest ini dari hasil run Anda.

| ID | Hasil Aktual (retest) | Status |
|---|---|---|
| TC03 | | |
| TC09 | | |
| TC10 | | |
| TC16 | | |
| TC01-TC02, TC04-TC08, TC11-TC15, TC17-TC19 | | |

## 6. Batasan pengujian

- `index` (GET) masih memakai `(int) $_GET['id']`, sehingga `?id=5abc` dibaca sebagai 5. Tidak termasuk ruang lingkup tugas, tetapi layak dicatat.
- Aturan `angkatan` hanya memeriksa angka (tidak ada rentang tahun); tipe kolomnya ada di database acara 18 yang tidak ikut diunggah, jadi tidak diuji.
