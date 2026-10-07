<div class="card shadow">

    <div class="card-header bg-primary text-white">
        Dashboard
    </div>

    <div class="card-body">

       <h3>Dashboard Utama Sistem Akademik</h3>

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

        <div class="alert alert-success mt-4">
            <h5>BKPM Acara 11 - Git dan GitHub</h5>
            <p class="mb-0">
                Project Sistem Informasi Akademik telah berhasil menggunakan
                Git dan GitHub untuk version control serta pengelolaan source code.
            </p>
        </div>

    </div>

</div>