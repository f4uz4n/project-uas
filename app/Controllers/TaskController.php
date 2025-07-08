<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;
use App\Models\ProjectModel;
use App\Libraries\JWTLibrary;

class TaskController extends ResourceController
{
    protected $modelName = 'App\Models\TaskModel';
    protected $format = 'json';
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWTLibrary();
    }

    public function index()
    {
        // Bug #24: No filtering by project or user
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $tasks = $this->model->select('tasks.*')
            ->join('projects', 'projects.id = tasks.project_id')
            ->where('projects.user_id', $payload->user_id)
            ->findAll();

        return $this->respond($tasks);
    }

    public function create()
    {
        $data = $this->request->getPost();

        // Bug #25: No validation for required fields
        $rules = [
            'title'       => 'required|min_length[3]',
            'project_id'  => 'required|is_natural_no_zero',
            'status'      => 'required|in_list[open,in_progress,completed]',
            'priority'    => 'required|in_list[low,medium,high]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Bug #26: Not validating project ownership
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $projectModel = new ProjectModel();
        $project = $projectModel
            ->where('id', $data['project_id'])
            ->where('user_id', $payload->user_id)
            ->first();

        if (!$project) {
            return $this->failForbidden('You do not have access to this project.');
        }

        $taskId = $this->model->insert($data);

        if ($taskId) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Task created successfully',
                'id' => $taskId
            ]);
        }

        return $this->failServerError('Creation failed');
    }

    public function show($id = null)
    {
        // Bug #27: No access control
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $task = $this->model->select('tasks.*')
            ->join('projects', 'projects.id = tasks.project_id')
            ->where('tasks.id', $id)
            ->where('projects.user_id', $payload->user_id)
            ->first();

        if (!$task) {
            return $this->failNotFound('Task not found');
        }

        return $this->respond($task);
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();

        if (!$this->model->find($id)) {
            return $this->failNotFound('Task not found');
        }

        // Bug #28: No validation for status updates
        $rules = [
            'status'   => 'in_list[open,in_progress,completed]',
            'priority' => 'in_list[low,medium,high]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $task = $this->model->select('tasks.*')
            ->join('projects', 'projects.id = tasks.project_id')
            ->where('tasks.id', $id)
            ->where('projects.user_id', $payload->user_id)
            ->first();

        if (!$task) {
            return $this->failForbidden('You do not have access to this task.');
        }

        if ($this->model->update($id, $data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Task updated successfully'
            ]);
        }

        return $this->failServerError('Update failed');
    }

    public function delete($id = null)
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $authHeader);
        $payload = $this->jwt->decode($token);

        $task = $this->model->select('tasks.*')
            ->join('projects', 'projects.id = tasks.project_id')
            ->where('tasks.id', $id)
            ->where('projects.user_id', $payload->user_id)
            ->first();

        if (!$task) {
            return $this->failNotFound('Task not found');
        }

        if ($this->model->delete($id)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Task deleted successfully'
            ]);
        }

        return $this->failServerError('Delete failed');
    }
}
