<?php
class BukuController {
    private Buku $model;
    private array $required = ['judul', 'penulis', 'tahun_terbit'];

    public function __construct() {
        $this->model = new Buku();
    }

    private function input(): array {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }
        return $data;
    }

    private function validateRequired(array $data): array {
        $errors = [];
        foreach ($this->required as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field][] = "Field $field wajib diisi";
            }
        }
        return $errors;
    }

    private function validateRules(array $data): array {
        $errors = [];
        if (!ctype_digit((string) $data['tahun_terbit'])
            || (int) $data['tahun_terbit'] < 1901
            || (int) $data['tahun_terbit'] > 2155) {
            $errors['tahun_terbit'][] = 'tahun_terbit harus berupa tahun yang valid (1901-2155)';
        }
        return $errors;
    }

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

        $id = $this->model->create($data);
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void {
        if (!$id || !$this->model->find((int) $id)) {
            Response::json(404, false, 'Data tidak ditemukan');
        }

        $data = $this->input();
        $this->validateOrFail($data);

        $this->model->update((int) $id, $data);
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
