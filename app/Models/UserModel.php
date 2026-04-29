<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'username',
        'password',
    ];

    protected $validationRules = [
        'username' => 'required|max_length[50]|is_unique[users.username,id,{id}]',
        'password' => 'required|min_length[6]|max_length[255]',
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}