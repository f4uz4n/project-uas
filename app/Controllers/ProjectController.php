<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProjectModel;
use App\Libraries\JWTLibrary;

class ProjectController extends ResourceController
{
    protected $modelName = 'App\Models\ProjectModel';
    protected $format = 'json';
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWTLibrary();
    }

    public function index()
    {
        // Bug #18: Shows all projects instead of user's projects only
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $projects = $this->model->where('user_id', $payload->user_id)->findAll();
        return $this->respond($projects);
    }

    public function create()
    {
        $data = $this->request->getPost();

        // Bug #19: No input validation
        $rules = [
            'name'   => 'required|min_length[3]',
            'status' => 'required|in_list[active,completed,on_hold]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Bug #20: Not setting user_id from JWT token
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $data['user_id'] = $payload->user_id;

        $projectId = $this->model->insert($data);

        if ($projectId) {
            return $this->respond([
                'status'  => 'success',
                'message' => 'Project created successfully',
                'id'      => $projectId
            ]);
        }

        return $this->failServerError('Creation failed');
    }

    public function show($id = null)
    {
        // Bug #21: No ownership check
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $project = $this->model->where('id', $id)->where('user_id', $payload->user_id)->first();

        if (!$project) {
            return $this->failNotFound('Project not found');
        }

        return $this->respond($project);
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();

        // Bug #22: No ownership validation
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $project = $this->model->where('id', $id)->where('user_id', $payload->user_id)->first();

        if (!$project) {
            return $this->failNotFound('Project not found');
        }

        if ($this->model->update($id, $data)) {
            return $this->respond([
                'status'  => 'success',
                'message' => 'Project updated successfully'
            ]);
        }

        return $this->failServerError('Update failed');
    }

    public function delete($id = null)
    {
        // Bug #23: No ownership check
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $project = $this->model->where('id', $id)->where('user_id', $payload->user_id)->first();

        if (!$project) {
            return $this->failNotFound('Project not found');
        }

        if ($this->model->delete($id)) {
            return $this->respond([
                'status'  => 'success',
                'message' => 'Project deleted successfully'
            ]);
        }

        return $this->failServerError('Delete failed');
    }
}