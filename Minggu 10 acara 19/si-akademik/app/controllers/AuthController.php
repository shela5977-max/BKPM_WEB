<?php
class AuthController {
    private User $model;

    public function __construct() {
        $this->model = new User();
    }

    // Aksi tunggal: POST /register (aksi login() ditambahkan pada acara 20)
    public function register(): void {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!is_array($data)) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid');
        }

        $errors = [];
        foreach (['username', 'email', 'password'] as $field) {
            if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
                $errors[$field][] = "Field $field wajib diisi";
            }
        }
        if ($errors) {
            Response::json(400, false, 'Username, email, dan password wajib diisi', null, $errors);
        }

        // Kata sandi tidak boleh disimpan sebagai plain text
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            Response::json(422, false, $e->getMessage(), null, ['email' => [$e->getMessage()]]);
        }

        Response::json(201, true, 'Registrasi berhasil', ['id' => $id]);
    }
}
