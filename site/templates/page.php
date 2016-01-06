<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>
<?= $escape->html($document->title) ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/home.css">
</head>
<body>
    <h1>
<?= $escape->html($document->title) ?>
</h1>
    
<?php if ($document->description): ?>
<p>
<?= $document->description ?>
</p>
<?php endif; ?>

</body>
</html>
