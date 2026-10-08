<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../Core/Database.php';

$respond = static function (array $body, int $status = 200): void {
    http_response_code($status);
    echo json_encode($body, JSON_THROW_ON_ERROR);
    exit;
};

$readInput = static function () use ($respond): array {
    try {
        $input = json_decode(
            file_get_contents('php://input'),
            false,
            512,
            JSON_THROW_ON_ERROR
        );
    } catch (JsonException) {
        $respond([
            'success' => false,
            'message' => 'Format JSON tidak valid'
        ], 400);
    }

    if (!$input instanceof stdClass) {
        $respond([
            'success' => false,
            'message' => 'Body request harus berupa object JSON'
        ], 400);
    }

    return get_object_vars($input);
};

$prepareStudentData = static function (array $input, PDO $pdo) use ($respond): array {
    foreach (['nim', 'nama', 'email'] as $field) {
        if (!isset($input[$field]) || !is_string($input[$field])) {
            $respond([
                'success' => false,
                'message' => 'nim, nama, dan email harus berupa teks'
            ], 422);
        }
    }

    $nim = trim($input['nim']);
    $nama = trim($input['nama']);
    $email = trim($input['email']);

    if ($nim === '' || $nama === '' || $email === '') {
        $respond([
            'success' => false,
            'message' => 'nim, nama, dan email wajib diisi'
        ], 422);
    }

    if (
        strlen($nim) > 20
        || strlen($nama) > 100
        || strlen($email) > 100
        || filter_var($email, FILTER_VALIDATE_EMAIL) === false
    ) {
        $respond([
            'success' => false,
            'message' => 'Data mahasiswa tidak valid'
        ], 422);
    }

    if (isset($input['prodi_id'])) {
        $prodiId = filter_var($input['prodi_id'], FILTER_VALIDATE_INT);

        if ($prodiId === false || $prodiId < 1) {
            $respond([
                'success' => false,
                'message' => 'prodi_id harus berupa ID program studi yang valid'
            ], 422);
        }

        $statement = $pdo->prepare('SELECT id FROM prodi WHERE id = :id');
        $statement->execute(['id' => $prodiId]);

        if (!$statement->fetchColumn()) {
            $respond([
                'success' => false,
                'message' => 'Program studi tidak ditemukan'
            ], 422);
        }
    } else {
        $prodiId = $pdo->query('SELECT id FROM prodi ORDER BY id LIMIT 1')->fetchColumn();

        if ($prodiId === false) {
            $respond([
                'success' => false,
                'message' => 'Tambahkan program studi terlebih dahulu sebelum membuat data mahasiswa'
            ], 409);
        }
    }

    $angkatan = $input['angkatan'] ?? date('Y');
    $angkatan = filter_var($angkatan, FILTER_VALIDATE_INT);

    if ($angkatan === false || $angkatan < 1901 || $angkatan > 2155) {
        $respond([
            'success' => false,
            'message' => 'angkatan harus berupa tahun yang valid'
        ], 422);
    }

    return [
        'nim' => $nim,
        'nama' => $nama,
        'email' => $email,
        'prodi_id' => (int) $prodiId,
        'angkatan' => $angkatan
    ];
};

try {
    $pdo = Database::getInstance();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'GET') {
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

            if ($id === false || $id < 1) {
                $respond([
                    'success' => false,
                    'message' => 'id harus berupa bilangan bulat positif'
                ], 400);
            }

            $statement = $pdo->prepare(
                'SELECT id, nim, nama, email FROM mahasiswa WHERE id = :id'
            );
            $statement->execute(['id' => $id]);
            $data = $statement->fetch();

            if (!$data) {
                $respond([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 404);
            }
        } else {
            $statement = $pdo->query(
                'SELECT id, nim, nama, email FROM mahasiswa ORDER BY nim'
            );
            $data = $statement->fetchAll();
        }

        $respond([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $data
        ]);
    }

    if ($method === 'POST') {
        $data = $prepareStudentData($readInput(), $pdo);
        $statement = $pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan)'
        );
        $statement->execute($data);

        $data['id'] = (int) $pdo->lastInsertId();
        unset($data['prodi_id'], $data['angkatan']);

        $respond([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    if ($method === 'PUT') {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if ($id === false || $id === null || $id < 1) {
            $respond([
                'success' => false,
                'message' => 'id harus berupa bilangan bulat positif'
            ], 400);
        }

        $data = $prepareStudentData($readInput(), $pdo);
        $data['id'] = $id;
        $statement = $pdo->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan
             WHERE id = :id'
        );
        $statement->execute($data);

        if ($statement->rowCount() === 0) {
            $exists = $pdo->prepare('SELECT 1 FROM mahasiswa WHERE id = :id');
            $exists->execute(['id' => $id]);

            if (!$exists->fetchColumn()) {
                $respond([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan'
                ], 404);
            }
        }

        $respond([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diubah'
        ]);
    }

    if ($method === 'DELETE') {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        if ($id === false || $id === null || $id < 1) {
            $respond([
                'success' => false,
                'message' => 'id harus berupa bilangan bulat positif'
            ], 400);
        }

        $statement = $pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $statement->execute(['id' => $id]);

        if ($statement->rowCount() === 0) {
            $respond([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ], 404);
        }

        $respond([
            'success' => true,
            'message' => 'Data mahasiswa berhasil dihapus'
        ]);
    }

    header('Allow: GET, POST, PUT, DELETE');
    $respond([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ], 405);
} catch (PDOException $exception) {
    error_log($exception->getMessage());

    if ($exception->getCode() === '23000') {
        $respond([
            'success' => false,
            'message' => 'NIM sudah terdaftar atau data tidak memenuhi relasi database'
        ], 409);
    }

    $respond([
        'success' => false,
        'message' => 'Terjadi kesalahan saat mengakses database'
    ], 500);
}
