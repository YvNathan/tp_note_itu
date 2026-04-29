<?php

namespace App\Controllers;

use App\Models\EtudiantModel;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class Etudiant extends Controller
{
    public function index()
    {
        $model = new EtudiantModel();

        $data['etudiants'] = $model->orderBy('nom', 'ASC')
            ->orderBy('prenoms', 'ASC')
            ->findAll();

        return view('template/list', $data);
    }

    public function notes(int $id)
    {
        $tab = strtolower((string) ($this->request->getGet('tab') ?? 's3'));
        $allowedTabs = ['s3', 's4-dev', 's4-bdres', 's4-web', 'l2-dev', 'l2-bdres', 'l2-web'];

        if (! in_array($tab, $allowedTabs, true)) {
            $tab = 's3';
        }

        $etudiantModel = new EtudiantModel();
        $etudiant = $etudiantModel->find($id);

        if (! $etudiant) {
            throw PageNotFoundException::forPageNotFound('Etudiant introuvable.');
        }

        $notesS3 = $this->fetchSemesterNotes($id, 3);
        $notesS4Dev = $this->fetchSemesterNotesForParcours($id, 4, 'dev');
        $notesS4Bdres = $this->fetchSemesterNotesForParcours($id, 4, 'bdres');
        $notesS4Web = $this->fetchSemesterNotesForParcours($id, 4, 'web');

        $notesToShow = [];
        $moyenneGenerale = null;

        if ($tab === 's3') {
            $notesToShow = $notesS3;
        } elseif ($tab === 's4-dev') {
            $notesToShow = $notesS4Dev;
        } elseif ($tab === 's4-bdres') {
            $notesToShow = $notesS4Bdres;
        } elseif ($tab === 's4-web') {
            $notesToShow = $notesS4Web;
        } elseif ($tab === 'l2-dev') {
            $notesToShow = array_merge($notesS3, $notesS4Dev);
            $moyenneGenerale = $this->computeL2Average($notesS3, $notesS4Dev);
        } elseif ($tab === 'l2-bdres') {
            $notesToShow = array_merge($notesS3, $notesS4Bdres);
            $moyenneGenerale = $this->computeL2Average($notesS3, $notesS4Bdres);
        } elseif ($tab === 'l2-web') {
            $notesToShow = array_merge($notesS3, $notesS4Web);
            $moyenneGenerale = $this->computeL2Average($notesS3, $notesS4Web);
        }

        $data = [
            'etudiant' => $etudiant,
            'tab' => $tab,
            'notes' => $notesToShow,
            'moyenneGenerale' => $moyenneGenerale,
            'tabs' => [
                's3' => 'S3',
                's4-dev' => 'S4 Dev',
                's4-bdres' => 'S4 BDRés',
                's4-web' => 'S4 Web',
                'l2-dev' => 'L2 Dev',
                'l2-bdres' => 'L2 BDRés',
                'l2-web' => 'L2 Web',
            ],
        ];

        return view('etudiant/notes', $data);
    }

    private function fetchSemesterNotes(int $etudiantId, int $semestre): array
    {
        $db = db_connect();

        return $db->table('notes n')
            ->select('c.id as cours_id, c.code_ue, c.intitule, c.credits, c.semestre, n.note')
            ->join('inscriptions i', 'i.id = n.inscription_id')
            ->join('cours c', 'c.id = n.cours_id')
            ->where('i.etudiant_id', $etudiantId)
            ->where('i.niveau', 'L2')
            ->where('c.semestre', $semestre)
            ->orderBy('c.code_ue', 'ASC')
            ->get()
            ->getResultArray();
    }

    private function fetchSemesterNotesForParcours(int $etudiantId, int $semestre, string $parcoursSlug): array
    {
        $db = db_connect();

        $builder = $db->table('notes n')
            ->select('c.id as cours_id, c.code_ue, c.intitule, c.credits, c.semestre, n.note')
            ->join('inscriptions i', 'i.id = n.inscription_id')
            ->join('parcours p', 'p.id = i.parcours_id')
            ->join('cours c', 'c.id = n.cours_id')
            ->join('parcours_cours pc', 'pc.cours_id = c.id AND pc.parcours_id = p.id')
            ->where('i.etudiant_id', $etudiantId)
            ->where('i.niveau', 'L2')
            ->where('c.semestre', $semestre);

        $this->applyParcoursFilter($builder, $parcoursSlug);

        return $builder->orderBy('c.code_ue', 'ASC')->get()->getResultArray();
    }

    private function applyParcoursFilter($builder, string $parcoursSlug): void
    {
        if ($parcoursSlug === 'dev') {
            $builder->groupStart()
                ->like('p.nom', 'develop')
                ->orLike('p.nom', 'developpement')
                ->orLike('p.nom', 'dev')
                ->groupEnd();

            return;
        }

        if ($parcoursSlug === 'bdres') {
            $builder->groupStart()
                ->like('p.nom', 'base')
                ->orLike('p.nom', 'donnee')
                ->orLike('p.nom', 'reseau')
                ->orLike('p.nom', 'bd')
                ->groupEnd();

            return;
        }

        if ($parcoursSlug === 'web') {
            $builder->groupStart()
                ->like('p.nom', 'web')
                ->orLike('p.nom', 'design')
                ->groupEnd();
        }
    }

    private function average(array $notes): ?float
    {
        if ($notes === []) {
            return null;
        }

        $sum = 0.0;

        foreach ($notes as $row) {
            $sum += (float) $row['note'];
        }

        return $sum / count($notes);
    }

    private function computeL2Average(array $notesS3, array $notesS4): ?float
    {
        $m3 = $this->average($notesS3);
        $m4 = $this->average($notesS4);

        if ($m3 === null && $m4 === null) {
            return null;
        }

        if ($m3 === null) {
            return $m4;
        }

        if ($m4 === null) {
            return $m3;
        }

        return ($m3 + $m4) / 2;
    }
}
