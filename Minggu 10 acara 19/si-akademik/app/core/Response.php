<?php
class Response {
    /**
     * Mengirim response JSON dengan format (envelope) yang seragam.
     *
     * @param int    $httpCode kode status HTTP (200, 201, 400, 404, 422, ...)
     * @param bool   $success  true jika request berhasil
     * @param string $message  pesan singkat untuk client
     * @param mixed  $data     data hasil (null jika tidak ada)
     * @param array  $errors   daftar kesalahan validasi per field, contoh:
     *                         ['nim' => ['NIM harus 9 karakter'], 'email' => ['Format email tidak valid']]
     */
    public static function json(
        int $httpCode,
        bool $success,
        string $message,
        $data = null,
        array $errors = []
    ): void {
        http_response_code($httpCode);
        header('Content-Type: application/json; charset=utf-8');

        // Tugas mandiri 2: pretty print hanya pada mode pengembangan
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode([
            'success'   => $success,
            'message'   => $message,
            'data'      => $data,
            'errors'    => $errors, // Tugas mandiri 1
            'timestamp' => date('c'),
        ], $flags);
        exit;
    }
}
