<?php
// includes/head.php — call with $pageTitle, $pageDesc already set
$pageTitle = $pageTitle ?? 'Globwocs Co. Ltd';
$pageDesc  = $pageDesc  ?? 'Globwocs Co. Ltd — Architecture, Engineering, Interior Design & Project Management. Creating coherent, functional spaces globally.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>">
  <title><?= htmlspecialchars($pageTitle) ?> — Globwocs Co. Ltd</title>
  <link rel="icon" href="assets/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<div id="page-transition"></div>
<div id="cursor"></div>
<div id="cursor-ring"></div>
