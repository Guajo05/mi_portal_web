<?php
// Configuración inicial
$name = '';
$gender = '';
$probability = 0;
$apiError = '';

// Función segura para obtener datos
function safeFetch($url) {
    // Intento 1: Usar cURL si está disponible
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        $response = curl_exec($ch);
        
        if (!curl_errno($ch)) {
            curl_close($ch);
            return $response;
        }
        curl_close($ch);
    }
    
    // Intento 2: Usar proxy local
    $proxyUrl = "http://localhost:3000/proxy?url=" . urlencode($url);
    $response = @file_get_contents($proxyUrl);
    
    return $response;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $url = "https://api.genderize.io/?name=" . urlencode($name);
    
    $response = safeFetch($url);
    
    if ($response === false) {
        $apiError = "Error: Configura un proxy local o habilita OpenSSL en php.ini";
    } else {
        $data = json_decode($response);
        
        if (!$data || isset($data->error)) {
            $apiError = "Error en la API: " . ($data->error ?? "Respuesta inválida");
        } else {
            $gender = $data->gender ?? 'desconocido';
            $probability = $data->probability ?? 0;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predicción de Género</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        .gender-card {
            max-width: 500px;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .gender-card:hover {
            transform: translateY(-5px);
        }
        .gender-male { 
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border-top: 5px solid #2196f3;
        }
        .gender-female { 
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            border-top: 5px solid #e91e63;
        }
        .gender-unknown { 
            background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
            border-top: 5px solid #9e9e9e;
        }
        .probability-bar {
            height: 25px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-predict {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-predict:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(37, 117, 252, 0.4);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-gradient">Predicción de Género</h1>
            <p class="lead text-muted">Descubre el género probable de cualquier nombre</p>
        </div>
        
        <form method="POST" class="mb-5">
            <div class="row g-3 justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="input-group">
                        <input type="text" name="name" class="form-control form-control-lg shadow-sm" 
                               placeholder="Ej: Carlos, María, Alex" required
                               value="<?= htmlspecialchars($name) ?>">
                        <button type="submit" class="btn btn-predict btn-lg text-white px-4">
                            <i class="bi bi-gender-ambiguous me-2"></i>Predecir
                        </button>
                    </div>
                </div>
            </div>
        </form>
        
        <?php if (!empty($apiError)): ?>
            <div class="alert alert-danger col-md-8 mx-auto">
                <div class="d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <h4 class="mb-0">Error de configuración</h4>
                </div>
                <hr>
                <p class="mb-1"><?= htmlspecialchars($apiError) ?></p>
                <div class="mt-3">
                    <h5>Pasos para solucionar:</h5>
                    <ol>
                        <li>Abre <code>php.ini</code></li>
                        <li>Busca <code>;extension=openssl</code></li>
                        <li>Elimina el punto y coma: <code>extension=openssl</code></li>
                        <li>Guarda y reinicia el servidor</li>
                    </ol>
                    <p class="mb-0">O instala un proxy local con Node.js:</p>
                    <pre class="bg-dark text-white p-3 rounded">npm install -g local-cors-proxy
lcp --proxyUrl https://api.genderize.io</pre>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($gender) && empty($apiError) && $_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <?php
            $cardClass = 'gender-unknown';
            $genderText = 'Desconocido';
            $icon = 'question-circle';
            
            if ($gender == 'male') {
                $cardClass = 'gender-male';
                $genderText = 'Masculino';
                $icon = 'gender-male';
            } elseif ($gender == 'female') {
                $cardClass = 'gender-female';
                $genderText = 'Femenino';
                $icon = 'gender-female';
            }
            ?>
            
            <div class="gender-card <?= $cardClass ?> p-4">
                <div class="text-center">
                    <h2 class="mb-3">Resultado para <strong><?= htmlspecialchars($name) ?></strong></h2>
                    
                    <div class="d-flex justify-content-center mb-4">
                        <div class="display-1">
                            <?php if ($gender == 'male'): ?>
                                ♂️
                            <?php elseif ($gender == 'female'): ?>
                                ♀️
                            <?php else: ?>
                                ❓
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h3 class="fw-bold mb-4"><?= $genderText ?></h3>
                    
                    <?php if ($probability > 0): ?>
                        <div class="mb-3">
                            <p class="mb-1">Probabilidad:</p>
                            <div class="probability-bar bg-white">
                                <div class="h-100 bg-info" 
                                     style="width: <?= $probability * 100 ?>%">
                                    <div class="h-100 d-flex justify-content-center align-items-center text-white fw-bold">
                                        <?= number_format($probability * 100, 1) ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <a href="gender.php" class="btn btn-outline-dark">
                            <i class="bi bi-arrow-repeat me-2"></i>Intentar con otro nombre
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-5 pt-4">
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="bi bi-house-door me-2"></i>Volver al inicio
            </a>
            <div class="mt-4 text-muted small">
                <p>Este sitio utiliza la API de <a href="https://genderize.io" target="_blank">Genderize.io</a></p>
                <p>Si los problemas persisten, contacta al administrador del sistema</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    
    <script>
        // Animación para la barra de probabilidad
        document.addEventListener('DOMContentLoaded', function() {
            const progressBar = document.querySelector('.probability-bar > div');
            if (progressBar) {
                // Guardar el ancho original
                const originalWidth = progressBar.style.width;
                // Resetear para animación
                progressBar.style.width = '0%';
                
                // Animar después de un breve retraso
                setTimeout(() => {
                    progressBar.style.width = originalWidth;
                }, 500);
            }
        });
    </script>
</body>
</html>