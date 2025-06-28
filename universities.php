<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $country = htmlspecialchars($_POST['country']);
    $response = file_get_contents("http://universities.hipolabs.com/search?country=" . urlencode($country));
    $universities = json_decode($response);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universidades</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Universidades de un País</h1>
        <form method="POST" action="universities.php">
            <input type="text" name="country" placeholder="Ingresa un país" required>
            <input type="submit" value="Buscar Universidades" class="btn btn-primary">
        </form>
        <?php if (isset($universities)): ?>
            <h2>Universidades en <?= $country ?>:</h2>
            <ul class="list-group">
                <?php foreach ($universities as $university): ?>
                    <li class="list-group-item">
                        <strong><?= $university->name ?></strong><br>
                        Dominio: <?= $university->domain ?><br>
                        <a href="<?= $university->web_pages[0] ?>" target="_blank">Visitar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
