<?php /** @var array $matakuliah */ ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 mb-0">Data Mata Kuliah</h2>
    <a href="<?= app_url('matakuliah/create') ?>" class="btn btn-primary">Tambah Mata Kuliah</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Program Studi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($matakuliah): ?>
                <?php foreach ($matakuliah as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($item['kode']) ?></td>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= (int) $item['sks'] ?></td>
                        <td><?= htmlspecialchars($item['prodi_nama']) ?></td>
                        <td class="text-nowrap">
                            <a href="<?= app_url('matakuliah/edit') ?>?id=<?= (int) $item['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form method="POST" action="<?= app_url('matakuliah/delete') ?>" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Belum ada data mata kuliah</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>