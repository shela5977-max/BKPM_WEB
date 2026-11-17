<?php
class MahasiswaController {
    private Mahasiswa $model;
    private array $required = ['nim', 'nama', 'email', 'prodi_id', 'angkatan'];

    public function __construct() {
        $this->model = new Mahasiswa();
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
        foreach ($this->required as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                return 'Field nim, nama, email, prodi_id, dan angkatan wajib diisi';
            }
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'Format email tidak valid';
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

        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 409);
        } catch (InvalidArgumentException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

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

        try {
            $this->model->update((int) $id, $data);
        } catch (DomainException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 409);
        } catch (InvalidArgumentException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

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
