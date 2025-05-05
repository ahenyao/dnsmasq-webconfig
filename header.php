<?php require_once 'config.php'; ?>
<?php if(!isset($title) || $title=="") {$title="Dnsmasq config page on ".$_SERVER['SERVER_NAME']; } ?>

<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Basic Page Needs
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta charset="utf-8">
    <title><?= $title; ?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Mobile Specific Metas
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FONT
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <!--<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">-->

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/skeleton.css">
    <link rel="stylesheet" href="css/overlay.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Scripts
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <script src="js/theme.js" defer="defer"></script>
    <script src="js/dnsRecords.js" defer="defer"></script>
    <script src="js/editor.js" defer="defer"></script>

    <!-- Favicon
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="icon" type="image/png" href="images/favicon.png">

</head>
<body>

<!-- Primary Page Layout
–––––––––––––––––––––––––––––––––––––––––––––––––– -->

<div class="header">
<?php

foreach (getAllConfigFiles() as $confFile) {

}
?>
</div>