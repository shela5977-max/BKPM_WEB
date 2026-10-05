<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

          <a class="navbar-brand"
              href="<?= app_url() ?>">
            SI Akademik
        </a>

        <div class="navbar-nav">

            <a class="nav-link"
               href="<?= app_url() ?>">
                Beranda
            </a>

            <a class="nav-link"
                    href="<?= app_url('mahasiswa') ?>">
                Mahasiswa
            </a>

                <a class="nav-link" href="<?= app_url('prodi') ?>">Prodi</a>

                <a class="nav-link" href="<?= app_url('matakuliah') ?>">Mata Kuliah</a>

            <a class="nav-link"
               href="<?= app_url('logout') ?>">
                Logout
            </a>

        </div>

    </div>
</nav>