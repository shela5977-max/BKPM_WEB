<div class="card">
    <div class="card-header bg-primary text-white">
        Tambah Mahasiswa
    </div>

    <div class="card-body">
        <form action="/mahasiswa" method="POST">
            <div class="mb-3">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" class="form-control" id="nim" name="nim" placeholder="Masukkan NIM">
            </div>

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama mahasiswa">
            </div>

            <div class="mb-3">
                <label for="angkatan" class="form-label">Angkatan</label>
                <input type="text" class="form-control" id="angkatan" name="angkatan" placeholder="Contoh: 2024">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/mahasiswa" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
