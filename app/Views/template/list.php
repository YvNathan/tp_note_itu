<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SysInfo — Utilisateurs</title>
  <link rel="stylesheet" href="<?= base_url('assets/template/style.css') ?>" />
</head>
<body>
<div class="app">
  <?= view('partials/sidebar') ?>

  <div class="main">

    <div class="topbar">
      <div class="topbar-title">Gestion des utilisateurs</div>
      <div class="topbar-search">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Rechercher…" />
      </div>
      <div class="topbar-actions">
        <button class="icon-btn">
          <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <span class="notif-dot"></span>
        </button>
        <button class="icon-btn">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
        </button>
      </div>
    </div>

    <div class="content">

      <div class="page-header">
        <div>
          <h2>Liste des utilisateurs</h2>
          <div class="breadcrumb">Accueil / <span>Utilisateurs</span></div>
        </div>
        <div style="display:flex;gap:10px">
          <button class="btn btn-secondary btn-sm">
            <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Exporter
          </button>
          <a href="<?= site_url('form') ?>" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Nouvel utilisateur
          </a>
        </div>
      </div>

      <!-- Toolbar filtres -->
      <div class="toolbar">
        <div class="toolbar-left">
          <div class="search-box">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Rechercher un utilisateur…" />
          </div>
          <select class="filter-select">
            <option>Tous les rôles</option>
            <option>Administrateur</option>
            <option>Gestionnaire</option>
            <option>Opérateur</option>
            <option>Auditeur</option>
          </select>
          <select class="filter-select">
            <option>Tous les statuts</option>
            <option>Actif</option>
            <option>Inactif</option>
            <option>Suspendu</option>
          </select>
          <select class="filter-select">
            <option>Département</option>
            <option>DSI</option>
            <option>Finance</option>
            <option>RH</option>
            <option>Commercial</option>
          </select>
        </div>
        <button class="btn btn-ghost btn-sm">
          <svg viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Filtres avancés
        </button>
      </div>

      <!-- Tableau -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th class="td-check"><input type="checkbox" /></th>
              <th class="sortable">Nom</th>
              <th class="sortable">Prenoms</th>
              <th class="sortable">Date de naissance</th>
              <th>Lieu de naissance</th>
              <th class="sortable">ID</th>
              <th class="sortable">Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($etudiants)): ?>
              <tr>
                <td colspan="8" style="text-align:center;color:var(--c-muted);padding:24px;">Aucun etudiant trouve.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($etudiants as $etudiant): ?>
                <tr>
                  <td><input type="checkbox" /></td>
                  <td style="font-weight:600;"><?= esc((string) ($etudiant['nom'] ?? '')) ?></td>
                  <td><?= esc((string) ($etudiant['prenoms'] ?? '')) ?></td>
                  <td><?= esc((string) ($etudiant['date_naissance'] ?? '')) ?></td>
                  <td><?= esc((string) ($etudiant['lieu_naissance'] ?? '')) ?></td>
                  <td style="color:var(--c-muted);font-family:monospace"><?= esc((string) ($etudiant['id'] ?? '')) ?></td>
                  <td><span class="badge badge-green">Actif</span></td>
                  <td>
                    <div class="td-actions">
                      <a href="<?= site_url('etudiants/' . $etudiant['id'] . '/notes') ?>" class="action-btn" title="Voir details">
                        <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>

          </tbody>
        </table>

        <div class="pagination">
          <span>Affichage de <strong>1–6</strong> sur <strong>284</strong> entrées</span>
          <div class="page-btns">
            <button class="page-btn">‹</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">…</button>
            <button class="page-btn">48</button>
            <button class="page-btn">›</button>
          </div>
        </div>

      </div><!-- /table-card -->

    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /app -->

</body>
</html>
