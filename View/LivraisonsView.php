<?php
require_once __DIR__ . '/layout.php';

class LivraisonsView
{
    private function statutBadge(string $s): string
    {
        return match($s) {
            'livrée'    => '<span class="badge badge-green">✅ livrée</span>',
            'en cours'  => '<span class="badge badge-yellow">🔄 en cours</span>',
            'planifiée' => '<span class="badge badge-blue">📅 planifiée</span>',
            default     => '<span class="badge badge-blue">' . htmlspecialchars($s) . '</span>',
        };
    }

    // ══════════════════════════════════════════════════════════
    //  LISTE
    // ══════════════════════════════════════════════════════════
    public function showList(array $livraisons, array $stats): void
    {
        layoutStart('Livraisons', 'livraisons');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
  <h1>📦 Livraisons</h1>
  <a href="index.php?page=livraisons_create" class="btn btn-primary">+ Nouvelle livraison</a>
</div>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">✅ Opération réussie !</div>
<?php endif; ?>

<!-- Mini stats -->
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.5rem;">
  <div class="stat-card" style="background:#FFE5EC;">
    <span class="stat-num" style="color:#FF6B8A;"><?= (int)($stats['total']     ?? 0) ?></span>
    <span class="stat-lbl">Total</span>
  </div>
  <div class="stat-card" style="background:#D5F5E3;">
    <span class="stat-num" style="color:#27ae60;"><?= (int)($stats['livrees']    ?? 0) ?></span>
    <span class="stat-lbl">✅ Livrées</span>
  </div>
  <div class="stat-card" style="background:#FFF9C4;">
    <span class="stat-num" style="color:#f39c12;"><?= (int)($stats['planifiees'] ?? 0) ?></span>
    <span class="stat-lbl">📅 Planifiées</span>
  </div>
  <div class="stat-card" style="background:#E8F4FD;">
    <span class="stat-num" style="color:#3498db;"><?= (int)($stats['en_cours']  ?? 0) ?></span>
    <span class="stat-lbl">🔄 En cours</span>
  </div>
</div>

<div class="card table-wrap">
<table>
  <thead>
    <tr>
      <th>#</th><th>Date prévue</th><th>Date réelle</th>
      <th>Lapin</th><th>Jardin</th><th>Zone</th><th>Statut</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($livraisons as $lv): ?>
  <tr>
    <td><?= $lv['Id_Livraison'] ?></td>
    <td><?= htmlspecialchars($lv['datePrevisionnelle']) ?></td>
    <td><?= $lv['dateLivraison'] ? htmlspecialchars($lv['dateLivraison']) : '<span style="color:#aaa;">—</span>' ?></td>
    <td>🐰 <?= htmlspecialchars($lv['NomLapin']) ?></td>
    <td>
      🌱 <?= htmlspecialchars($lv['NomJardin'] ?: $lv['AdresseJardin']) ?>
      <?php if ($lv['VilleJardin']): ?>
        <small style="color:#888;">(<?= htmlspecialchars($lv['VilleJardin']) ?>)</small>
      <?php endif; ?>
    </td>
    <td><span class="badge badge-purple">🗺️ <?= htmlspecialchars($lv['NomZone']) ?></span></td>
    <td><?= $this->statutBadge($lv['Statut'] ?? 'planifiée') ?></td>
    <td style="display:flex;gap:.4rem;flex-wrap:wrap;">
      <?php if (($lv['Statut'] ?? '') !== 'livrée'): ?>
      <a href="index.php?page=livraisons_done&id=<?= $lv['Id_Livraison'] ?>"
         class="btn btn-success btn-sm"
         title="Marquer livrée"
         onclick="return confirm('Confirmer la livraison ?')">✅</a>
      <?php endif; ?>
      <a href="index.php?page=livraisons_edit&id=<?= $lv['Id_Livraison'] ?>" class="btn btn-warning btn-sm">✏️</a>
      <a href="index.php?page=livraisons_delete&id=<?= $lv['Id_Livraison'] ?>"
         class="btn btn-danger btn-sm"
         onclick="return confirm('Supprimer cette livraison ?')">🗑️</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($livraisons)): ?>
    <tr><td colspan="8" style="text-align:center;color:#aaa;padding:2rem;">Aucune livraison enregistrée 📦</td></tr>
  <?php endif; ?>
  </tbody>
</table>
</div>
<?php
        layoutEnd();
    }

    // ══════════════════════════════════════════════════════════
    //  CRÉER
    // ══════════════════════════════════════════════════════════
    public function showCreate(?string $error, array $lapins, array $zones): void
    {
        layoutStart('Nouvelle livraison', 'livraisons');
?>
<h1>📦 Nouvelle livraison</h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:520px;">
  <form method="POST">
    <div class="form-group">
      <label>Date prévisionnelle <span style="color:var(--accent);">*</span></label>
      <input type="date" name="date_prev" required value="<?= date('Y-m-d') ?>">
    </div>
    <div class="form-group">
      <label>Lapin chargé de la livraison <span style="color:var(--accent);">*</span></label>
      <select name="id_lapin" required>
        <option value="">— Choisir un lapin —</option>
        <?php foreach ($lapins as $l): ?>
          <option value="<?= $l['Id_Lapins'] ?>">🐰 <?= htmlspecialchars($l['Nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
    <label for="id_zone">Zone destinataire *</label>
    <select name="id_zone" id="id_zone" required>
        <option value="">— Choisir une zone —</option>
        <?php foreach ($zones as $zone): ?>
            <option value="<?= $zone['Id_Zone'] ?>"><?= htmlspecialchars($zone['Nom']) ?></option>
        <?php endforeach?>
    </select>
</div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">✅ Créer</button>
      <a href="index.php?page=livraisons" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    // ══════════════════════════════════════════════════════════
    //  ÉDITER
    // ══════════════════════════════════════════════════════════
    public function showEdit(array $liv, ?string $error, array $lapins, array $jardins): void
    {
        layoutStart('Modifier livraison', 'livraisons');
?>
<h1>✏️ Modifier livraison #<?= $liv['Id_Livraison'] ?></h1>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="card" style="max-width:520px;">
  <form method="POST">
    <div class="form-group">
      <label>Date prévisionnelle <span style="color:var(--accent);">*</span></label>
      <input type="date" name="date_prev" required value="<?= htmlspecialchars($liv['datePrevisionnelle']) ?>">
    </div>
    <div class="form-group">
      <label>Date de livraison réelle</label>
      <input type="date" name="date_livraison" value="<?= htmlspecialchars($liv['dateLivraison'] ?? '') ?>">
    </div>
    <div class="form-group">
      <label>Statut</label>
      <select name="statut">
        <?php foreach (['planifiée','en cours','livrée'] as $s): ?>
          <option value="<?= $s ?>" <?= ($liv['Statut'] ?? '') === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Lapin <span style="color:var(--accent);">*</span></label>
      <select name="id_lapin" required>
        <?php foreach ($lapins as $l): ?>
          <option value="<?= $l['Id_Lapins'] ?>"
            <?= $l['Id_Lapins'] == $liv['Id_Lapins'] ? 'selected' : '' ?>>
            🐰 <?= htmlspecialchars($l['Nom']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-group">
      <label>Jardin <span style="color:var(--accent);">*</span></label>
      <select name="id_jardin" required>
        <?php foreach ($jardins as $j): ?>
          <option value="<?= $j['Id_Jardin'] ?>"
            <?= $j['Id_Jardin'] == $liv['Id_Jardin'] ? 'selected' : '' ?>>
            🌱 <?= htmlspecialchars($j['Nom'] ?: $j['Adresse']) ?>
            (<?= htmlspecialchars($j['NomZone']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="display:flex;gap:.75rem;">
      <button type="submit" class="btn btn-primary">💾 Sauvegarder</button>
      <a href="index.php?page=livraisons" class="btn btn-ghost">Annuler</a>
    </div>
  </form>
</div>
<?php
        layoutEnd();
    }

    // ══════════════════════════════════════════════════════════
    //  LOGS
    // ══════════════════════════════════════════════════════════
    public function showLogs(array $logs, array $parZone): void
    {
        layoutStart('Logs de livraisons', 'logs');
?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem;">
  <h1>📋 Journal des livraisons</h1>
  <a href="index.php?page=logs&export=csv" class="btn btn-success">⬇️ Exporter CSV</a>
</div>

<?php if (!empty($parZone)): ?>
<div class="card" style="margin-bottom:1.5rem;">
  <h2>📊 Livraisons réalisées par zone</h2>
  <?php $max = max(array_column($parZone,'nb')) ?: 1; $colors=['#FFB3C6','#B5EAD7','#C7CEEA','#FFF3B0','#E2B4FF']; ?>
  <?php foreach ($parZone as $i => $z): ?>
    <div style="margin-bottom:.8rem;">
      <div style="display:flex;justify-content:space-between;font-weight:700;font-size:.9rem;margin-bottom:.25rem;">
        <span>🗺️ <?= htmlspecialchars($z['Zone']) ?></span>
        <span><?= $z['nb'] ?> livraison(s)</span>
      </div>
      <div style="background:#f0e8ec;border-radius:999px;height:12px;">
        <div style="background:<?= $colors[$i % count($colors)] ?>;width:<?= round($z['nb']/$max*100) ?>%;height:100%;border-radius:999px;"></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="card table-wrap">
  <h2 style="margin-bottom:1rem;">📄 Historique (<?= count($logs) ?> entrées)</h2>
  <?php if (empty($logs)): ?>
    <p style="text-align:center;color:#aaa;padding:2rem;">Aucune livraison enregistrée dans les logs.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr><th>#</th><th>Date / Heure</th><th>Lapin</th><th>Jardin</th><th>Adresse</th><th>ID Livraison</th></tr>
    </thead>
    <tbody>
    <?php foreach ($logs as $log): ?>
    <tr>
      <td><?= $log['Id_Log'] ?></td>
      <td>
        <strong><?= date('d/m/Y', strtotime($log['Date_Heure'])) ?></strong>
        <small style="color:#888;"> à <?= date('H:i:s', strtotime($log['Date_Heure'])) ?></small>
      </td>
      <td>🐰 <?= htmlspecialchars($log['Nom_Lapin'] ?? '—') ?></td>
      <td>🌱 <?= htmlspecialchars($log['Nom_Jardin'] ?? '—') ?></td>
      <td>📍 <?= htmlspecialchars($log['Adresse_Jardin'] ?? '—') ?></td>
      <td><span class="badge badge-blue">#<?= $log['Id_Livraison'] ?? '—' ?></span></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php
        // Export CSV inline
        if (isset($_GET['export']) && $_GET['export'] === 'csv' && !empty($logs)) {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="logs_livraisons_' . date('Ymd_His') . '.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID Log','Date','Heure','Lapin','Jardin','Adresse','ID Livraison'], ';');
            foreach ($logs as $log) {
                fputcsv($out, [
                    $log['Id_Log'],
                    date('d/m/Y', strtotime($log['Date_Heure'])),
                    date('H:i:s', strtotime($log['Date_Heure'])),
                    $log['Nom_Lapin']     ?? '',
                    $log['Nom_Jardin']    ?? '',
                    $log['Adresse_Jardin']?? '',
                    $log['Id_Livraison']  ?? '',
                ], ';');
            }
            fclose($out);
            exit;
        }

        layoutEnd();
    }
}