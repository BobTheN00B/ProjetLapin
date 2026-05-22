<?php
// View/layout.php – inclus par chaque vue
// Variables attendues : $pageTitle, $activeMenu
function layoutStart(string $pageTitle, string $activeMenu = ''): void {
    $user = $_SESSION['user'] ?? null;
?>
<div class = "Layout">
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> – 🐰 Lapin de Pâques</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav>
  <a class="nav-brand" href="index.php?page=dashboard">🐰 Lapin Boss</a>
  <?php if ($user): ?>
  <div class="nav-links">
    <a href="index.php?page=dashboard"  class="<?= $activeMenu==='dashboard'  ?'active':'' ?>">🏠 Accueil</a>
    <a href="index.php?page=lapins"     class="<?= $activeMenu==='lapins'     ?'active':'' ?>">🐰 Lapins</a>
    <a href="index.php?page=magasins" class="<?= $activeMenu === 'magasins' ? 'active' : '' ?>">🍫 Magasins</a>
    <a href="index.php?page=zones"      class="<?= $activeMenu==='zones'      ?'active':'' ?>">🗺️ Zones</a>
    <a href="index.php?page=jardins"    class="<?= $activeMenu==='jardins'    ?'active':'' ?>">🌱 Jardins</a>
    <a href="index.php?page=collectes"  class="<?= $activeMenu==='collectes'  ?'active':'' ?>">🚚 Collectes</a>
    <a href="index.php?page=livraisons" class="<?= $activeMenu==='livraisons' ?'active':'' ?>">📦 Livraisons</a>
    <a href="index.php?page=logs"       class="<?= $activeMenu==='logs'       ?'active':'' ?>">📋 Logs</a>
    <?php if (($user['role'] ?? '') === 'admin'): ?>
    <a href="index.php?page=utilisateurs" class="<?= $activeMenu==='utilisateurs'?'active':'' ?>">👤 Utilisateurs</a>
    <?php endif; ?>
  </div>
  <div class="nav-user">
    👋 <?= htmlspecialchars($user['nom']) ?>
    &nbsp;|&nbsp;<a href="index.php?page=logout">Déconnexion</a>
  </div>
  <?php endif; ?>
</nav>
<main>
<?php
}

function layoutEnd(): void {
?>
</main>
<footer style="text-align:center;padding:2rem;color:#aaa;font-size:.8rem;">
  🐰 Lapin de Pâques – Hackathon BTS SIO – Lycée Fulbert
</footer>
</div>
</body>
</html>
<?php
}