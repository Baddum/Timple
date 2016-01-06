<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Static Style Guide</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/home.css">
</head>
<body>
    
<?php if ($document->title): ?>
<h1><span>
<?= $escape->html($document->title) ?>
</span></h1>
<?php endif; ?>

    
<?php if ($document->description): ?>
<p>
<?= $document->description ?>
</p>
<?php endif; ?>

    
<?php if ($document->title): ?>
<h1><span>
<?= $escape->html($document->title) ?>
</span></h1>
<?php endif; ?>

</body>
</html>
