<?php

namespace App\Models;

use CodeIgniter\Model;

class NoteModel extends Model
{
    protected $table = 'notes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'inscription_id',
        'cours_id',
        'note',
    ];

    protected $validationRules = [
        'inscription_id' => 'required|is_natural_no_zero',
        'cours_id'       => 'required|is_natural_no_zero',
        'note'           => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[20]',
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    protected $useTimestamps = false;
    protected $beforeInsert = ['stampCreatedAt'];
    protected $beforeUpdate = ['stampUpdatedAt'];

    protected function stampCreatedAt(array $data): array
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');

        return $data;
    }

    protected function stampUpdatedAt(array $data): array
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');

        return $data;
    }
}
