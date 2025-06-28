<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = htmlspecialchars($_POST['keyword']);
    $response = file_get_contents("https://api.unsplash.com/photos/random?query=" . urlencode($keyword) . "&client_id=TU_API_KEY"); // Reemplaza con tu API Key de Unsplash
    $image = json_decode($response);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Imágenes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Generador de Imágenes</h1>
        <form method="POST" action="image.php">
            <input type="text" name="keyword" placeholder="Ingresa una palabra clave" required>
            <input type="submit" value="Buscar Imagen" class="btn btn-primary">
        </form>
        <?php if (isset($image)): ?>
            <h2>Imagen para "<?= $keyword ?>":</h2>
            <img src="<?= $image->urls->regular ?>" alt="<?= $keyword ?>" class="img-fluid">
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
