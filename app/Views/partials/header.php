<?php
$userName = session()->get('user_nom') ?? 'Invité';
$isLogged = (bool) session()->get('user_id');
?>
<header class="topbar">
  <div class="topbar-left">
    <div class="topbar-title">SysInfo</div>
  </div>
  <div class="topbar-right">
    <?php if ($isLogged): ?>
      <span class="user-name"><?= esc((string) $userName) ?></span>
      <a href="<?= site_url('logout') ?>" class="btn btn-logout">Déconnexion</a>
    <?php else: ?>
      <a href="<?= site_url('login') ?>" class="btn btn-login">Connexion</a>
    <?php endif; ?>
  </div>
</header>
