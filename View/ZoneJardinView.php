<?php
require_once __DIR__ . '/layout.php';

class ZoneJardinView
{
    // ══════════════════════════════════════════════════════════
    //  ZONES
    // ══════════════════════════════════════════════════════════
    public function showZoneList(array $zones): void
    {
        layoutStart('Zones', 'zones');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
  <h1>🗺️ Zones de distribution</h1>
  <a href="index.php?page=zones_create" class="btn btn-primary">+ Nouvelle zone</a>
</div>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Opération réussie !</div>
<?php endif; ?>

<?php if (empty($zones)): ?>
  <div class="card" style="text-align:center;color:#aaa;padding:3rem;">Aucune zone enregistrée 🌍</div>
<?php else: ?>
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1.2rem;">
  <?php foreach ($zones as $z): ?>
    <div class="card" style="border-left:5px solid var(--accent);">
      <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.8rem;">
        <div>
          <h2 style="margin:0;">🗺️ <?= htmlspecialchars($z['Nom']) ?></h2>
          <?php if ($z['Adresse']): ?>
            <small style="color:#888;">📍 <?= htmlspecialchars($z['Adresse']) ?></small>
          <?php endif; ?>
        </div>
        <div style="display:flex;gap:.4rem;">
          <a href="index.php?page=zones_edit&id=<?= $z['Id_Zone'] ?>" class="btn btn-warning btn-sm">✏️</a>
          <a href="index.php?page=zones_delete&id=<?= $z['Id_Zone'] ?>"
             class="btn btn-danger btn-sm"
             onclick="return confirm('Supprimer cette zone ?')">🗑️</a>
        </div>
      </div>
      <?php if (!empty($z['jardins'])): ?>
        <div style="font-size:.82rem;color:#666;font-weight:700;margin-bottom:.4rem;">
          🌱 <?= count($z['jardins']) ?> jardin(s) :
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
        <?php foreach ($z['jardins'] as $j): ?>
          <span class="badge badge-green" style="font-size:.75rem;">
            <?= htmlspecialchars($j['Nom'] ?: $j['Adresse']) ?>
          </span>
        <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p style="color:#aaa;font-size:.82rem;">Aucun jardin dans cette zone.</p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php
        layoutEnd();
    }

    public function showZoneCreate(?string $error): void
    {
        layoutStart('Nouvelle zone', 'zones');
?>
<h1>🗺️ Nouvelle zone</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group">
      <label>Nom de la zone</label>
      <input type="text" name="nom" required placeholder="Ex: Zone Nord">
    </div>
    <div class="form-group">
      <label>Adresse / Description</label>
      <input type="text" name="adresse" placeholder="Ex: Quartier des Lilas">
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">✅ Créer</button>
      <a href="index.php?page=zones" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    public function showZoneEdit(array $zone, ?string $error): void
    {
        layoutStart('Modifier zone', 'zones');
?>
<h1>✏️ Modifier <?= htmlspecialchars($zone['Nom']) ?></h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group">
      <label>Nom</label>
      <input type="text" name="nom" value="<?= htmlspecialchars($zone['Nom']) ?>" required>
    </div>
    <div class="form-group">
      <label>Adresse / Description</label>
      <input type="text" name="adresse" value="<?= htmlspecialchars($zone['Adresse'] ?? '') ?>">
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
      <a href="index.php?page=zones" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    // ══════════════════════════════════════════════════════════
    //  JARDINS
    // ══════════════════════════════════════════════════════════
    public function showJardinList(array $jardins): void
    {
        layoutStart('Jardins', 'jardins');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
  <h1>🌱 Jardins</h1>
  <a href="index.php?page=jardins_create" class="btn btn-primary">+ Nouveau jardin</a>
</div>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Opération réussie !</div>
<?php endif; ?>
<div class="card table-wrap">
<table>
  <thead>
    <tr><th>#</th><th>Nom</th><th>Adresse</th><th>Ville</th><th>Zone</th><th>Actions</th></tr>
  </thead>
  <tbody>
  <?php foreach ($jardins as $j): ?>
  <tr>
    <td><?= $j['Id_Jardin'] ?></td>
    <td style="font-weight:700;">🌱 <?= htmlspecialchars($j['Nom'] ?: '—') ?></td>
    <td><?= htmlspecialchars($j['Adresse']) ?></td>
    <td><?= htmlspecialchars($j['Ville'] ?? '—') ?></td>
    <td><span class="badge badge-purple">🗺️ <?= htmlspecialchars($j['NomZone']) ?></span></td>
    <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
      <a href="index.php?page=jardins_edit&id=<?= $j['Id_Jardin'] ?>" class="btn btn-warning btn-sm">✏️</a>
      <a href="index.php?page=jardins_delete&id=<?= $j['Id_Jardin'] ?>"
         class="btn btn-danger btn-sm"
         onclick="return confirm('Supprimer ce jardin ?')">🗑️</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($jardins)): ?>
    <tr><td colspan="6" style="text-align:center;color:#aaa;padding:2rem;">Aucun jardin enregistré 🌱</td></tr>
  <?php endif; ?>
  </tbody>
</table>
</div>
<?php
        layoutEnd();
    }

    public function showJardinCreate(?string $error, array $zones): void
    {
        layoutStart('Nouveau jardin', 'jardins');
?>
<h1>🌱 Nouveau jardin</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group">
      <label>Nom du jardin <small style="color:#aaa;">(optionnel)</small></label>
      <input type="text" name="nom" placeholder="Ex: Jardin des Roses">
    </div>
    <div class="form-group">
      <label>Adresse <span style="color:var(--accent);">*</span></label>
      <input type="text" name="adresse" required placeholder="Ex: 12 rue des Tulipes">
    </div>
    <div class="form-group">
      <label>Ville</label>
      <input type="text" name="ville" placeholder="Ex: Chartres">
    </div>
    <div class="form-group">
      <label>Zone <span style="color:var(--accent);">*</span></label>
      <select name="id_zone" required>
        <option value="">— Choisir une zone —</option>
        <?php foreach ($zones as $z): ?>
          <option value="<?= $z['Id_Zone'] ?>"><?= htmlspecialchars($z['Nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">✅ Créer</button>
      <a href="index.php?page=jardins" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    public function showJardinEdit(array $jardin, ?string $error, array $zones): void
    {
        layoutStart('Modifier jardin', 'jardins');
?>
<h1>✏️ Modifier jardin</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:500px;">
  <form method="POST">
    <div class="form-group">
      <label>Nom</label>
      <input type="text" name="nom" value="<?= htmlspecialchars($jardin['Nom'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Adresse <span style="color:var(--accent);">*</span></label>
      <input type="text" name="adresse" value="<?= htmlspecialchars($jardin['Adresse']) ?>" required>
    </div>
    <div class="form-group">
      <label>Ville</label>
      <input type="text" name="ville" value="<?= htmlspecialchars($jardin['Ville'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Zone <span style="color:var(--accent);">*</span></label>
      <select name="id_zone" required>
        <?php foreach ($zones as $z): ?>
          <option value="<?= $z['Id_Zone'] ?>"
            <?= $z['Id_Zone'] == $jardin['Id_Zone'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($z['Nom']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
      <a href="index.php?page=jardins" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }
}