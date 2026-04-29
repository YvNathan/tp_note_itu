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
}
