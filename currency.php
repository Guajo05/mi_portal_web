<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = htmlspecialchars($_POST['amount']);
    $response = file_get_contents("https://api.exchangerate-api.com/v4/latest/USD");
    $data = json_decode($response);
    $conversionRate = $data->rates->DOP ?? 0;
    $convertedAmount = $amount * $conversionRate;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversión de Monedas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container text-center">
        <h1>Conversión de Monedas</h1>
        <form method="POST" action="currency.php">
            <input type="number" name="amount" placeholder="Cantidad en USD" required>
            <input type="submit" value="Convertir" class="btn btn-primary">
        </form>
        <?php if (isset($convertedAmount)): ?>
            <h2><?= $amount ?> USD = <?= number_format($convertedAmount, 2) ?> DOP</h2>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary">Regresar</a>
    </div>
</body>
</html>
