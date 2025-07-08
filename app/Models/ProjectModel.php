<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'user_id', 'status'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Bug #32: No validation rules
    protected $validationRules = [
        'name'        => 'required|min_length[3]',
        'description' => 'permit_empty',
        'user_id'     => 'required|is_natural_no_zero',
        'status'      => 'required|in_list[active,completed,on_hold]',
    ];
}