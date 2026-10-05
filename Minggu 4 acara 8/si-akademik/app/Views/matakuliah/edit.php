<?php /** @var array $matakuliah */ /** @var array $prodi */ ?>

<h2 class="h4 mb-3">Edit Mata Kuliah</h2>

<form method="POST" action="<?= app_url('matakuliah/update') ?>">
    <input type="hidden" name="id" value="<?= (int) $matakuliah['id'] ?>">
    <div class="mb-3">
        <label for="kode" class="form-label">Kode</label>
        <input id="kode" type="text" name="kode" class="form-control" maxlength="10" value="<?= htmlspecialchars($matakuliah['kode']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="nama" class="form-label">Nama Mata Kuliah</label>
        <input id="nama" type="text" name="nama" class="form-control" maxlength="150" value="<?= htmlspecialchars($matakuliah['nama']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="sks" class="form-label">SKS</label>
        <input id="sks" type="number" name="sks" class="form-control" min="1" max="255" value="<?= (int) $matakuliah['sks'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="prodi_id" class="form-label">Program Studi</label>
        <select id="prodi_id" name="prodi_id" class="form-select" required>
            <?php foreach ($prodi as $item): ?>
                <option value="<?= (int) $item['id'] ?>" <?= (int) $matakuliah['prodi_id'] === (int) $item['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($item['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button class="btn btn-primary">Simpan Perubahan</button>
    <a href="<?= app_url('matakuliah') ?>" class="btn btn-outline-secondary">Batal</a>
</form>