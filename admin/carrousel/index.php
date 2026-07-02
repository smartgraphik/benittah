<?php
require __DIR__.'/../auth.php';
require_admin();

$logosPath = __DIR__ . '/../../data/home_logos.json';
$assetDir = __DIR__ . '/../../assets/img/logos';

function carrousel_clean_logo_path($path) {
  $path = trim((string)$path);
  if ($path === '') { return ''; }
  if (preg_match('#^https?://#', $path)) { return $path; }
  $path = '/' . ltrim($path, '/');
  return clean_text($path, 255);
}

function carrousel_existing_logo_paths($assetDir) {
  $paths = array();
  if (!is_dir($assetDir)) { return $paths; }
  $allowed = array('png','jpg','jpeg','svg','webp','gif');
  foreach (scandir($assetDir) ?: array() as $file) {
    if ($file === '.' || $file === '..') { continue; }
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) { continue; }
    $paths[] = '/assets/img/logos/' . basename($file);
  }
  sort($paths);
  return $paths;
}

if (request_method_is_post()) {
  require_csrf();
  $items = array();
  foreach (($_POST['logos'] ?? array()) as $row) {
    if (!empty($row['delete'])) { continue; }
    $name = clean_text($row['name'] ?? '', 190);
    $logo = carrousel_clean_logo_path($row['logo'] ?? '');
    if ($name === '' && $logo === '') { continue; }
    if ($name === '' || $logo === '') { continue; }
    $items[] = array(
      'name' => $name,
      'logo' => $logo,
      'enabled' => !empty($row['enabled']),
    );
  }

  $newName = clean_text($_POST['new_name'] ?? '', 190);
  $newLogo = carrousel_clean_logo_path($_POST['new_logo'] ?? '');
  if ($newName !== '' && $newLogo !== '') {
    $items[] = array(
      'name' => $newName,
      'logo' => $newLogo,
      'enabled' => true,
    );
  }

  write_json('home_logos', $items);
  header('Location: /admin/carrousel/?saved=1');
  exit;
}

$logos = read_json('home_logos', array());
$existingLogoPaths = carrousel_existing_logo_paths($assetDir);

require __DIR__.'/../_layout.php';
admin_header('Admin — Carroussel','carrousel');
?>
<div class="admin-top">
  <div>
    <h1>Carroussel logos home</h1>
    <p>Modifier les logos affichés dans la section “Ils m’ont fait confiance”.</p>
  </div>
  <button form="carrouselForm" class="btn btn-primary" type="submit">Enregistrer</button>
</div>

<?php if(isset($_GET['saved'])): ?><div class="notice">Carroussel enregistré.</div><?php endif; ?>

<form id="carrouselForm" class="admin-panel form" method="post">
  <?= csrf_field() ?>
  <datalist id="logo-paths">
    <?php foreach($existingLogoPaths as $path): ?><option value="<?= e($path) ?>"></option><?php endforeach; ?>
  </datalist>

  <div class="admin-panel-head">
    <h2>Logos actifs</h2>
    <span class="admin-empty">Décoche “Actif” pour masquer temporairement, ou coche “Supprimer”.</span>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table carrousel-table">
      <thead>
        <tr>
          <th>Aperçu</th>
          <th>Nom</th>
          <th>Chemin du logo</th>
          <th>Actif</th>
          <th>Supprimer</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($logos as $i=>$logo): ?>
        <tr>
          <td>
            <?php if(!empty($logo['logo'])): ?>
              <span class="logo-preview"><img src="<?= e($logo['logo']) ?>" alt=""></span>
            <?php endif; ?>
          </td>
          <td><input name="logos[<?= (int)$i ?>][name]" value="<?= e($logo['name'] ?? '') ?>" placeholder="Nom du client"></td>
          <td><input list="logo-paths" name="logos[<?= (int)$i ?>][logo]" value="<?= e($logo['logo'] ?? '') ?>" placeholder="/assets/img/logos/logo.png"></td>
          <td><label class="inline-check"><input type="checkbox" name="logos[<?= (int)$i ?>][enabled]" value="1" <?= !empty($logo['enabled']) ? 'checked' : '' ?>> Actif</label></td>
          <td><label class="inline-check danger-check"><input type="checkbox" name="logos[<?= (int)$i ?>][delete]" value="1"> Supprimer</label></td>
        </tr>
      <?php endforeach; ?>
      <?php if(!$logos): ?><tr><td colspan="5">Aucun logo configuré.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="admin-panel carrousel-add-panel">
    <h2>Ajouter un logo</h2>
    <div class="admin-form-grid">
      <label>Nom du client<input name="new_name" placeholder="Exemple : Orange"></label>
      <label>Chemin du logo<input list="logo-paths" name="new_logo" placeholder="/assets/img/logos/orange.png"></label>
    </div>
  </div>
</form>
<?php admin_footer(); ?>
