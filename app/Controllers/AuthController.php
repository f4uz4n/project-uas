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
        $data = $this->request->getPost();

        // Bug #6: No input validation
        $rules = [
            'name'     => 'required',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userModel = new UserModel();

        // Bug #7: Password not hashed
        $userData = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ];

        $userId = $userModel->insert($userData);

        if ($userId) {
            // Bug #8: Returning password in response
            unset($userData['password']); // password tidak dikembalikan ke client

            return $this->respond([
                'status'  => 'success',
                'message' => 'User registered successfully',
                'data'    => $userData
            ]);
        }

        return $this->failServerError('Registration failed');
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Bug #9: No input validation
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Bug #10: Plain text password comparison
        if ($user && password_verify($password, $user['password'])) {
            $payload = [
                'user_id' => $user['id'],
                'email'   => $user['email'],
                'exp'     => time() + 3600
            ];

            $token = $this->jwt->encode($payload);

            unset($user['password']); // password tidak dikirim balik

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
        return $this->respond(['message' => 'Not implemented yet']);
    }
}