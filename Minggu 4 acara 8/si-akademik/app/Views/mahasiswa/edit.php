<?php /** @var array $mahasiswa */ /** @var array $prodi */ ?>

<h2 class="mb-3">Edit Mahasiswa</h2>

<form method="POST" action="<?= app_url('mahasiswa/update') ?>">

    <input
        type="hidden"
        name="id"
        value="<?= (int) $mahasiswa['id'] ?>">

    <div class="mb-3">
        <label>NIM</label>

        <input
            type="text"
            name="nim"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['nim']) ?>">
    </div>

    <div class="mb-3">
        <label>Nama</label>

        <input
            type="text"
            name="nama"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['nama']) ?>">
    </div>

    <div class="mb-3">
        <label>Email</label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['email']) ?>">
    </div>

    <div class="mb-3">
        <label>Program Studi</label>

        <select name="prodi_id" class="form-select" required>
            <?php foreach ($prodi as $item): ?>
                <option value="<?= (int) $item['id'] ?>" <?= (int) $mahasiswa['prodi_id'] === (int) $item['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($item['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Angkatan</label>

        <input
            type="number"
            name="angkatan"
            class="form-control"
            value="<?= htmlspecialchars($mahasiswa['angkatan']) ?>">
    </div>

    <div class="mb-3">
        <label>Status</label>

        <select name="status" class="form-control">

            <option value="aktif"
                <?= $mahasiswa['status'] == 'aktif' ? 'selected' : '' ?>>
                Aktif
            </option>

            <option value="cuti"
                <?= $mahasiswa['status'] == 'cuti' ? 'selected' : '' ?>>
                Cuti
            </option>

            <option value="lulus"
                <?= $mahasiswa['status'] == 'lulus' ? 'selected' : '' ?>>
                Lulus
            </option>

        </select>
    </div>

    <button class="btn btn-warning">
        Update
    </button>

</form>