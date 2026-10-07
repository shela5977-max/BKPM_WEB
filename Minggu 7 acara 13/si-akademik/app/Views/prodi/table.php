<?php /** @var array $prodi */ ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 mb-0">Data Program Studi</h2>
    <a href="<?= app_url('prodi/create') ?>" class="btn btn-primary">Tambah Prodi</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Program Studi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($prodi): ?>
                <?php foreach ($prodi as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['kode']) ?></td>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td class="text-nowrap">
                            <a href="<?= app_url('prodi/edit') ?>?id=<?= (int) $item['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="<?= app_url('prodi/delete') ?>" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus prodi ini?')">
                                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4" class="text-center">Belum ada data prodi</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>