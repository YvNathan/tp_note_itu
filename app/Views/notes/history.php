<?php
$student = $student ?? [];
$course = $course ?? [];
$inscription = $inscription ?? [];
$notes = $notes ?? [];
$successMessage = session()->getFlashdata('success');
$errorMessage = session()->getFlashdata('error');
$successMessage = is_scalar($successMessage) ? (string) $successMessage : '';
$errorMessage = is_scalar($errorMessage) ? (string) $errorMessage : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SysInfo — Historique de note</title>
  <link rel="stylesheet" href="<?= base_url('assets/template/style.css') ?>" />
</head>
<body>
<div class="app">
  <?= view('partials/sidebar') ?>

  <div class="main">
    <div class="topbar">
      <div class="topbar-title">Historique de note</div>
      <div class="topbar-actions">
        <a href="<?= site_url('etudiants/' . ($student['id'] ?? 0) . '/notes') ?>" class="btn btn-secondary btn-sm">Retour au relevé</a>
        <a href="<?= site_url('note/form') ?>" class="btn btn-primary btn-sm">Ajouter une note</a>
      </div>
    </div>

    <div class="content">
      <div class="page-header">
        <div>
          <h2><?= esc((string) ($student['nom'] ?? '') . ' ' . (string) ($student['prenoms'] ?? '')) ?></h2>
          <div class="breadcrumb">Accueil / Etudiants / <span>Historique</span></div>
        </div>
      </div>

      <?php if ($successMessage !== ''): ?>
        <div class="alert alert-info"><?= esc($successMessage) ?></div>
      <?php endif; ?>

      <?php if ($errorMessage !== ''): ?>
        <div class="alert alert-danger"><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <div class="notes-toolbar">
        <div class="student-meta">
          <span>Matricule: <strong><?= esc((string) ($inscription['matricule'] ?? '-')) ?></strong></span>
          <span>-</span>
          <span>Matière: <strong><?= esc((string) ($course['code_ue'] ?? '') . ' - ' . (string) ($course['intitule'] ?? '')) ?></strong></span>
          <span>-</span>
          <span>Semestre: <strong><?= esc((string) ($course['semestre'] ?? '')) ?></strong></span>
        </div>
      </div>

      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Note</th>
              <th>Créée le</th>
              <th>Modifiée le</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($notes)): ?>
              <tr>
                <td colspan="4" class="empty-state">Aucune note enregistrée pour cette matière.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($notes as $note): ?>
                <tr>
                  <td class="note-value"><?= esc(number_format((float) $note['note'], 2, '.', '')) ?></td>
                  <td><?= esc((string) ($note['created_at'] ?? '-')) ?></td>
                  <td><?= esc((string) ($note['updated_at'] ?? '-')) ?></td>
                  <td>
                    <div class="td-actions">
                      <a href="<?= site_url('note/edit/' . $note['id']) ?>" class="action-btn" title="Modifier">
                        <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      </a>
                      <form action="<?= site_url('note/delete/' . $note['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Supprimer cette note ?');">
                        <?= csrf_field() ?>
                        <button type="submit" class="action-btn" title="Supprimer">
                          <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>
