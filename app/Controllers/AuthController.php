<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Libraries\JWTLibrary;

class AuthController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWTLibrary();
    }

    public function register()
    {
        // $data = $this->request->getPost(); #salah
        // Benar:
        $data = $this->request->getPost();
        if (!isset($data['name'], $data['email'], $data['password'])) {
            return $this->failValidationErrors('Name, email, and password are required');
        }


        // Bug #6: No input validation
        $userModel = new UserModel();

        // Bug #7: Password not hashed
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            // Salah:
            // 'password' => $data['password'],
            // Benar:
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ];

        $userId = $userModel->insert($userData);

        if ($userId) {
            return $this->respond([
                'status' => 'success',
                'message' => 'User registered successfully',
                // Salah:
                // 'data' => $userData,  
                // Benar:
                'data' => [
                    'id' => $userId,
                    'name' => $data['name'],
                    'email' => $data['email']
                ]
            ]);
        }

        return $this->failServerError('Registration failed');
    }

    public function login()
    {
        // Salah:
        // $email = $this->request->getPost('email');

        // Benar:
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        if (!$email || !$password) {
            return $this->failValidationErrors('Email and password are required');
        }

        // Bug #9: No input validation
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Bug #10: Plain text password comparison
        // Salah:
    // if ($user && $user['password'] === $password) {

        // Benar:
        if ($user && password_verify($password, $user['password'])) { {
            $payload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'exp' => time() + 3600
            ];

            $token = $this->jwt->encode($payload);

            return $this->respond([
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ]);
        }

        return $this->failUnauthorized('Invalid credentials');
        }
    }
    public function refresh() 
    {
        // Bug #11: Missing implementation
        // Salah:
        // return $this->respond(['message' => 'Not implemented']);

        // Benar (contoh awal implementasi):
        $authHeader = $this->request->getHeaderLine('Authorization');
        if (!$authHeader) {
            return $this->failUnauthorized('Missing token');
        }
        // lanjutkan dengan parsing dan validasi token...
    }
}
