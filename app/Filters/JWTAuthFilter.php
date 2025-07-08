<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Libraries\JWTLibrary;
use Exception;

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
        $token = $header->getValue();
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7); // hapus prefix "Bearer "
        }

        $jwt = new JWTLibrary();

        try {
            $decoded = $jwt->decode($token);
            // Bug #35: Not setting user data in request
            $request->user = $decoded;
        } catch (Exception $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Invalid token']);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Not implemented
    }
}