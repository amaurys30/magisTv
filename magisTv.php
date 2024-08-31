<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recomendaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
// Historial de películas de los usuarios
$historialPelicula = [
    'usuario1' => ['Rápido y furioso', 'Mad max', 'Deadpool'],
    'usuario2' => ['El pianista', 'Divergente', 'Insurgente'],
    'Amaurys' => ['Deadpool', 'Tres metros sobre el cielo', 'El pianista'],
];

// Catálogo de películas/series por género
$catalogo = [
    'Acción' => ['Duro de matar', 'Rápido y furioso', 'Mad max'],
    'Comedia' => ['Deadpool', 'Son como niños 1', 'Son como niños 2'],
    'Drama' => ['El pianista', 'Cadena perpetua', 'La vida es bella'],
    'Romance' => ['Tres metros sobre el cielo', 'Culpa mía', 'Yo antes de ti'],
    'Documental' => ['Divergente', 'Insurgente', 'Leal', "Juegos del hambre"],
];

function obtenerGenerosPreferidos($historial, $catalogo) {
    $generosVistos = [];

    // Recorrer el historial del usuario
    foreach ($historial as $peliculaVisto) {
        // Identificar el género al que pertenece cada película
        foreach ($catalogo as $genero => $peliculas) {
            if (in_array($peliculaVisto, $peliculas)) {
                if (!isset($generosVistos[$genero])) {
                    $generosVistos[$genero] = 0;
                }
                $generosVistos[$genero]++;
            }
        }
    }

    // Ordenar los géneros por cantidad de películas vistas (descendente)
    arsort($generosVistos);
    return array_keys($generosVistos); // Devolver los géneros más vistos en orden
}

function obtenerRecomendaciones($usuario, $historialPelicula, $catalogo) {
    if (!isset($historialPelicula[$usuario])) {
        return "Usuario no encontrado.";
    }

    $historial = $historialPelicula[$usuario];
    $generosPreferidos = obtenerGenerosPreferidos($historial, $catalogo);
    $recomendaciones = [];

    // Obtener recomendaciones de los géneros preferidos
    foreach ($generosPreferidos as $genero) {
        foreach ($catalogo[$genero] as $pelicula) {
            if (!in_array($pelicula, $historial)) {
                $recomendaciones[] = $pelicula;
            }
        }
    }

    if (empty($recomendaciones)) {
        return "No hay nuevas recomendaciones disponibles.";
    }

    return $recomendaciones;
}

// Manejo de la solicitud POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuario = $_POST['user'];
        $recomendaciones = obtenerRecomendaciones($usuario, $historialPelicula, $catalogo);

        echo '<div class="container mt-5">';
        if (is_array($recomendaciones)) {
            echo "<h2 class='mb-4 text-center'>Recomendaciones para <strong>$usuario</strong>:</h2>";
            echo "<div class='row row-cols-1 row-cols-md-3 g-4'>"; // Sistema de grilla de Bootstrap
            foreach ($recomendaciones as $recomendacion) {
                echo "<div class='col'>";
                echo "<div class='card mb-4'>";
                echo "<img src='recursos/" . str_replace(' ', '', $recomendacion) . ".jpg' class='card-img-top' alt='$recomendacion' width='300' height='200'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>$recomendacion</h5>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='text-center'>$recomendaciones</p>";
        }
        echo '</div>';
    }
?>

</body>
</html>