<h2 class="h4 mb-3">Tambah Program Studi</h2>

<form method="POST" action="<?= app_url('prodi') ?>">
    <div class="mb-3">
        <label for="kode" class="form-label">Kode</label>
        <input id="kode" type="text" name="kode" class="form-control" maxlength="10" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Program Studi</label>
        <input id="nama" type="text" name="nama" class="form-control" maxlength="100" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="<?= app_url('prodi') ?>" class="btn btn-outline-secondary">Batal</a>
</form>