<div class="card shadow">

    <div class="card-header bg-primary text-white">
        Dashboard
    </div>

    <div class="card-body">

        <h3>Selamat Datang di Sistem Informasi Akademik</h3>

        <p>
            Halo,
            <strong>
                <?= $_SESSION['user_name'] ?? 'Guest'; ?>
            </strong>
        </p>

        <p>
            Silakan pilih menu yang tersedia.
        </p>

        <a
            href="<?= app_url('mahasiswa') ?>"
            class="btn btn-primary">
            Daftar Mahasiswa
        </a>

    </div>

</div>