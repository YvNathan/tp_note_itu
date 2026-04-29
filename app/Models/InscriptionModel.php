<?php

namespace App\Models;

use CodeIgniter\Model;

class InscriptionModel extends Model
{
    protected $table = 'inscriptions';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'etudiant_id',
        'parcours_id',
        'niveau',
        'annee_universitaire',
        'matricule',
    ];
}
