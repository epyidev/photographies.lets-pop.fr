<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pierre Lihoreau - Galerie Photographe</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="blocktitle">
    <div class="title">Pierre Lihoreau</div>
    <div class="subtitle">Galerie de photographies</div>
  </div>
  <div class="block">
    <div class="indexgallery">
      <?php
      $upload_dir = __DIR__ . '/uploads';
      $dirs = array_filter(glob($upload_dir . '/*'), 'is_dir');

      foreach ($dirs as $dir) {
        $settings_path = $dir . '/settings.json';
        $dirname = basename($dir);
        if (!file_exists($settings_path)) continue;
        $settings = json_decode(file_get_contents($settings_path), true);
        $display_name = htmlspecialchars($settings['display_name'] ?? $dirname);
        echo "<div><a href='album.php?dir=$dirname' class='mbutton small'>$display_name</a></div>";
      }
      ?>
    </div>
  </div>
</body>
</html>