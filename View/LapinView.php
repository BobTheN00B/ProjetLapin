<?php
require_once __DIR__ . '/layout.php';

class LapinView
{
    private function statutBadge(string $s): string
    {
        return match($s) {
            'disponible' => '<span class="badge badge-green">🟢 disponible</span>',
            'en mission' => '<span class="badge badge-yellow">🟡 en mission</span>',
            'repos'      => '<span class="badge badge-blue">🔵 repos</span>',
            default      => '<span class="badge badge-blue">' . htmlspecialchars($s) . '</span>',
        };
    }

    public function showList(array $lapins): void
    {
        layoutStart('Lapins', 'lapins');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
  <h1>🐰 Les lapins</h1>
  <a href="index.php?page=lapins_create" class="btn btn-primary">+ Nouveau lapin</a>
</div>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Opération réussie !</div>
<?php endif; ?>
<div class="card table-wrap">
<table>
  <thead><tr><th>#</th><th>Nom</th><th>Couleur</th><th>Statut</th><th>Responsable</th><th>Actions</th></tr></thead>
  <tbody>
  <?php foreach ($lapins as $l): ?>
  <tr>
    <td><?= $l['Id_Lapins'] ?></td>
    <td style="font-weight:700;">🐰 <?= htmlspecialchars($l['Nom']) ?></td>
    <td><?= htmlspecialchars($l['Couleur'] ?? '–') ?></td>
    <td><?= $this->statutBadge($l['Statut']) ?></td>
    <td><?= htmlspecialchars($l['NomUtilisateur']) ?></td>
    <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
      <a href="index.php?page=lapins_edit&id=<?= $l['Id_Lapins'] ?>" class="btn btn-warning btn-sm">✏️</a>
      <a href="index.php?page=lapins_delete&id=<?= $l['Id_Lapins'] ?>"
         class="btn btn-danger btn-sm"
         onclick="return confirm('Supprimer ce lapin ?')">🗑️</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($lapins)): ?>
    <tr><td colspan="6" style="text-align:center;color:#aaa;padding:2rem;">Aucun lapin enregistré 😢</td></tr>
  <?php endif; ?>
  </tbody>
</table>
</div>
<?php
        layoutEnd();
    }

    public function showCreate(?string $error, array $users): void
    {
        layoutStart('Nouveau lapin', 'lapins');
?>
<h1>🐰 Nouveau lapin</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group"><label>Nom du lapin</label><input type="text" name="nom" required placeholder="Ex: Flopsy"></div>
    <div class="form-group"><label>Couleur</label><input type="text" name="couleur" placeholder="Gris, Blanc, Roux..."></div>
    <div class="form-group"><label>Statut</label>
      <select name="statut">
        <option value="disponible">🟢 Disponible</option>
        <option value="en mission">🟡 En mission</option>
        <option value="repos">🔵 Au repos</option>
      </select>
    </div>
    <div class="form-group"><label>Responsable</label>
      <select name="id_utilisateur">
        <?php foreach ($users as $u): ?>
          <option value="<?= $u['Id_Utilisateurs'] ?>"><?= htmlspecialchars($u['Nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">✅ Créer</button>
      <a href="index.php?page=lapins" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    public function showEdit(array $lapin, ?string $error): void
    {
        layoutStart('Modifier lapin', 'lapins');
?>
<h1>✏️ Modifier <?= htmlspecialchars($lapin['Nom']) ?></h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group"><label>Nom</label>
      <input type="text" name="nom" value="<?= htmlspecialchars($lapin['Nom']) ?>" required>
    </div>
    <div class="form-group"><label>Couleur</label>
      <input type="text" name="couleur" value="<?= htmlspecialchars($lapin['Couleur'] ?? '') ?>">
    </div>
    <div class="form-group"><label>Statut</label>
      <select name="statut">
        <?php foreach (['disponible','en mission','repos'] as $s): ?>
          <option value="<?= $s ?>" <?= $lapin['Statut']===$s?'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
      <a href="index.php?page=lapins" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }
}