<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city = htmlspecialchars($_POST['city']);
    $apiKey = 'TU_API_KEY'; // Reemplaza con tu API Key de OpenWeather
    $response = file_get_contents("https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $apiKey . "&units=metric");
    $weather = json_decode($response);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clima</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Clima en República Dominicana</h1>
        <form method="POST" action="weather.php">
            <input type="text" name="city" placeholder="Ingresa una ciudad" required>
            <input type="submit" value="Consultar Clima" class="btn btn-primary">
        </form>
        <?php if (isset($weather)): ?>
            <h2>Clima en <?= $weather->name ?>:</h2>
            <p>Temperatura: <?= $weather->main->temp ?> °C</p>
            <p>Condiciones: <?= $weather->weather[0]->description ?></p>
            <img src="http://openweathermap.org/img/wn/<?= $weather->weather[0]->icon ?>.png" alt="Clima">
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
