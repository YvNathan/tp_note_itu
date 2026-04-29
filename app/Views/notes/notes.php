<?php
$student = $student ?? [];
$inscription = $inscription ?? [];
$notes = $notes ?? [];
$tabs = $tabs ?? [];
$tab = $tab ?? 's3';
$moyenneGenerale = $moyenneGenerale ?? null;
$successMessage = session()->getFlashdata('success');
$successMessage = is_scalar($successMessage) ? (string) $successMessage : '';
// Result summary defaults (provided by controller)
$resultCredits = $resultCredits ?? 0;
$resultAverage = $resultAverage ?? null;
$resultMention = $resultMention ?? '';
$resultVerdict = $resultVerdict ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SysInfo — Relevé de notes</title>
  <link rel="stylesheet" href="<?= base_url('assets/template/style.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/template/notes.css') ?>" />
</head>
<body>
<div class="app">
  <?= view('partials/sidebar') ?>

  <div class="main">
    <div class="topbar">
      <div class="topbar-title">Relevé de notes</div>
      <div class="topbar-actions">
        <a href="<?= site_url('note/form') ?>" class="btn btn-secondary btn-sm">Nouvelle note</a>
        <a href="<?= site_url('list') ?>" class="btn btn-secondary btn-sm">Retour à la liste</a>
      </div>
    </div>

    <div class="content">
      <div class="page-header">
        <div>
          <h2><?= esc((string) ($student['nom'] ?? '') . ' ' . (string) ($student['prenoms'] ?? '')) ?></h2>
          <div class="breadcrumb">Accueil / Etudiants / <span>Relevé</span></div>
        </div>
      </div>

      <?php if ($successMessage !== ''): ?>
        <div class="alert alert-info"><?= esc($successMessage) ?></div>
      <?php endif; ?>

      <div class="notes-toolbar">
        <div class="student-meta">
          <span>ID: <strong><?= esc((string) ($student['id'] ?? '')) ?></strong></span>
          <?php if (! empty($inscription['matricule'])): ?>
            <span>-</span>
            <span>Matricule: <strong><?= esc((string) $inscription['matricule']) ?></strong></span>
          <?php endif; ?>
          <span>-</span>
          <span>Date naissance: <strong><?= esc((string) ($student['date_naissance'] ?? '')) ?></strong></span>
          <span>-</span>
          <span>Lieu: <strong><?= esc((string) ($student['lieu_naissance'] ?? '')) ?></strong></span>
        </div>
      </div>

      <div class="tabs-row">
        <?php foreach ($tabs as $tabKey => $tabLabel): ?>
          <a class="tab-pill <?= $tab === $tabKey ? 'active' : '' ?>" href="<?= site_url('etudiants/' . ($student['id'] ?? 0) . '/notes') . '?tab=' . $tabKey ?>">
            <?= esc($tabLabel) ?>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="alert alert-info">Cliquez sur une matière pour consulter son historique de notes.</div>

      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>Code UE</th>
              <th>Intitulé</th>
              <th>Crédits</th>
              <th>Semestre</th>
              <th>Note</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if (empty($notes)): ?>
            <tr>
              <td colspan="6" class="empty-state">Aucune note disponible pour cet onglet.</td>
            </tr>
          <?php else: ?>
            <?php
              // For L2 tabs, group by semester for visual separation
              $isL2 = strpos($tab, 'l2') === 0;
              $groupedNotes = [];
              if ($isL2) {
                foreach ($notes as $row) {
                  $sem = (int) ($row['semestre'] ?? 0);
                  if (!isset($groupedNotes[$sem])) {
                    $groupedNotes[$sem] = [];
                  }
                  $groupedNotes[$sem][] = $row;
                }
                ksort($groupedNotes);
              } else {
                $groupedNotes[0] = $notes;
              }
            ?>
            <?php foreach ($groupedNotes as $semester => $semesterNotes): ?>
              <?php if ($isL2 && $semester > 0): ?>
                <!-- Separator for L2 -->
                <tr style="height:24px">
                  <td colspan="6" style="background:transparent;border:none;padding:0"></td>
                </tr>
              <?php endif; ?>
              <?php foreach ($semesterNotes as $row): ?>
              <tr>
                <td><a href="<?= site_url('etudiants/' . ($student['id'] ?? 0) . '/matieres/' . ($row['cours_id'] ?? 0)) ?>"><?= esc((string) ($row['code_ue'] ?? '')) ?></a></td>
                <td><a href="<?= site_url('etudiants/' . ($student['id'] ?? 0) . '/matieres/' . ($row['cours_id'] ?? 0)) ?>"><?= esc((string) ($row['intitule'] ?? '')) ?></a></td>
                <td><?= esc((string) ($row['credits'] ?? '')) ?></td>
                <td><?= esc((string) ($row['semestre'] ?? '')) ?></td>
                <td class="note-value"><?= esc(number_format((float) $row['note'], 2, '.', '')) ?></td>
                <td>
                  <a href="<?= site_url('etudiants/' . ($student['id'] ?? 0) . '/matieres/' . ($row['cours_id'] ?? 0)) ?>" class="action-btn" title="Historique">
                    <svg viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 3-6.7"/><polyline points="3 3 3 9 9 9"/></svg>
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">Résultat</div>
        </div>
        <div style="padding:var(--spacing-lg)">
          <div style="display:grid;gap:var(--spacing-md)">
            <div><span style="color:var(--c-muted)">Crédits</span><br /><strong><?= esc((string) ($resultCredits ?? 0)) ?></strong></div>
            <div><span style="color:var(--c-muted)">Moyenne générale</span><br /><strong><?= esc($resultAverage !== null ? number_format((float) $resultAverage, 2, '.', '') : '-') ?></strong></div>
            <div><span style="color:var(--c-muted)">Mention</span><br /><strong><?= esc((string) ($resultMention ?? '')) ?></strong></div>
            <div style="margin-top:var(--spacing-md);padding-top:var(--spacing-md);border-top:1px solid var(--c-border)">
              <div style="font-size:18px;font-weight:bold;color:<?= ((float) $resultAverage >= 10.0) ? 'var(--c-success)' : 'var(--c-danger)' ?>"><?= esc((string) ($resultVerdict ?? '')) ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
