<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pokemonName = htmlspecialchars($_POST['pokemon']);
    $response = file_get_contents("https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemonName));
    $pokemon = json_decode($response);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Pokémon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Información de un Pokémon</h1>
        <form method="POST" action="pokemon.php">
            <input type="text" name="pokemon" placeholder="Ingresa un Pokémon" required>
            <input type="submit" value="Consultar Pokémon" class="btn btn-primary">
        </form>
        <?php if (isset($pokemon)): ?>
            <h2><?= ucfirst($pokemon->name) ?></h2>
            <img src="<?= $pokemon->sprites->front_default ?>" alt="<?= $pokemon->name ?>" class="img-fluid">
            <p>Experiencia Base: <?= $pokemon->base_experience ?></p>
            <p>Habilidades: <?= implode(', ', array_map(fn($ability) => $ability->ability->name, $pokemon->abilities)) ?></p>
            <audio controls>
                <source src="<?= $pokemon->sounds[0] ?>" type="audio/mpeg">
                Tu navegador no soporta el elemento de audio.
            </audio>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
