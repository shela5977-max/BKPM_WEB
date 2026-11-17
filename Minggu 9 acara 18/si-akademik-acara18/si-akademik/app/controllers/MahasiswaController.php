<?php
class MahasiswaController {
    private Mahasiswa $model;
    private array $required = ['nim', 'nama', 'email', 'prodi_id', 'angkatan'];

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    // Membaca body JSON; body yang bukan JSON valid ditolak dengan 400
    private function input(): array {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }
        return $data;
    }

    // Validasi 400: field wajib diisi. Mengembalikan daftar error per field.
    private function validateRequired(array $data): array {
        $errors = [];
        foreach ($this->required as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field][] = "Field $field wajib diisi";
            }
        }
        return $errors;
    }

    // Validasi 422: format/aturan bisnis. Mengembalikan daftar error per field.
    private function validateRules(array $data): array {
        $errors = [];
        if (strlen((string) $data['nim']) !== 9) {
            $errors['nim'][] = 'Format NIM tidak valid, harus 9 digit';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'][] = 'Format email tidak valid';
        }
        if (!ctype_digit((string) $data['prodi_id'])) {
            $errors['prodi_id'][] = 'prodi_id harus berupa angka';
        }
        if (!ctype_digit((string) $data['angkatan'])) {
            $errors['angkatan'][] = 'angkatan harus berupa angka';
        }
        return $errors;
    }

    // Menjalankan kedua tahap validasi; langsung mengirim response jika gagal
    private function validateOrFail(array $data): void {
        if ($errors = $this->validateRequired($data)) {
            Response::json(400, false, 'Data yang dikirim tidak lengkap', null, $errors);
        }
        if ($errors = $this->validateRules($data)) {
            Response::json(422, false, 'Validasi data gagal', null, $errors);
        }
    }

    public function index(): void {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $data = $this->model->find((int) $id);
            if (!$data) Response::json(404, false, 'Data tidak ditemukan');
            Response::json(200, true, 'Data berhasil diambil', $data);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void {
        $data = $this->input();
        $this->validateOrFail($data);

        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            Response::json(422, false, $e->getMessage());
        }

        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void {
        if (!$id || !$this->model->find((int) $id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $data = $this->input();
        $this->validateOrFail($data);

        try {
            $this->model->update((int) $id, $data);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            Response::json(422, false, $e->getMessage());
        }

        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void {
        if (!$id || !$this->model->find((int) $id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $this->model->delete((int) $id);
        Response::json(200, true, 'Data berhasil dihapus');
    }
}
