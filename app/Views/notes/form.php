<?php
$inscriptions = $inscriptions ?? [];
$courses = $courses ?? [];
$semesters = $semesters ?? [];
$note = $note ?? [];
$mode = $mode ?? 'create';
$isEdit = $mode === 'edit';
$actionUrl = $actionUrl ?? site_url('note/save');
$noteValue = $note['note'] ?? '';
$formTitle = $isEdit ? 'Modifier une note' : 'Saisie de note';
$breadcrumb = $isEdit ? 'Accueil / Notes / <span>Modification</span>' : 'Accueil / Notes / <span>Nouvelle</span>';
$studentDisplay = trim(($note['nom'] ?? '') . ' ' . ($note['prenoms'] ?? ''));
$courseDisplay = trim(($note['code_ue'] ?? '') . ' - ' . ($note['intitule'] ?? ''));
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
  <title>SysInfo — <?= esc($formTitle) ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/template/style.css') ?>" />
</head>
<body>
<div class="app">
  <?= view('partials/sidebar') ?>

  <div class="main">

    <div class="topbar">
      <div class="topbar-title"><?= esc($formTitle) ?></div>
      <div class="topbar-search">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Rechercher…" />
      </div>
    </div>

    <div class="content">

      <div class="page-header">
        <div>
          <h2><?= esc($formTitle) ?></h2>
          <div class="breadcrumb"><?= $breadcrumb ?></div>
        </div>
        <a href="<?= $isEdit ? site_url('etudiants/' . ($note['etudiant_id'] ?? 0) . '/matieres/' . ($note['cours_id'] ?? 0)) : site_url('dashboard') ?>" class="btn btn-secondary btn-sm">
          <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
          Retour
        </a>
      </div>

      <div class="alert alert-info">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>Les champs marqués d'un <strong>*</strong> sont obligatoires.</span>
      </div>

      <?php if ($errorMessage !== ''): ?>
        <div class="alert alert-danger"><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <?php if ($isEdit): ?>
      <form action="<?= esc($actionUrl) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-card section-gap">
          <div class="form-section-title">Note à modifier</div>
          <div class="form-grid">
            <div>
              <label class="field-label">Étudiant</label>
              <input type="text" value="<?= esc((string) $studentDisplay) ?>" readonly />
            </div>
            <div>
              <label class="field-label">Matricule</label>
              <input type="text" value="<?= esc((string) ($note['matricule'] ?? '')) ?>" readonly />
            </div>
            <div>
              <label class="field-label">Cours</label>
              <input type="text" value="<?= esc((string) $courseDisplay) ?>" readonly />
            </div>
            <div>
              <label class="field-label">Semestre</label>
              <input type="text" value="<?= esc((string) ($note['semestre'] ?? '')) ?>" readonly />
            </div>
          </div>
          <input type="hidden" name="inscription_id" value="<?= esc((string) ($note['inscription_id'] ?? '')) ?>" />
          <input type="hidden" name="cours_id" value="<?= esc((string) ($note['cours_id'] ?? '')) ?>" />
          <div class="form-grid cols-1">
            <div>
              <label class="field-label">Note <span class="required">*</span></label>
              <div class="input-group">
                <input type="number" id="noteInput" name="note" min="0" max="20" step="0.25" value="<?= esc((string) $noteValue) ?>" required />
                <span class="addon addon-right">/ 20</span>
              </div>
            </div>
          </div>
        </div>

        <div class="form-footer">
          <a href="<?= site_url('etudiants/' . ($note['etudiant_id'] ?? 0) . '/matieres/' . ($note['cours_id'] ?? 0)) ?>" class="btn btn-secondary">Annuler</a>
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Mettre à jour la note
          </button>
        </div>
      </form>
      <?php else: ?>
      <form id="noteForm">

        <!-- ── 1. Sélection d'étudiant ──────────────────────────────── -->
        <div class="form-card section-gap">
          <div class="form-section-title">1. Informations de l'étudiant</div>
          <div class="form-grid">
            <div>
              <label class="field-label">Étudiant (par matricule) <span class="required">*</span></label>
              <select id="inscriptionSelect" name="inscription_id" required>
                <option value="">— Sélectionner un étudiant —</option>
                <?php foreach ($inscriptions as $inscription): ?>
                  <option value="<?= esc((string) ($inscription['id'] ?? '')) ?>" data-nom="<?= esc((string) ($inscription['nom'] ?? '')) ?>" data-prenoms="<?= esc((string) ($inscription['prenoms'] ?? '')) ?>">
                    <?= esc((string) ($inscription['matricule'] ?? '')) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="field-label">Nom &amp; Prénom</label>
              <input type="text" id="studentName" placeholder="Auto-rempli" readonly />
            </div>
          </div>
        </div>

        <!-- ── 2. Sélection du cours et note ────────────────────────── -->
        <div class="form-card section-gap">
          <div class="form-section-title">2. Cours et note</div>
          <div class="form-grid">
            <div>
              <label class="field-label">Semestre <span class="required">*</span></label>
              <select id="semesterSelect" name="semester" required>
                <option value="">— Sélectionner un semestre —</option>
                <?php foreach ($semesters as $semester): ?>
                  <option value="<?= esc((string) ($semester['semestre'] ?? '')) ?>">Semestre <?= esc((string) ($semester['semestre'] ?? '')) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="field-label">Cours <span class="required">*</span></label>
              <select id="courseSelect" name="cours_id" required>
                <option value="">— Sélectionner un cours —</option>
              </select>
            </div>
            <div>
              <label class="field-label">Note <span class="required">*</span></label>
              <div class="input-group">
                <input type="number" id="noteInput" name="note" min="0" max="20" step="0.25" placeholder="Ex : 15.5" required />
                <span class="addon addon-right">/ 20</span>
              </div>
            </div>
          </div>
        </div>

        <!-- ── Footer boutons ─────────────────────────────────────────── -->
        <div class="form-footer">
          <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Annuler</a>
          <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            Enregistrer la note
          </button>
        </div>

      </form>
      <?php endif; ?>

    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /app -->

<?php if (! $isEdit): ?>
<script>
  const inscriptionSelect = document.getElementById('inscriptionSelect');
  const studentNameField = document.getElementById('studentName');
  const semesterSelect = document.getElementById('semesterSelect');
  const courseSelect = document.getElementById('courseSelect');
  const noteForm = document.getElementById('noteForm');
  const allCourses = <?= json_encode($courses, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  const actionUrl = <?= json_encode($actionUrl) ?>;

  function updateStudentName() {
    const selectedOption = inscriptionSelect.selectedOptions[0];

    if (!selectedOption || !selectedOption.value) {
      studentNameField.value = '';
      return;
    }

    const nom = selectedOption.dataset.nom || '';
    const prenoms = selectedOption.dataset.prenoms || '';
    studentNameField.value = `${nom} ${prenoms}`.trim();
  }

  inscriptionSelect.addEventListener('change', updateStudentName);
  updateStudentName();

  function renderCourses(semester) {
    courseSelect.innerHTML = '<option value="">— Sélectionner un cours —</option>';

    if (!semester) {
      return;
    }

    const filteredCourses = allCourses.filter(course => String(course.semestre) === String(semester));

    filteredCourses.forEach(course => {
      const option = document.createElement('option');
      option.value = course.id;
      option.textContent = `${course.code_ue} - ${course.intitule}`;
      courseSelect.appendChild(option);
    });
  }

  // Load courses when semester is selected
  semesterSelect.addEventListener('change', function () {
    renderCourses(this.value);
  });

  // Handle form submission
  noteForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {
      const response = await fetch(actionUrl, {
        method: 'POST',
        body: formData,
      });

      const responseText = await response.text();
      let payload = {};

      try {
        payload = responseText ? JSON.parse(responseText) : {};
      } catch (parseError) {
        payload = { message: responseText || 'Réponse serveur invalide' };
      }

      if (response.ok) {
        alert('Note enregistrée avec succès!');
        document.getElementById('noteForm').reset();
        document.getElementById('studentName').value = '';
        courseSelect.innerHTML = '<option value="">— Sélectionner un cours —</option>';
      } else {
        const errorMessage = payload.message || 'Erreur inconnue';
        const errorDetails = payload.errors ? JSON.stringify(payload.errors) : '';
        alert(`Erreur: ${errorMessage}${errorDetails ? ' ' + errorDetails : ''}`);
      }
    } catch (error) {
      console.error('Error saving note:', error);
      alert('Une erreur est survenue lors de l\'enregistrement.');
    }
  });
</script>
<?php endif; ?>

</body>
</html>
