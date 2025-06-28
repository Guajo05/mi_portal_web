<?php
$response = file_get_contents('https://official-joke-api.appspot.com/random_joke');
$joke = json_decode($response);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Chistes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Generador de Chistes</h1>
        <h2><?= $joke->setup ?></h2>
        <h3><?= $joke->punchline ?></h3>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
