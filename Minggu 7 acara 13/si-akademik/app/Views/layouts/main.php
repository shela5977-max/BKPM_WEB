<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistem Informasi Akademik</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <?php include __DIR__ . '/../partials/navbar.php'; ?>

    <main class="container mt-4">

        <?php if (isset($_SESSION['flash'])): ?>

            <div class="alert alert-<?= $_SESSION['flash']['type']; ?> alert-dismissible fade show">
                <?= $_SESSION['flash']['message']; ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>
            </div>

            <?php unset($_SESSION['flash']); ?>

        <?php endif; ?>

        <?php
        if (isset($content)) {
            require $content;
        }
        ?>

    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>