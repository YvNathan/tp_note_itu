<?php

namespace App\Models;

use CodeIgniter\Model;

class ParcoursCoursModel extends Model
{
    protected $table = 'parcours_cours';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'parcours_id',
        'cours_id',
        'est_optionnel',
        'groupe_option',
    ];

    protected $validationRules = [
        'parcours_id'   => 'required|is_natural_no_zero',
        'cours_id'      => 'required|is_natural_no_zero',
        'est_optionnel' => 'permit_empty|in_list[0,1]',
        'groupe_option' => 'permit_empty|max_length[50]',
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
