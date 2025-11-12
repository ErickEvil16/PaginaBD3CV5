<?php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    $sql = "
        SELECT pe.id_participacion,
               u.nombre AS usuario,
               e.nombre_evento AS evento,
               e.fecha_evento
        FROM participacion_evento pe
        INNER JOIN usuario u ON pe.id_usuario = u.id_usuario
        INNER JOIN evento e ON pe.id_evento = e.id_evento
        ORDER BY e.fecha_evento DESC, u.nombre ASC
    ";
    $stmt = $conn->query($sql);
    $participaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Participaciones en Eventos</title>
<link rel="stylesheet" href="styleConsultas.css">
</head>
<body>

<h2>Participaciones Registradas</h2>
<a href="../vistas/form_participacion.php">➕ Registrar nueva participación</a><br><br>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Evento</th>
        <th>Fecha del Evento</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($participaciones as $p): ?>
    <tr>
        <td><?= htmlspecialchars($p["id_participacion"]) ?></td>
        <td><?= htmlspecialchars($p["usuario"]) ?></td>
        <td><?= htmlspecialchars($p["evento"]) ?></td>
        <td><?= htmlspecialchars($p["fecha_evento"]) ?></td>
        <td>
            <form action="actualizar_participacion.php" method="POST" style="display:inline;">
                <input type="hidden" name="id_participacion" value="<?= $p["id_participacion"] ?>">
                <button type="submit">✏️ Actualizar</button>
            </form>

            <form action="eliminar_participacion.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta participación?');">
                <input type="hidden" name="id_participacion" value="<?= $p["id_participacion"] ?>">
                <button type="submit">🗑️ Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="../index.php"><button>Volver al inicio</button></a>
</body>
</html>
