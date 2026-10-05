<?php /** @var array $prodi */ ?>

<h2 class="mb-3">Tambah Mahasiswa</h2>

<form method="POST" action="<?= app_url('mahasiswa') ?>">

    <div class="mb-3">
        <label>NIM</label>
        <input type="text" name="nim" class="form-control">
    </div>

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <div class="mb-3">
        <label>Program Studi</label>
        <select name="prodi_id" class="form-select" required>
            <?php foreach ($prodi as $item): ?>
                <option value="<?= (int) $item['id'] ?>"><?= htmlspecialchars($item['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Angkatan</label>
        <input type="number" name="angkatan" class="form-control">
    </div>

    <div class="mb-3">
        <label>Status</label>

        <select name="status" class="form-control">
            <option value="aktif">Aktif</option>
            <option value="cuti">Cuti</option>
            <option value="lulus">Lulus</option>
        </select>
    </div>

    <button class="btn btn-primary">
        Simpan
    </button>

</form>