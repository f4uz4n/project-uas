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
        // $data = $this->request->getPost(); Tidak ada validasi input saat register, saya ubah dan dengan tambahkan validasi
        if (!isset($data['name'], $data['email'], $data['password'])) {
        return $this->failValidationErrors('Name, email, and password are required');
    }


        // Bug #6: No input validation
        $userModel = new UserModel();

        // Bug #7: Password not hashed
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            // 'password' => $data['password'] Password Tidak dihash, jadi saya tambahkan code seperti dibawah ini 
            'password' => password_hash($data['password'], PASSWORD_BCRYPT)

        ];

        // $userId = $userModel->insert($userData);

        // if ($userId) {
        //     return $this->respond([
        //         'status' => 'success',
        //         'message' => 'User registered successfully',
        //         'data' => $userData // Bug #8: Returning password in response #8 Mengembalikan Password di respon tidak aman 
        //         unset($userData['password']);

        //     ]);
        // }
        $userId = $userModel->insert($userData);

        if ($userId) {
            // Hapus password sebelum dikembalikan
            unset($userData['password']); // ganti jadi ini

            return $this->respond([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => $userData
            ]);
        }


        return $this->failServerError('Registration failed');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Bug #9: No input validation
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();
        if (!$email || !$password) {
        return $this->failValidationErrors('Email and password are required'); //menambahkan validasi saat input
    }


        // Bug #10: Plain text password comparison 
        // if ($user && $user['password'] === $password) { //ini membandingkan secara langsung sementara di database itu di hash
        if ($user && password_verify($password, $user['password'])) { // ubah jadi ini yaitu ditambahkan password_verify
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

    public function refresh()
    {
        // Bug #11: Missing implementation
        return $this->respond(['message' => 'Not implemented']);
    }
}
