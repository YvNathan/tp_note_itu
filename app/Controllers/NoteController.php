<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\NoteModel;
use App\Models\InscriptionModel;
use App\Models\EtudiantModel;
use App\Models\CoursModel;

class NoteController extends Controller
{
    protected $noteModel;
    protected $inscriptionModel;
    protected $etudiantModel;
    protected $coursModel;

    public function __construct()
    {
        $this->noteModel = new NoteModel();
        $this->inscriptionModel = new InscriptionModel();
        $this->etudiantModel = new EtudiantModel();
        $this->coursModel = new CoursModel();
    }

    public function form()
    {
        $inscriptions = $this->inscriptionModel
            ->select('inscriptions.id, inscriptions.matricule, etudiants.nom, etudiants.prenoms')
            ->join('etudiants', 'etudiants.id = inscriptions.etudiant_id', 'left')
            ->findAll();

        $courses = $this->coursModel
            ->select('id, code_ue, intitule, semestre')
            ->orderBy('semestre', 'ASC')
            ->orderBy('intitule', 'ASC')
            ->findAll();

        $semesters = $this->coursModel
            ->distinct()
            ->select('semestre')
            ->orderBy('semestre', 'ASC')
            ->findAll();

        return view('template/note_form', [
            'inscriptions' => $inscriptions,
            'courses'      => $courses,
            'semesters'    => $semesters,
        ]);
    }

    public function getStudent($inscriptionId)
    {
        $inscription = $this->inscriptionModel
            ->select('inscriptions.id, inscriptions.matricule, etudiants.nom, etudiants.prenoms')
            ->join('etudiants', 'etudiants.id = inscriptions.etudiant_id', 'left')
            ->find($inscriptionId);

        if (!$inscription) {
            return $this->response->setJSON(['error' => 'Inscription not found'])->setStatusCode(404);
        }

        return $this->response->setJSON($inscription);
    }

    public function getCoursesBySemester($semester)
    {
        $courses = $this->coursModel
            ->where('semestre', $semester)
            ->orderBy('intitule', 'ASC')
            ->findAll();

        return $this->response->setJSON($courses);
    }

    public function save()
    {
        try {
            $contentType = strtolower($this->request->getHeaderLine('Content-Type'));
            if (str_contains($contentType, 'application/json')) {
                $payload = $this->request->getJSON(true);
                $data = [
                    'inscription_id' => $payload['inscription_id'] ?? null,
                    'cours_id'       => $payload['cours_id'] ?? null,
                    'note'           => $payload['note'] ?? null,
                ];
            } else {
                $data = [
                    'inscription_id' => $this->request->getPost('inscription_id'),
                    'cours_id'       => $this->request->getPost('cours_id'),
                    'note'           => $this->request->getPost('note'),
                ];
            }

            if (! $this->noteModel->save($data)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => $this->noteModel->errors(),
                ])->setStatusCode(422);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Note saved successfully',
                'id'      => $this->noteModel->getInsertID(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Note save failed: {message}', ['message' => $e->getMessage()]);

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unexpected error while saving the note',
            ])->setStatusCode(500);
        }
    }
}
