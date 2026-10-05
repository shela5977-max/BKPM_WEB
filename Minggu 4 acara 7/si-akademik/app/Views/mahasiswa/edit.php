<?php /** @var array $mahasiswa */ ?>

<h2 class="mb-3">Edit Mahasiswa</h2>

<form method="POST" action="/mahasiswa/update">

    <input
        type="hidden"
        name="id"
        value="<?= $mahasiswa['id'] ?>">

    <div class="mb-3">
        <label>NIM</label>

        <input
            type="text"
            name="nim"
            class="form-control"
            value="<?= $mahasiswa['nim'] ?>">
    </div>

    <div class="mb-3">
        <label>Nama</label>

        <input
            type="text"
            name="nama"
            class="form-control"
            value="<?= $mahasiswa['nama'] ?>">
    </div>

    <div class="mb-3">
        <label>Email</label>

        <input
            type="email"
            name="email"
            class="form-control"
            value="<?= $mahasiswa['email'] ?>">
    </div>

    <div class="mb-3">
        <label>Prodi ID</label>

        <input
            type="number"
            name="prodi_id"
            class="form-control"
            value="<?= $mahasiswa['prodi_id'] ?>">
    </div>

    <div class="mb-3">
        <label>Angkatan</label>

        <input
            type="number"
            name="angkatan"
            class="form-control"
            value="<?= $mahasiswa['angkatan'] ?>">
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