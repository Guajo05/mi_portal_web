<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $site = htmlspecialchars($_POST['site']);
    $response = file_get_contents($site . '/wp-json/wp/v2/posts?per_page=3');
    $news = json_decode($response);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Últimas Noticias</h1>
        <form method="POST" action="news.php">
            <input type="text" name="site" placeholder="Ingresa la URL de la página" required>
            <input type="submit" value="Obtener Noticias" class="btn btn-primary">
        </form>
        <?php if (isset($news)): ?>
            <h2>Noticias de <?= $site ?>:</h2>
            <ul class="list-group">
                <?php foreach ($news as $article): ?>
                    <li class="list-group-item">
                        <strong><?= $article->title->rendered ?></strong><br>
                        <?= $article->excerpt->rendered ?><br>
                        <a href="<?= $article->link ?>" target="_blank">Leer más</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
