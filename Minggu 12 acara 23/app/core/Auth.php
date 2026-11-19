<?php
// Acara 20 - Langkah 1: Class Auth (JWT HS256 native, tanpa Composer)
class Auth {
    const SECRET = 'kunci_rahasia_workshop_2026';

    // Tugas mandiri 2: masa berlaku token diperpendek dari 3600 detik (1 jam) menjadi 300 detik (5 menit)
    const TOKEN_TTL = 300;

    // Base64URL: ganti + dan / menjadi - dan _, lalu buang padding "="
    private static function base64url(string $data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // Kebalikan base64url() agar payload bisa dibaca kembali dengan benar
    private static function base64urlDecode(string $data): string {
        return (string) base64_decode(strtr($data, '-_', '+/'));
    }

    public static function generateToken(array $payload): string {
        $header     = self::base64url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload['exp'] = time() + self::TOKEN_TTL;
        $payloadEnc = self::base64url(json_encode($payload));
        $signature  = self::base64url(hash_hmac('sha256', "$header.$payloadEnc", self::SECRET, true));
        return "$header.$payloadEnc.$signature";
    }

    // Mengembalikan payload (array) jika token sah, atau false jika tidak sah / kedaluwarsa
    public static function validateToken(string $token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        [$header, $payload, $signature] = $parts;

        // 1) Verifikasi tanda tangan (hash_equals tahan serangan timing)
        $valid = self::base64url(hash_hmac('sha256', "$header.$payload", self::SECRET, true));
        if (!hash_equals($valid, $signature)) return false;

        // 2) Baca payload dan cek masa berlaku
        $data = json_decode(self::base64urlDecode($payload), true);
        if (!is_array($data) || !isset($data['exp']) || $data['exp'] < time()) return false;

        return $data;
    }

    // Membaca header Authorization (tidak peka huruf besar/kecil, ada fallback jika getallheaders() tidak tersedia)
    private static function getAuthorizationHeader(): string {
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        foreach ($headers as $name => $value) {
            if (strtolower($name) === 'authorization') return $value;
        }
        return $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';
    }

    // Middleware autentikasi: hentikan request dengan 401 jika token tidak ada / tidak sah
    public static function requireAuth(): array {
        $authHeader = self::getAuthorizationHeader();

        if (!preg_match('/Bearer\s+(\S+)/i', $authHeader, $m)) {
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $payload = self::validateToken($m[1]);
        if (!$payload) {
            Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
        }
        return $payload;
    }
}
