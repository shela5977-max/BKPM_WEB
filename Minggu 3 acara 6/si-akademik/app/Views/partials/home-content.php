<div class="card shadow">

    <div class="card-header bg-primary text-white">
        Dashboard
    </div>

    <div class="card-body">

        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_SESSION['flash_message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

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
            href="/Minggu%203%20acara%206/si-akademik/public/mahasiswa"
            class="btn btn-primary">
            Daftar Mahasiswa
        </a>

    </div>

</div>