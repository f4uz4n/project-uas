<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Libraries\JWTLibrary;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWTLibrary();
    }

    public function index()
    {
        // Bug #12: No pagination
        $limit = $this->request->getGet('limit') ?? 10;
        $offset = $this->request->getGet('offset') ?? 0;

        $users = $this->model->findAll($limit, $offset);
        return $this->respond($users);
    }

    public function show($id = null)
    {
        // Bug #13: No input validation for ID
        if (!is_numeric($id)) {
            return $this->failValidationErrors('Invalid ID format');
        }

        $user = $this->model->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        // Bug #14: Returning sensitive data
        unset($user['password']);
        return $this->respond($user);
    }

    public function update($id = null)
    {
        // Bug #15: No authorization check (user can update other users)
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        if ($payload->user_id != $id) {
            return $this->failForbidden('You are not allowed to update this user');
        }

        $data = $this->request->getRawInput();

        if (!$this->model->find($id)) {
            return $this->failNotFound('User not found');
        }

        // Bug #16: No input validation
        $rules = [
            'name'  => 'permit_empty|min_length[3]',
            'email' => 'permit_empty|valid_email'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        if ($this->model->update($id, $data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'User updated successfully'
            ]);
        }

        return $this->failServerError('Update failed');
    }

    public function delete($id = null)
    {
        // Bug #17: No authorization check
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        if ($payload->user_id != $id) {
            return $this->failForbidden('You are not allowed to delete this user');
        }

        if (!$this->model->find($id)) {
            return $this->failNotFound('User not found');
        }

        if ($this->model->delete($id)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        }

        return $this->failServerError('Delete failed');
    }
}