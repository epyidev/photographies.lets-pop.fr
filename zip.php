<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dir = $_GET['dir'] ?? '';
$path = __DIR__ . "/uploads/$dir";
$zipname = "$dir.zip";

$zip = new ZipArchive;
if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
  $files = array_merge(
		glob("$path/*.jpg"),
		glob("$path/*.jpeg"),
		glob("$path/*.png"),
		glob("$path/*.gif")
	);

	foreach ($files as $file) {
			$zip->addFile($file, basename($file));
	}
  $zip->close();
  header('Content-Type: application/zip');
  header("Content-Disposition: attachment; filename=$zipname");
  readfile($zipname);
  unlink($zipname);
  exit;
} else {
  die("Erreur lors de la création de l'archive.");
}
?>
