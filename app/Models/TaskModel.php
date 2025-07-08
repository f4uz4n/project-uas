<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table = 'tasks';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'project_id', 'status', 'priority'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Bug #33: No validation rules
    protected $validationRules = [
        'title'       => 'required|min_length[3]',
        'description' => 'permit_empty',
        'project_id'  => 'required|is_natural_no_zero',
        'status'      => 'required|in_list[open,in_progress,completed]',
        'priority'    => 'required|in_list[low,medium,high]',
    ];
}