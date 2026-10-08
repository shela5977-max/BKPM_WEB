<?php /** @var array $mahasiswa */ /** @var string $search */ ?>

<div class="d-flex justify-content-between mb-3">

    <h3>Data Mahasiswa</h3>

    <a href="<?= app_url('mahasiswa/create') ?>"
       class="btn btn-primary">
        + Tambah Mahasiswa
    </a>

</div>

<form method="GET" action="<?= app_url('mahasiswa') ?>" class="row g-2 mb-3">
    <div class="col-sm-8 col-md-5">
        <input type="search" name="search" class="form-control" placeholder="Cari nama atau NIM" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary">Cari</button>
        <?php if ($search !== ''): ?>
            <a href="<?= app_url('mahasiswa') ?>" class="btn btn-outline-secondary">Reset</a>
        <?php endif; ?>
    </div>
</form>

<table class="table table-bordered table-striped">

    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Prodi</th>
            <th>Angkatan</th>
            <th>Status</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>

    <tbody>

        <?php if (!empty($mahasiswa)): ?>

            <?php foreach ($mahasiswa as $index => $mhs): ?>

                <tr>

                    <td><?= $index + 1 ?></td>

                    <td><?= htmlspecialchars($mhs['nim']) ?></td>

                    <td><?= htmlspecialchars($mhs['nama']) ?></td>

                    <td><?= htmlspecialchars($mhs['email']) ?></td>

                    <td><?= htmlspecialchars($mhs['prodi_nama']) ?></td>

                    <td><?= htmlspecialchars($mhs['angkatan']) ?></td>

                    <td><?= htmlspecialchars($mhs['status']) ?></td>

                    <td>

                        <a href="<?= app_url('mahasiswa/edit') ?>?id=<?= (int) $mhs['id'] ?>"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form method="POST"
                              action="<?= app_url('mahasiswa/delete') ?>"
                              style="display:inline;">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $mhs['id'] ?>">

                            <button
                                type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">

                                Hapus

                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="8" class="text-center">
                    Belum ada data mahasiswa
                </td>
            </tr>

        <?php endif; ?>

    </tbody>

</table>