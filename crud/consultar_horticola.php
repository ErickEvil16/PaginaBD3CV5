<?php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    $sql = "
        SELECT h.id_semilla, s.nombre_planta, s.variedad, s.estado, 
               h.temporada_siembra, h.clima, t.tipo
        FROM horticola h
        INNER JOIN semilla s ON h.id_semilla = s.id_semilla
        LEFT JOIN tipo t ON s.id_tipo = t.id_tipo
        ORDER BY s.nombre_planta;
    ";
    $stmt = $conn->query($sql);
    $horticolas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

} catch (PDOException $e) {
    die("❌ Error al consultar: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Semillas Hortícolas Registradas</title>
<link rel="stylesheet" href="styleConsultas.css">
</head>
<body>

<h2>Semillas Hortícolas Registradas</h2>
<a href="../vistas/form_horticola.php">➕ Nueva Semilla Hortícola</a><br><br>

<table border="1">
    <tr>
        <th>ID Semilla</th>
        <th>Planta</th>
        <th>Variedad</th>
        <th>Estado</th>
        <th>Tipo</th>
        <th>Temporada de Siembra</th>
        <th>Clima</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($horticolas as $h): ?>
    <tr>
        <td><?= htmlspecialchars($h["id_semilla"]) ?></td>
        <td><?= htmlspecialchars($h["nombre_planta"]) ?></td>
        <td><?= htmlspecialchars($h["variedad"]) ?></td>
        <td><?= htmlspecialchars($h["estado"]) ?></td>
        <td><?= htmlspecialchars($h["tipo"] ?? "—") ?></td>
        <td><?= htmlspecialchars($h["temporada_siembra"] ?? "—") ?></td>
        <td><?= htmlspecialchars($h["clima"] ?? "—") ?></td>
        <td>
            <form action="actualizar_horticola.php" method="get" style="display:inline;">
                <input type="hidden" name="id_semilla" value="<?= (int)$h['id_semilla'] ?>">
                <button type="submit">✏️ Editar</button>
            </form>

            <form action="eliminar_horticola.php" method="post" style="display:inline;"
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta semilla hortícola?');">
                <input type="hidden" name="id_semilla" value="<?= (int)$h['id_semilla'] ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit">🗑️ Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="../index.php"><button>Volver al inicio</button></a>
</body>
</html>
