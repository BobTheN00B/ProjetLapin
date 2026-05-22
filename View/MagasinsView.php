<?php
require_once __DIR__ . '/layout.php';

class MagasinsView
{
    public function showList(array $magasins): void
    {
        layoutStart('Magasins', 'magasins');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
  <h1>🍫 Magasins donateurs</h1>
  <a href="index.php?page=magasins_create" class="btn btn-primary">+ Nouveau magasin</a>
</div>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Opération réussie !</div>
<?php endif; ?>
<div class="card table-wrap">
<table>
  <thead>
    <tr><th>#</th><th>Nom</th><th>Adresse</th><th>Actions</th></tr>
  </thead>
  <tbody>
  <?php foreach ($magasins as $m): ?>
  <tr>
    <td><?= $m['Id_Magasins'] ?></td>
    <td style="font-weight:700;">🍫 <?= htmlspecialchars($m['Nom']) ?></td>
    <td><?= htmlspecialchars($m['Adresse'] ?? '—') ?></td>
    <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
      <a href="index.php?page=magasins_edit&id=<?= $m['Id_Magasins'] ?>" class="btn btn-warning btn-sm">✏️</a>
      <a href="index.php?page=magasins_delete&id=<?= $m['Id_Magasins'] ?>"
         class="btn btn-danger btn-sm"
         onclick="return confirm('Supprimer ce magasin ?')">🗑️</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($magasins)): ?>
    <tr><td colspan="4" style="text-align:center;color:#aaa;padding:2rem;">Aucun magasin enregistré 🍫</td></tr>
  <?php endif; ?>
  </tbody>
</table>
</div>
<?php
        layoutEnd();
    }

    public function showCreate(?string $error): void
    {
        layoutStart('Nouveau magasin', 'magasins');
?>
<h1>🍫 Nouveau magasin</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group">
      <label>Nom du magasin <span style="color:var(--accent);">*</span></label>
      <input type="text" name="nom" required placeholder="Ex: Chocolaterie Ménier">
    </div>
    <div class="form-group">
      <label>Adresse</label>
      <input type="text" name="adresse" placeholder="Ex: 5 rue du Cacao, Chartres">
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">✅ Créer</button>
      <a href="index.php?page=magasins" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    public function showEdit(array $mag, ?string $error): void
    {
        layoutStart('Modifier magasin', 'magasins');
?>
<h1>✏️ Modifier <?= htmlspecialchars($mag['Nom']) ?></h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group">
      <label>Nom <span style="color:var(--accent);">*</span></label>
      <input type="text" name="nom" value="<?= htmlspecialchars($mag['Nom']) ?>" required>
    </div>
    <div class="form-group">
      <label>Adresse</label>
      <input type="text" name="adresse" value="<?= htmlspecialchars($mag['Adresse'] ?? '') ?>">
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
      <a href="index.php?page=magasins" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }
}