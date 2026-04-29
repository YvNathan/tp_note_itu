<?php

namespace App\Controllers;

use App\Models\EtudiantModel;
use CodeIgniter\Controller;

class EtudiantController extends Controller
{
    public function index()
    {
        $model = new EtudiantModel();

        $data['etudiants'] = $model->orderBy('nom', 'ASC')
            ->orderBy('prenoms', 'ASC')
            ->findAll();

        return view('template/list', $data);
    }
}
