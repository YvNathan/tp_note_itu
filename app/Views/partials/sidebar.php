<?php
$userName = session()->get('user_nom') ?? 'Invité';
?>
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24" width="18" height="18"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    </div>
    <div>
      <div class="brand-name">SysInfo</div>
      <div class="brand-sub">v2.4.0</div>
    </div>
  </div>

  <div class="sidebar-section">Navigation</div>
  <a href="<?= site_url('list') ?>" class="nav-item">Etudiants</a>
  <a href="<?= site_url('note/form') ?>" class="nav-item">Saisie de note</a>

  <div class="sidebar-bottom">
    <div class="user-row">
      <div class="avatar"><?= strtoupper(substr($userName, 0, 2)) ?></div>
      <div class="user-info">
        <div class="name"><?= esc((string) $userName) ?></div>
      </div>
    </div>
    <a href="<?= site_url('logout') ?>" class="nav-item" style="margin-top:var(--spacing-md);color:var(--c-danger);">
      <svg viewBox="0 0 24 24" style="width:18px;height:18px;stroke-width:2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
      Déconnexion
    </a>
  </div>
</aside>
