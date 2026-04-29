<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
    ];

    protected $validationRules = [
        'nom'          => 'required|max_length[100]',
        'email'        => 'required|valid_email|max_length[150]|is_unique[users.email,id,{id}]',
        'mot_de_passe' => 'required|min_length[6]|max_length[255]',
        'role'         => 'required|in_list[admin,enseignant]',
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
}