<?php

$dir = $_GET['dir'] ?? '';
$path = __DIR__ . "/uploads/$dir";
$settings_path = "$path/settings.json";

if (!file_exists($settings_path)) {
  die("Prestations inconnues.");
}

$settings = json_decode(file_get_contents($settings_path), true);
$password_required = !empty($settings['password']);

// Ajout du header HTML pour appliquer le style
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($settings['display_name'] ?? $dir); ?></title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0; width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.85);
      justify-content: center;
      align-items: center;
      transition: opacity 0.2s;
    }
    .modal.open { display: flex; }
    .modal img {
      max-width: 90vw;
      max-height: 90vh;
      border-radius: 12px;
      box-shadow: 0 4px 32px rgba(0,0,0,0.4);
      background: #222;
    }
    .modal .close {
      position: absolute;
      top: 32px;
      right: 48px;
      color: #fff;
      font-size: 3rem;
      font-weight: bold;
      cursor: pointer;
      z-index: 1001;
      text-shadow: 0 2px 8px #000;
      background: none;
      border: none;
    }
    @media (max-width: 600px) {
      .modal .close { top: 12px; right: 18px; font-size: 2.2rem; }
    }
  </style>
</head>
<body>
<?php
if ($password_required) {
  session_start();
  if (!isset($_SESSION['access'][$dir]) || $_SESSION['access'][$dir] !== true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $pass = $_POST['password'] ?? '';
      if ($pass === $settings['password']) {
        $_SESSION['access'][$dir] = true;
      } else {
        $error = 'Mot de passe incorrect';
      }
    }
    if (!isset($_SESSION['access'][$dir])) {
			echo '<div class="blocktitle">
				<div class="title">Pierre Lihoreau</div>
				<div class="subtitle">Galerie de photographies</div>
			</div>';
			echo "<div class='block'>";
			echo "<div class='readzone'>";
      echo "<form class='passwordform' method='post'><h2>Entrez le mot de passe pour accéder aux photos</h2><p style='color:red;'>" . ($error ?? '') . "</p><input type='password' name='password' placeholder='Entrez le mot de passe' autocomplete='current-password'><button type='submit' class='mbutton small'>Entrer</button></form>";
			echo "</div>";
			echo "</div>";
			echo "</body></html>";
      exit;
    }
  }
}

$files = array_filter(array_merge(
    glob("$path/*.jpg"),
    glob("$path/*.jpeg"),
    glob("$path/*.png"),
    glob("$path/*.gif")
));
echo '<div class="blocktitle">
	<div class="title">Pierre Lihoreau</div>
	<div class="subtitle">Galerie de photographies</div>
	</div>';
	echo "<div class='block'>";
			echo "<div class='readzone'>";
$display_name = htmlspecialchars($settings['display_name'] ?? $dir);
echo "<div><div class='title'><h1>$display_name</h1></div><div class='gallery'>"; // <a class='mbutton small zip-link' href='zip.php?dir=$dir'>Télécharger tout (ZIP)</a>
foreach ($files as $file) {
  $filename = basename($file);
  $imgsrc = "uploads/$dir/$filename";
  echo "<div class='album'><img src='$imgsrc' alt='' onclick=\"openModal('$imgsrc')\" style='cursor:zoom-in;' /><a class='mbutton small' href='$imgsrc' download>Télécharger</a></div>";
}
echo "</div></div>";
// Modal pour affichage grand format
?>
<div id="imgModal" class="modal" onclick="closeModal(event)">
  <button class="close" onclick="closeModal(event)">&times;</button>
  <img id="modalImg" src="" alt="Agrandissement" />
</div>
<script>
function openModal(src) {
  document.getElementById('modalImg').src = src;
  document.getElementById('imgModal').classList.add('open');
}
function closeModal(e) {
  if (e.target.classList.contains('modal') || e.target.classList.contains('close')) {
    document.getElementById('imgModal').classList.remove('open');
    document.getElementById('modalImg').src = '';
  }
}
// Fermer avec ESC
window.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeModal({target: document.getElementById('imgModal')});
});
</script>
<?php
echo "</body></html>";
?>
