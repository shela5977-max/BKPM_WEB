// Ubah BASE_URL sesuai cara menjalankan proyek:
//  - XAMPP (Apache) : alamat folder proyek di htdocs + /public
//  - jalankan.bat   : 'http://localhost:8080'
const BASE_URL = 'http://localhost/BKPM_WEB/Minggu%2012%20acara%2024/public';

const tbody = document.querySelector('#tabel-mahasiswa tbody');

// Menampilkan satu baris pesan (memuat / kosong / gagal) di dalam tabel
function tampilkanPesan(teks, kelas = '') {
    tbody.innerHTML = '';
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.colSpan = 3;
    td.className = 'pesan ' + kelas;
    td.textContent = teks;
    tr.appendChild(td);
    tbody.appendChild(tr);
}

// Mencegah karakter HTML pada data tampil sebagai tag (XSS)
function esc(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

async function muatDataMahasiswa() {
    // Tugas mandiri 1: indikator tampil sebelum data berhasil diambil
    tampilkanPesan('Memuat data...', 'memuat');

    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();

        if (!response.ok || !hasil.success) {
            tampilkanPesan(hasil.message || 'Gagal memuat data.', 'error');
            return;
        }

        // Tugas mandiri 2: data kosong
        if (!Array.isArray(hasil.data) || hasil.data.length === 0) {
            tampilkanPesan('Belum ada data', 'kosong');
            return;
        }

        tbody.innerHTML = '';
        hasil.data.forEach((mhs) => {
            // Catatan: API mengirim nama prodi pada field "prodi_nama" (hasil JOIN di model)
            tbody.innerHTML +=
                `<tr><td>${esc(mhs.nim)}</td><td>${esc(mhs.nama)}</td><td>${esc(mhs.prodi_nama)}</td></tr>`;
        });
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan('Gagal memuat data. Periksa koneksi atau server.', 'error');
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
