<?php
class BukuController {
    private Buku $model;

    public function __construct() {
        $this->model = new Buku();
    }

    private function json(array $body, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($body);
        exit;
    }

    private function input(): array {
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    private function validate(array $data): ?string {
        foreach (['judul', 'penulis', 'tahun_terbit'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                return 'Field judul, penulis, dan tahun_terbit wajib diisi';
            }
        }
        if (!ctype_digit((string) $data['tahun_terbit'])
            || (int) $data['tahun_terbit'] < 1901
            || (int) $data['tahun_terbit'] > 2155) {
            return 'tahun_terbit harus berupa tahun yang valid (1901-2155)';
        }
        return null;
    }

    public function index(): void {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $data = $this->model->find((int) $id);
            if (!$data) {
                $this->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }
            $this->json(['success' => true, 'data' => $data]);
        }

        $this->json(['success' => true, 'data' => $this->model->getAll()]);
    }

    public function store(): void {
        $data = $this->input();

        if ($error = $this->validate($data)) {
            $this->json(['success' => false, 'message' => $error], 400);
        }

        $id = $this->model->create($data);
        $this->json(['success' => true, 'message' => 'Data berhasil ditambahkan', 'data' => ['id' => $id]], 201);
    }

    public function update($id): void {
        if (!$id || !$this->model->find((int) $id)) {
            $this->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $data = $this->input();

        if ($error = $this->validate($data)) {
            $this->json(['success' => false, 'message' => $error], 400);
        }

        $this->model->update((int) $id, $data);
        $this->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id): void {
        if (!$id || !$this->model->find((int) $id)) {
            $this->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $this->model->delete((int) $id);
        $this->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
