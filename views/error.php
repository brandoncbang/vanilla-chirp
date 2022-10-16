<?php

/** @var \App\Support\HttpException $e */

?>

<?php include __DIR__ . '/_head.php' ?>

<h1><?= e($e->getCode()) ?> | <?= e($e->getMessage()) ?></h1>

<?php include __DIR__ . '/_tail.php' ?>