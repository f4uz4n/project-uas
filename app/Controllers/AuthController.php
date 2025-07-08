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
        $data = $this->request->getJSON(true);

        // Bug #6: No input validation
        if (!isset($data['name'], $data['email'], $data['password'])) {
            return $this->failValidationErrors('Name, email, and password are required.');
        }

        $userModel = new UserModel();

        // Bug #7: Password not hashed
        $userData = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ];

        $userId = $userModel->insert($userData);

        if ($userId) {
            // Bug #8: Returning password in response
            return $this->respond([
                'status'  => 'success',
                'message' => 'User registered successfully',
                'data'    => ['id' => $userId, 'name' => $userData['name'], 'email' => $userData['email']]
            ]);
        }

        return $this->failServerError('Registration failed');
    }

    public function login()
    {
        $data = $this->request->getJSON(true);

        // Bug #9: No input validation
        if (!isset($data['email'], $data['password'])) {
            return $this->failValidationErrors('Email and password are required.');
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $data['email'])->first();

        // Bug #10: Plain text password comparison
        if ($user && password_verify($data['password'], $user['password'])) {
            $payload = [
                'user_id' => $user['id'],
                'email'   => $user['email'],
                'exp'     => time() + 3600
            ];

            $token = $this->jwt->encode($payload);

            return $this->respond([
                'status' => 'success',
                'token'  => $token,
                'user'   => $user
            ]);
        }

        return $this->failUnauthorized('Invalid credentials');
    }

    public function refresh()
    {
        // Bug #11: Missing implementation
        return $this->respond(['message' => 'Not implemented']);
    }
}
