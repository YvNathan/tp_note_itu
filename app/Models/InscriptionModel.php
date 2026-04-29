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

    protected $validationRules = [
        'etudiant_id'         => 'required|is_natural_no_zero',
        'parcours_id'         => 'required|is_natural_no_zero',
        'niveau'              => 'required|in_list[L1,L2,L3,M1,M2]',
        'annee_universitaire' => 'required|max_length[10]',
        'matricule'           => 'required|max_length[30]|is_unique[inscriptions.matricule,id,{id}]',
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;
}
