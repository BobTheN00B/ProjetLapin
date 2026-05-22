<?php
require_once __DIR__ . '/layout.php';

class UserView
{

    public function showRegister(?string $error): void
    {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Créer un compte – 🐰 Lapin de Pâques</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <div class="User">
    <div class="login-box">
    <span class="emoji">🥕</span>
    <h1>Bienvenue !</h1>
    <p class="sub">Créez votre premier compte administrateur</p>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Nom de votre lapin</label>
        <input type="text" name="nom" required placeholder="Bugs Bunny">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required placeholder="lapin@paques.fr">
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn-login">🐾 Créer mon compte</button>
    </form>
    <p style="text-align: center; margin-top: 15px;">
        <a href="index.php?page=login" style="color: #FF6B8A; text-decoration: none; font-weight: bold; font-family: 'Nunito', sans-serif;">Déjà un compte ? Se connecter</a>
    </p>
    </div>
  </div>
</body>
</html>
<?php
    }
    
    public function showLogin(?string $error): void
    {
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion – 🐰 Lapin de Pâques</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;800&display=swap" rel="stylesheet">
</head>
<body>
  <div class="User">
    <div class="login-box">
    <span class="emoji">🐰</span>
    <h1>Lapin de Pâques</h1>
    <p class="sub">Accès réservé aux lapins autorisés 🥕</p>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required placeholder="lapin@paques.fr">
      </div>
      <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn-login">🐾 Se connecter</button>
    </form>
    </div>
</body>
</html>
<?php
    }

    public function showDashboard(array $stats, array $parZone, array $lapins, int $nbMagasins, int $nbJardins): void
    {
        layoutStart('Tableau de bord', 'dashboard');
        $bgColors = ['#FFB3C6','#FFF3B0','#B5EAD7','#C7CEEA','#E2B4FF'];
?>
<h1><span class="bunny">🐰</span> Tableau de bord</h1>

<div class="stats-grid">
  <div class="stat-card" style="background:#FFE5EC;">
    <span class="stat-num" style="color:#FF6B8A;"><?= (int)($stats['total'] ?? 0) ?></span>
    <span class="stat-lbl">Livraisons totales</span>
  </div>
  <div class="stat-card" style="background:#D5F5E3;">
    <span class="stat-num" style="color:#2ecc71;"><?= (int)($stats['livrees'] ?? 0) ?></span>
    <span class="stat-lbl">✅ Livrées</span>
  </div>
  <div class="stat-card" style="background:#FFF9C4;">
    <span class="stat-num" style="color:#f39c12;"><?= (int)($stats['planifiees'] ?? 0) ?></span>
    <span class="stat-lbl">📅 Planifiées</span>
  </div>
  <div class="stat-card" style="background:#E8F4FD;">
    <span class="stat-num" style="color:#3498db;"><?= count($lapins) ?></span>
    <span class="stat-lbl">🐰 Lapins</span>
  </div>
  <div class="stat-card" style="background:#F3E5F5;">
    <span class="stat-num" style="color:#9b59b6;"><?= $nbMagasins ?></span>
    <span class="stat-lbl">🍫 Magasins</span>
  </div>
  <div class="stat-card" style="background:#E8F5E9;">
    <span class="stat-num" style="color:#27ae60;"><?= $nbJardins ?></span>
    <span class="stat-lbl">🌱 Jardins</span>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;flex-wrap:wrap;">

<div class="card">
  <h2>🗺️ Livraisons par zone</h2>
  <?php if (empty($parZone)): ?>
    <p style="color:#aaa;">Aucune livraison encore.</p>
  <?php else: ?>
  <?php $max = max(array_column($parZone,'nb')) ?: 1; ?>
  <?php foreach ($parZone as $i => $z): ?>
    <div style="margin-bottom:.8rem;">
      <div style="display:flex;justify-content:space-between;font-weight:700;font-size:.9rem;margin-bottom:.3rem;">
        <span><?= htmlspecialchars($z['Zone']) ?></span>
        <span><?= $z['nb'] ?> 🐣</span>
      </div>
      <div style="background:#f0e8ec;border-radius:999px;height:10px;">
        <div style="background:<?= $bgColors[$i % count($bgColors)] ?>;width:<?= round($z['nb']/$max*100) ?>%;height:100%;border-radius:999px;transition:width .5s;"></div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>

<div class="card">
  <h2>🐰 Statut des lapins</h2>
  <div style="display:flex;flex-direction:column;gap:.6rem;">
  <?php foreach ($lapins as $l): ?>
    <?php
      $badge = match($l['Statut']) {
        'disponible'  => 'badge-green',
        'en mission'  => 'badge-yellow',
        'repos'       => 'badge-blue',
        default       => 'badge-blue',
      };
    ?>
    <div style="display:flex;align-items:center;justify-content:space-between;">
      <span style="font-weight:700;">🐰 <?= htmlspecialchars($l['Nom']) ?></span>
      <span class="badge <?= $badge ?>"><?= htmlspecialchars($l['Statut']) ?></span>
    </div>
  <?php endforeach; ?>
  </div>
</div>

</div>

<div class="card" style="margin-top:1rem;">
  <h2>⚡ Actions rapides</h2>
  <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
    <a href="index.php?page=livraisons_create" class="btn btn-primary">+ Nouvelle livraison</a>
    <a href="index.php?page=collectes_create" class="btn btn-success">+ Nouvelle collecte</a>
    <a href="index.php?page=lapins_create" class="btn btn-warning">+ Nouveau lapin</a>
    <a href="index.php?page=logs" class="btn btn-ghost">📋 Voir les logs</a>
  </div>
</div>
<?php
        layoutEnd();
    }

    public function showList(array $users): void
    {
        layoutStart('Utilisateurs', 'utilisateurs');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
  <h1>👤 Utilisateurs</h1>
  <a href="index.php?page=utilisateurs_create" class="btn btn-primary">+ Ajouter</a>
</div>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Opération réussie !</div>
<?php endif; ?>
<div class="card table-wrap">
<table>
  <thead><tr><th>#</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Créé le</th><th>Actions</th></tr></thead>
  <tbody>
  <?php foreach ($users as $u): ?>
  <tr>
    <td><?= $u['Id_Utilisateurs'] ?></td>
    <td><?= htmlspecialchars($u['Nom']) ?></td>
    <td><?= htmlspecialchars($u['Email']) ?></td>
    <td><span class="badge <?= $u['Role']==='admin'?'badge-red':'badge-blue' ?>"><?= $u['Role'] ?></span></td>
    <td>
      <a href="index.php?page=utilisateurs_delete&id=<?= $u['Id_Utilisateurs'] ?>"
         class="btn btn-danger btn-sm"
         onclick="return confirm('Supprimer cet utilisateur ?')">🗑️</a>
    </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php
        layoutEnd();
    }

    public function showCreate(?string $error): void
    {
        layoutStart('Nouvel utilisateur', 'utilisateurs');
?>
<h1>👤 Nouvel utilisateur</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group"><label>Nom</label><input type="text" name="nom" required></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
    <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required></div>
    <div class="form-group"><label>Rôle</label>
      <select name="role"><option value="lapin">Lapin</option><option value="admin">Admin</option></select>
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">✅ Créer</button>
      <a href="index.php?page=utilisateurs" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
</div>
<?php
        layoutEnd();
    }
}