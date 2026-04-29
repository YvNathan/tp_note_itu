<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\NoteModel;
use App\Models\InscriptionModel;
use App\Models\EtudiantModel;
use App\Models\CoursModel;

class NoteController extends Controller
{
    protected NoteModel $noteModel;
    protected InscriptionModel $inscriptionModel;
    protected EtudiantModel $etudiantModel;
    protected CoursModel $coursModel;

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

        return view('notes/form', [
            'mode'         => 'create',
            'actionUrl'    => site_url('note/save'),
            'inscriptions' => $inscriptions,
            'courses'      => $courses,
            'semesters'    => $semesters,
            'note'         => null,
        ]);
    }

    public function edit(int $id)
    {
        if (! session()->get('user_id')) {
            return redirect()->to(site_url('login'))->with('error', 'Connexion requise');
        }

        $note = $this->noteModel
            ->select('notes.id, notes.note, notes.created_at, notes.updated_at, notes.inscription_id, notes.cours_id, inscriptions.matricule, inscriptions.niveau, etudiants.id as etudiant_id, etudiants.nom, etudiants.prenoms, cours.code_ue, cours.intitule, cours.semestre')
            ->join('inscriptions', 'inscriptions.id = notes.inscription_id', 'left')
            ->join('etudiants', 'etudiants.id = inscriptions.etudiant_id', 'left')
            ->join('cours', 'cours.id = notes.cours_id', 'left')
            ->find($id);

        if (! $note) {
            throw PageNotFoundException::forPageNotFound('Note introuvable.');
        }

        return view('notes/form', [
            'mode'         => 'edit',
            'actionUrl'    => site_url('note/update/' . $id),
            'note'         => $note,
            'inscriptions' => [],
            'courses'      => [],
            'semesters'    => [],
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

    public function show(int $etudiantId)
    {
        $student = $this->etudiantModel->find($etudiantId);

        if (! $student) {
            throw PageNotFoundException::forPageNotFound('Etudiant introuvable.');
        }

        $inscription = $this->resolveTranscriptInscription($etudiantId);

        $notesS3 = $this->fetchTranscriptRows(3, null, $inscription['id'] ?? null);
        $notesS4Dev = $this->fetchTranscriptRows(4, 'dev', $inscription['id'] ?? null);
        $notesS4Bdres = $this->fetchTranscriptRows(4, 'bdres', $inscription['id'] ?? null);
        $notesS4Web = $this->fetchTranscriptRows(4, 'web', $inscription['id'] ?? null);

        $tab = strtolower((string) ($this->request->getGet('tab') ?? 's3'));
        $allowedTabs = ['s3', 's4-dev', 's4-bdres', 's4-web', 'l2-dev', 'l2-bdres', 'l2-web'];

        if (! in_array($tab, $allowedTabs, true)) {
            $tab = 's3';
        }

        $notesToShow = [];
        $moyenneGenerale = null;
        $resultCredits = 0;
        $resultAverage = null;
        $resultMention = '';
        $resultVerdict = '';

        if ($tab === 's3') {
            $notesToShow = $notesS3;
            $resultCredits = $this->sumCredits($notesToShow);
            $resultAverage = $this->weightedAverage($notesToShow);
        } elseif ($tab === 's4-dev') {
            $notesToShow = $notesS4Dev;
            $resultCredits = $this->sumCredits($notesToShow);
            $resultAverage = $this->weightedAverage($notesToShow);
        } elseif ($tab === 's4-bdres') {
            $notesToShow = $notesS4Bdres;
            $resultCredits = $this->sumCredits($notesToShow);
            $resultAverage = $this->weightedAverage($notesToShow);
        } elseif ($tab === 's4-web') {
            $notesToShow = $notesS4Web;
            $resultCredits = $this->sumCredits($notesToShow);
            $resultAverage = $this->weightedAverage($notesToShow);
        } elseif ($tab === 'l2-dev') {
            $notesToShow = array_merge($notesS3, $notesS4Dev);
            $moyenneGenerale = $this->computeL2Average($notesS3, $notesS4Dev);
            $resultCredits = $this->sumCredits($notesToShow);
            $resultAverage = $moyenneGenerale;
        } elseif ($tab === 'l2-bdres') {
            $notesToShow = array_merge($notesS3, $notesS4Bdres);
            $moyenneGenerale = $this->computeL2Average($notesS3, $notesS4Bdres);
            $resultCredits = $this->sumCredits($notesToShow);
            $resultAverage = $moyenneGenerale;
        } elseif ($tab === 'l2-web') {
            $notesToShow = array_merge($notesS3, $notesS4Web);
            $moyenneGenerale = $this->computeL2Average($notesS3, $notesS4Web);
            $resultCredits = $this->sumCredits($notesToShow);
            $resultAverage = $moyenneGenerale;
        }

        if ($resultAverage !== null) {
            $resultMention = $this->computeMention($resultAverage);
            $resultVerdict = ((float) $resultAverage >= 10.0) ? 'ADMIS(E)' : 'NON ADMIS(E)';
        }

        return view('notes/notes', [
            'student'         => $student,
            'inscription'     => $inscription,
            'tab'             => $tab,
            'notes'           => $notesToShow,
            'moyenneGenerale' => $moyenneGenerale,
            'resultCredits'   => $resultCredits,
            'resultAverage'   => $resultAverage,
            'resultMention'   => $resultMention,
            'resultVerdict'   => $resultVerdict,
            'tabs'            => [
                's3'       => 'S3',
                's4-dev'   => 'S4 Dev',
                's4-bdres' => 'S4 BDRés',
                's4-web'   => 'S4 Web',
                'l2-dev'   => 'L2 Dev',
                'l2-bdres' => 'L2 BDRés',
                'l2-web'   => 'L2 Web',
            ],
        ]);
    }

    public function history(int $etudiantId, int $coursId)
    {
        $student = $this->etudiantModel->find($etudiantId);
        $course = $this->coursModel->find($coursId);
        $inscription = $this->resolveTranscriptInscription($etudiantId);

        if (! $student || ! $course) {
            throw PageNotFoundException::forPageNotFound('Ressource introuvable.');
        }

        $notes = [];

        if ($inscription) {
            $notes = $this->noteModel
                ->where('inscription_id', $inscription['id'])
                ->where('cours_id', $coursId)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->findAll();
        }

        return view('notes/history', [
            'student'     => $student,
            'course'      => $course,
            'inscription' => $inscription,
            'notes'       => $notes,
        ]);
    }

    public function save()
    {
        try {
            $data = $this->extractNotePayload();

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

    public function update(int $id)
    {
        if (! session()->get('user_id')) {
            return redirect()->to(site_url('login'))->with('error', 'Connexion requise');
        }

        $note = $this->noteModel
            ->select('notes.id, notes.inscription_id, notes.cours_id, inscriptions.etudiant_id')
            ->join('inscriptions', 'inscriptions.id = notes.inscription_id', 'left')
            ->find($id);

        if (! $note) {
            throw PageNotFoundException::forPageNotFound('Note introuvable.');
        }

        $data = [
            'note' => $this->request->getPost('note'),
        ];

        if (! $this->noteModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'La note n\'a pas pu être modifiée.');
        }

        return redirect()->to(site_url('etudiants/' . $note['etudiant_id'] . '/matieres/' . $note['cours_id']))
            ->with('success', 'Note mise à jour avec succès.');
    }

    public function delete(int $id)
    {
        if (! session()->get('user_id')) {
            return redirect()->to(site_url('login'))->with('error', 'Connexion requise');
        }

        $note = $this->noteModel
            ->select('notes.id, notes.cours_id, inscriptions.etudiant_id')
            ->join('inscriptions', 'inscriptions.id = notes.inscription_id', 'left')
            ->find($id);

        if (! $note) {
            throw PageNotFoundException::forPageNotFound('Note introuvable.');
        }

        $this->noteModel->delete($id);

        return redirect()->to(site_url('etudiants/' . $note['etudiant_id'] . '/matieres/' . $note['cours_id']))
            ->with('success', 'Note supprimée avec succès.');
    }

    public function store()
    {
        return $this->save();
    }

    private function resolveTranscriptInscription(int $etudiantId): ?array
    {
        return $this->inscriptionModel
            ->where('etudiant_id', $etudiantId)
            ->orderBy('id', 'DESC')
            ->first();
    }

    private function fetchTranscriptRows(int $semestre, ?string $parcoursSlug = null, ?int $inscriptionId = null): array
    {
        $db = db_connect();
        $builder = $db->table('cours c');
        $builder->select('c.id as cours_id, c.code_ue, c.intitule, c.credits, c.semestre');

        if ($inscriptionId !== null) {
            $notesSubquery = $db->table('notes')
                ->select('inscription_id, cours_id, MAX(note) as note')
                ->where('inscription_id', $inscriptionId)
                ->groupBy(['inscription_id', 'cours_id'])
                ->getCompiledSelect();

            $builder->select('COALESCE(n.note, 0) as note', false)
                ->join('(' . $notesSubquery . ') n', 'n.cours_id = c.id', 'left', false);
        } else {
            $builder->select('0 as note', false);
        }

        // When filtering by parcours, include parcours_cours metadata so we can
        // handle option groups (groupe_option) in PHP and pick the course with
        // the best note inside each option group.
        $joinedParcours = false;
        if ($parcoursSlug !== null) {
            $joinedParcours = true;
            $builder->join('parcours_cours pc', 'pc.cours_id = c.id', 'left')
                ->join('parcours ptab', 'ptab.id = pc.parcours_id', 'left')
                ->select('pc.est_optionnel, pc.groupe_option');

            $this->applyParcoursFilter($builder, $parcoursSlug, 'ptab.nom');
        }

        $rows = $builder
            ->where('c.semestre', $semestre)
            ->groupBy('c.id')
            ->orderBy('c.code_ue', 'ASC')
            ->get()
            ->getResultArray();

        // If parcours joined and groupe_option present, select only the best
        // course per option group (the one with the highest note). Non-option
        // courses are kept as-is. This is done in PHP to avoid complex SQL.
        if ($joinedParcours) {
            $nonOptionals = [];
            $groupBest = [];

            foreach ($rows as $r) {
                $g = isset($r['groupe_option']) ? trim((string) $r['groupe_option']) : '';
                $isOptional = isset($r['est_optionnel']) && (int) $r['est_optionnel'] === 1;

                if ($isOptional && $g !== '') {
                    // ensure numeric note
                    $noteVal = isset($r['note']) ? (float) $r['note'] : 0.0;

                    if (! isset($groupBest[$g]) || $noteVal > (float) $groupBest[$g]['note']) {
                        $groupBest[$g] = $r;
                    }
                    continue;
                }

                // keep non-optionals
                $nonOptionals[] = $r;
            }

            // Merge non-optionals and best of each group
            $final = $nonOptionals;
            foreach ($groupBest as $best) {
                $final[] = $best;
            }

            // Sort final by code_ue to keep deterministic order
            usort($final, function ($a, $b) {
                return strcmp($a['code_ue'] ?? '', $b['code_ue'] ?? '');
            });

            return $final;
        }

        return $rows;
    }

    private function applyParcoursFilter($builder, string $parcoursSlug, string $column = 'p.nom'): void
    {
        if ($parcoursSlug === 'dev') {
            $builder->groupStart()
                ->like($column, 'develop')
                ->orLike($column, 'developpement')
                ->orLike($column, 'dev')
                ->groupEnd();

            return;
        }

        if ($parcoursSlug === 'bdres') {
            $builder->groupStart()
                ->like($column, 'base')
                ->orLike($column, 'donnee')
                ->orLike($column, 'reseau')
                ->orLike($column, 'bd')
                ->groupEnd();

            return;
        }

        if ($parcoursSlug === 'web') {
            $builder->groupStart()
                ->like($column, 'web')
                ->orLike($column, 'design')
                ->groupEnd();
        }
    }

    private function extractNotePayload(): array
    {
        $contentType = strtolower($this->request->getHeaderLine('Content-Type'));

        if (str_contains($contentType, 'application/json')) {
            $payload = $this->request->getJSON(true);

            return [
                'inscription_id' => $payload['inscription_id'] ?? null,
                'cours_id'       => $payload['cours_id'] ?? null,
                'note'           => $payload['note'] ?? null,
            ];
        }

        return [
            'inscription_id' => $this->request->getPost('inscription_id'),
            'cours_id'       => $this->request->getPost('cours_id'),
            'note'           => $this->request->getPost('note'),
        ];
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

    private function weightedAverage(array $notes): ?float
    {
        if (empty($notes)) {
            return null;
        }

        $totalWeight = 0.0;
        $weightedSum = 0.0;

        foreach ($notes as $row) {
            $cred = isset($row['credits']) ? (float) $row['credits'] : 0.0;
            $note = isset($row['note']) ? (float) $row['note'] : 0.0;
            $weightedSum += $note * $cred;
            $totalWeight += $cred;
        }

        if ($totalWeight == 0.0) {
            return null;
        }

        return $weightedSum / $totalWeight;
    }

    private function sumCredits(array $notes): int
    {
        $sum = 0;
        foreach ($notes as $row) {
            $sum += isset($row['credits']) ? (int) $row['credits'] : 0;
        }
        return $sum;
    }

    private function computeMention(float $avg): string
    {
        if ($avg >= 16.0) {
            return 'Très Bien';
        }
        if ($avg >= 14.0) {
            return 'Bien';
        }
        if ($avg >= 12.0) {
            return 'Assez Bien';
        }
        if ($avg >= 10.0) {
            return 'Passable';
        }

        return 'Ajourné';
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
