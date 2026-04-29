<?php

namespace App\Models;

use CodeIgniter\Model;

class EtudiantModel extends Model
{
    protected $table = 'etudiants';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom',
        'prenoms',
        'date_naissance',
        'lieu_naissance',
    ];

    protected $validationRules = [
        'nom'            => 'required|max_length[100]',
        'prenoms'        => 'required|max_length[150]',
        'date_naissance' => 'required|valid_date',
        'lieu_naissance' => 'required|max_length[150]',
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}