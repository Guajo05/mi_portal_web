<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $countryName = htmlspecialchars($_POST['country']);
    $response = file_get_contents("https://restcountries.com/v3.1/name/" . strtolower($countryName));
    $countryData = json_decode($response);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de un País</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Datos de un País</h1>
        <form method="POST" action="country.php">
            <input type="text" name="country" placeholder="Ingresa un país" required>
            <input type="submit" value="Consultar Datos" class="btn btn-primary">
        </form>
        <?php if (isset($countryData)): ?>
            <h2>Información sobre <?= $countryData[0]->name->common ?>:</h2>
            <img src="<?= $countryData[0]->flags->svg ?>" alt="Bandera de <?= $countryData[0]->name->common ?>" class="img-fluid">
            <p>Capital: <?= $countryData[0]->capital[0] ?></p>
            <p>Población: <?= number_format($countryData[0]->population) ?></p>
            <p>Moneda: <?= $countryData[0]->currencies[array_key_first($countryData[0]->currencies)]->name ?></p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
