<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\JWTLibrary;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeader('Authorization');

        if (!$header) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Authorization header missing']);
        }

        // Bug #34: Wrong token format handling
        // Salah:
        $token = str_replace('Bearer ', '', $header);

        // Benar:
        $authHeader = $header->getValue();
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Invalid Authorization header format']);
        }
        $token = $matches[1];

        $jwt = new JWTLibrary();

        try {
            $decoded = $jwt->decode($token);
            // Bug #35: Not setting user data in request
        } catch (Exception $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Invalid token']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Not implemented
        // Salah:
        // (tidak ada baris yang menyimpan user ke request) // Bug #35
        // Benar:
        $request->user = $decoded;
    }
}
