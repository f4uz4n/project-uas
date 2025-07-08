<?php

namespace App\Libraries;

use Exception;

class JWTLibrary
{
    private $key;

    public function __construct()
    {
        // Bug #36: Hardcoded secret key
        $this->key = getenv('JWT_SECRET') ?: 'fallback-secret';
    }

    public function encode($payload)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signature = hash_hmac('sha256', $base64Header . "." . $base64Payload, $this->key, true);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }

    public function decode($token)
    {
        $parts = explode('.', $token);

        // Bug #37: No proper token validation
        if (count($parts) != 3) {
            throw new Exception('Invalid token format');
        }

        list($base64Header, $base64Payload, $base64Signature) = $parts;

        $header = json_decode(base64_decode(strtr($base64Header, '-_', '+/')), true);
        $payload = json_decode(base64_decode(strtr($base64Payload, '-_', '+/')), true);

        // Bug #38: No signature verification
        $expectedSignature = hash_hmac(
            'sha256',
            $base64Header . '.' . $base64Payload,
            $this->key,
            true
        );
        $expectedBase64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($expectedSignature));

        if (!hash_equals($expectedBase64Signature, $base64Signature)) {
            throw new Exception('Invalid token signature');
        }

        return $payload;
    }
}