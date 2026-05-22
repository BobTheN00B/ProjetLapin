<?php
require_once __DIR__ . '/layout.php';

class CollecteView
{
    private function statutBadge(string $s): string
    {
        return match($s) {
            'réalisée'  => '<span class="badge badge-green">✅ réalisée</span>',
            'en cours'  => '<span class="badge badge-yellow">🔄 en cours</span>',
            'planifiée' => '<span class="badge badge-blue">📅 planifiée</span>',
            default     => '<span class="badge badge-blue">' . htmlspecialchars($s) . '</span>',
        };
    }

    public function showList(array $collectes): void
    {
        layoutStart('Collectes', 'collectes');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
  <h1>🚚 Collectes</h1>
  <a href="index.php?page=collectes_create" class="btn btn-primary">+ Nouvelle collecte</a>
</div>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Opération réussie !</div>
<?php endif; ?>
<div class="card table-wrap">
<table>
  <thead>
    <tr>
      <th>#</th><th>Date prévue</th><th>Date réelle</th>
      <th>Magasin</th><th>Statut</th><th>Lapins</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($collectes as $c): ?>
  <tr>
    <td><?= $c['Id_Collecte'] ?></td>
    <td><?= htmlspecialchars($c['datePrevisionnelle']) ?></td>
    <td><?= $c['dateCollecte'] ? htmlspecialchars($c['dateCollecte']) : '<span style="color:#aaa;">—</span>' ?></td>
    <td>🍫 <?= htmlspecialchars($c['NomMagasin']) ?></td>
    <td><?= $this->statutBadge($c['Statut'] ?? 'planifiée') ?></td>
    <td>
      <?php if (!empty($c['lapins'])): ?>
        <?php foreach ($c['lapins'] as $l): ?>
          <span class="badge badge-purple" style="font-size:.72rem;">🐰 <?= htmlspecialchars($l['Nom']) ?></span>
        <?php endforeach; ?>
      <?php else: ?>
        <span style="color:#aaa;font-size:.8rem;">Aucun</span>
      <?php endif; ?>
    </td>
    <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
      <?php if (($c['Statut'] ?? '') !== 'réalisée'): ?>
      <a href="index.php?page=collectes_done&id=<?= $c['Id_Collecte'] ?>"
         class="btn btn-success btn-sm"
         title="Marquer réalisée"
         onclick="return confirm('Marquer cette collecte comme réalisée ?')">✅</a>
      <?php endif; ?>
      <a href="index.php?page=collectes_edit&id=<?= $c['Id_Collecte'] ?>" class="btn btn-warning btn-sm">✏️</a>
      <a href="index.php?page=collectes_delete&id=<?= $c['Id_Collecte'] ?>"
         class="btn btn-danger btn-sm"
         onclick="return confirm('Supprimer cette collecte ?')">🗑️</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($collectes)): ?>
    <tr><td colspan="7" style="text-align:center;color:#aaa;padding:2rem;">Aucune collecte planifiée 🚚</td></tr>
  <?php endif; ?>
  </tbody>
</table>
</div>
<?php
        layoutEnd();
    }

    private function lapinsCheckboxes(array $lapins, array $selected = []): void
    {
?>
    <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.5rem;">
    <?php foreach ($lapins as $l): ?>
      <label style="display:flex;align-items:center;gap:.35rem;background:#f0e8ec;padding:.3rem .75rem;border-radius:999px;cursor:pointer;font-weight:700;font-size:.85rem;">
        <input type="checkbox" name="lapins[]" value="<?= $l['Id_Lapins'] ?>"
          <?= in_array($l['Id_Lapins'], $selected) ? 'checked' : '' ?>>
        🐰 <?= htmlspecialchars($l['Nom']) ?>
      </label>
    <?php endforeach; ?>
    </div>
<?php
    }

    public function showCreate(?string $error, array $magasins, array $lapins): void
    {
        layoutStart('Nouvelle collecte', 'collectes');
?>
<h1>🚚 Nouvelle collecte</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:560px;">
  <form method="POST">
    <div class="form-group">
      <label>Date prévisionnelle <span style="color:var(--accent);">*</span></label>
      <input type="date" name="date_prev" required value="<?= date('Y-m-d') ?>">
    </div>
    <div class="form-group">
      <label>Magasin donateur <span style="color:var(--accent);">*</span></label>
      <select name="id_magasins" required>
        <option value="">— Choisir un magasin —</option>
        <?php foreach ($magasins as $m): ?>
          <option value="<?= $m['Id_Magasins'] ?>"><?= htmlspecialchars($m['Nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Lapins affectés</label>
      <?php $this->lapinsCheckboxes($lapins); ?>
    </div>
    <div style="display:flex;gap:.75rem;margin-top:1rem;">
      <button type="submit" class="btn btn-primary">✅ Créer</button>
      <a href="index.php?page=collectes" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    public function showEdit(array $collecte, ?string $error, array $magasins, array $lapins, array $lapinsAffectes): void
    {
        layoutStart('Modifier collecte', 'collectes');
?>
<h1>✏️ Modifier collecte #<?= $collecte['Id_Collecte'] ?></h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:560px;">
  <form method="POST">
    <div class="form-group">
      <label>Date prévisionnelle <span style="color:var(--accent);">*</span></label>
      <input type="date" name="date_prev" required value="<?= htmlspecialchars($collecte['datePrevisionnelle']) ?>">
    </div>
    <div class="form-group">
      <label>Date réelle</label>
      <input type="date" name="date_collecte" value="<?= htmlspecialchars($collecte['dateCollecte'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Statut</label>
      <select name="statut">
        <?php foreach (['planifiée','en cours','réalisée'] as $s): ?>
          <option value="<?= $s ?>" <?= ($collecte['Statut'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Magasin <span style="color:var(--accent);">*</span></label>
      <select name="id_magasins" required>
        <?php foreach ($magasins as $m): ?>
          <option value="<?= $m['Id_Magasins'] ?>"
            <?= $m['Id_Magasins'] == $collecte['Id_Magasins'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($m['Nom']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Lapins affectés</label>
      <?php $this->lapinsCheckboxes($lapins, $lapinsAffectes); ?>
    </div>
    <div style="display:flex;gap:.75rem;margin-top:1rem;">
      <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
      <a href="index.php?page=collectes" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }
}