const token = localStorage.getItem('token');
const role  = localStorage.getItem('role');
const isAdmin = role === 'admin';

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const formMhs = document.querySelector('#form-mahasiswa');
const pesanForm = document.querySelector('#pesan-form');
let dataMahasiswa = [];
let editId = null; // null = mode tambah, angka = mode ubah

// ---------- Tugas mandiri 2: sembunyikan elemen admin jika role = user ----------
document.querySelector('#role-aktif').textContent = role || '-';
document.querySelectorAll('.admin-only').forEach((el) => {
    if (isAdmin && el.id !== 'kartu-form') el.classList.remove('hidden'); // form baru tampil saat Tambah/Ubah
});

// ---------- Tugas mandiri 1: Logout ----------
function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    window.location.href = 'login.html';
}
document.querySelector('#btn-logout').addEventListener('click', logout);

// ---------- Utilitas ----------
function esc(teks) {
    const el = document.createElement('div');
    el.textContent = teks ?? '';
    return el.innerHTML;
}

function tampilkanPesan(teks, kelas = '') {
    tbody.innerHTML = '';
    const tr = document.createElement('tr');
    const td = document.createElement('td');
    td.colSpan = isAdmin ? 4 : 3;
    td.className = 'pesan ' + kelas;
    td.textContent = teks;
    tr.appendChild(td);
    tbody.appendChild(tr);
}

function tampilkanPesanForm(teks) {
    pesanForm.textContent = teks;
    pesanForm.classList.toggle('hidden', !teks);
}

// Request ber-token. 401 (token habis/tidak sah) -> logout otomatis.
async function apiFetch(path, options = {}) {
    const res = await fetch(`${BASE_URL}${path}`, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            ...(options.headers || {}),
        },
    });
    const hasil = await res.json();
    if (res.status === 401) {
        alert(hasil.message + '. Silakan login kembali.');
        logout();
    }
    return { status: res.status, hasil };
}

// ---------- Acara 25 - Langkah 5: muat data + ringkasan ----------
async function muatData() {
    tampilkanPesan('Memuat data...', 'memuat');
    try {
        const res = await fetch(`${BASE_URL}/mahasiswa`); // GET bersifat publik
        const hasil = await res.json();

        if (!res.ok || !hasil.success) {
            tampilkanPesan(hasil.message || 'Gagal memuat data.', 'error');
            return;
        }

        dataMahasiswa = Array.isArray(hasil.data) ? hasil.data : [];
        document.querySelector('#total-mahasiswa').textContent = dataMahasiswa.length;

        if (dataMahasiswa.length === 0) {
            tampilkanPesan('Belum ada data', 'kosong');
            return;
        }

        tbody.innerHTML = '';
        dataMahasiswa.forEach((m) => {
            // Tombol Ubah/Hapus hanya dibuat untuk admin
            const aksi = isAdmin
                ? `<td class="aksi">
                       <button type="button" class="ubah" data-aksi="ubah" data-id="${esc(m.id)}">Ubah</button>
                       <button type="button" class="bahaya" data-aksi="hapus" data-id="${esc(m.id)}">Hapus</button>
                   </td>`
                : '';
            tbody.insertAdjacentHTML('beforeend',
                `<tr><td>${esc(m.nim)}</td><td>${esc(m.nama)}</td><td>${esc(m.prodi_nama)}</td>${aksi}</tr>`);
        });
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanPesan('Gagal memuat data. Periksa koneksi atau server.', 'error');
    }
}

// ---------- Form Tambah / Ubah (admin) ----------
function bukaForm(mhs = null) {
    editId = mhs ? Number(mhs.id) : null;
    document.querySelector('#judul-form').textContent = mhs ? 'Ubah Mahasiswa' : 'Tambah Mahasiswa';
    ['nim', 'nama', 'email', 'prodi_id', 'angkatan'].forEach((f) => {
        document.querySelector('#' + f).value = mhs ? mhs[f] : '';
    });
    tampilkanPesanForm('');
    document.querySelector('#kartu-form').classList.remove('hidden');
    document.querySelector('#nim').focus();
}

function tutupForm() {
    editId = null;
    formMhs.reset();
    tampilkanPesanForm('');
    document.querySelector('#kartu-form').classList.add('hidden');
}

if (isAdmin) {
    document.querySelector('#btn-tambah').addEventListener('click', () => bukaForm());
    document.querySelector('#btn-batal').addEventListener('click', tutupForm);

    formMhs.addEventListener('submit', async (e) => {
        e.preventDefault();
        const body = {
            nim: nim.value.trim(), nama: nama.value.trim(), email: email.value.trim(),
            prodi_id: prodi_id.value, angkatan: angkatan.value,
        };
        const path   = editId ? `/mahasiswa?id=${editId}` : '/mahasiswa';
        const method = editId ? 'PUT' : 'POST';

        try {
            const { status, hasil } = await apiFetch(path, { method, body: JSON.stringify(body) });
            if (hasil.success) {
                tutupForm();
                muatData();
            } else {
                // 400/422 berisi errors per field, 403 berisi pesan akses ditolak
                const detail = Object.values(hasil.errors || {}).flat().join(' ');
                tampilkanPesanForm(`(${status}) ${hasil.message}${detail ? ' - ' + detail : ''}`);
            }
        } catch (error) {
            console.error(error);
            tampilkanPesanForm('Gagal menyimpan data. Periksa koneksi atau server.');
        }
    });

    // Delegasi event untuk tombol Ubah / Hapus pada tabel
    tbody.addEventListener('click', async (e) => {
        const btn = e.target.closest('button[data-aksi]');
        if (!btn) return;
        const id = Number(btn.dataset.id);
        const mhs = dataMahasiswa.find((m) => Number(m.id) === id);
        if (!mhs) return;

        if (btn.dataset.aksi === 'ubah') {
            bukaForm(mhs);
        } else if (confirm(`Hapus data ${mhs.nama} (${mhs.nim})?`)) {
            try {
                const { status, hasil } = await apiFetch(`/mahasiswa?id=${id}`, { method: 'DELETE' });
                if (hasil.success) muatData();
                else alert(`(${status}) ${hasil.message}`);
            } catch (error) {
                console.error(error);
                alert('Gagal menghapus data. Periksa koneksi atau server.');
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', muatData);
