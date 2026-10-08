<?php /** @var array $prodi */ ?>

<h2 class="h4 mb-3">Tambah Mata Kuliah</h2>

<form method="POST" action="<?= app_url('matakuliah') ?>">
    <div class="mb-3">
        <label for="kode" class="form-label">Kode</label>
        <input id="kode" type="text" name="kode" class="form-control" maxlength="10" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Mata Kuliah</label>
        <input id="nama" type="text" name="nama" class="form-control" maxlength="150" required>
    </div>
    <div class="mb-3">
        <label for="sks" class="form-label">SKS</label>
        <input id="sks" type="number" name="sks" class="form-control" min="1" max="255" required>
    </div>
    <div class="mb-3">
        <label for="prodi_id" class="form-label">Program Studi</label>
        <select id="prodi_id" name="prodi_id" class="form-select" required>
            <?php foreach ($prodi as $item): ?>
                <option value="<?= (int) $item['id'] ?>"><?= htmlspecialchars($item['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="<?= app_url('matakuliah') ?>" class="btn btn-outline-secondary">Batal</a>
</form>