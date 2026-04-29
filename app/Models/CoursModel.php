<?php

namespace App\Models;

use CodeIgniter\Model;

class CoursModel extends Model
{
    protected $table = 'cours';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'code_ue',
        'intitule',
        'credits',
        'semestre',
    ];
}
