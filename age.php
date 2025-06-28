<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $response = file_get_contents("https://api.agify.io/?name=" . $name);
    $data = json_decode($response);
    $age = $data->age ?? 'desconocida';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Edad</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Predicción de Edad</h1>
        <form method="POST" action="age.php">
            <input type="text" name="name" placeholder="Ingresa un nombre" required>
            <input type="submit" value="Predecir Edad" class="btn btn-primary">
        </form>
        <?php if (isset($age)): ?>
            <h2>La edad estimada es: <?= $age ?> años</h2>
            <img src="<?= $age < 18 ? 'assets/baby.png' : ($age < 65 ? 'assets/adult.png' : 'assets/senior.png') ?>" alt="Edad" class="img-fluid">
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
