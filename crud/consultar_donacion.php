<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    $sql = "
        SELECT d.id_donacion, u.nombre AS usuario, d.fecha_donacion, d.cantidad_donada,
               STRING_AGG(s.nombre_planta, ', ') AS semillas
        FROM donacion d
        INNER JOIN usuario u ON d.id_usuario = u.id_usuario
        LEFT JOIN donacion_semilla ds ON d.id_donacion = ds.id_donacion
        LEFT JOIN semilla s ON ds.id_semilla = s.id_semilla
        GROUP BY d.id_donacion, u.nombre, d.fecha_donacion, d.cantidad_donada
        ORDER BY d.fecha_donacion DESC;
    ";
    $stmt = $conn->query($sql);
    $donaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Donaciones Registradas</title>
<link rel="stylesheet" href="styleConsultas.css">
<style>

</style>
</head>
<body>

<h2>Donaciones Registradas</h2>
<a href="../vistas/form_donacion.php">➕ Nueva Donación</a><br><br>

<table>
    <tr>
        <th>ID Donación</th>
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Cantidad</th>
        <th>Semillas Donadas</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($donaciones as $d): ?>
    <tr>
        <td><?= htmlspecialchars($d["id_donacion"]) ?></td>
        <td><?= htmlspecialchars($d["usuario"]) ?></td>
        <td><?= htmlspecialchars($d["fecha_donacion"] ?? "—") ?></td>
        <td><?= htmlspecialchars($d["cantidad_donada"] ?? "—") ?></td>
        <td><?= htmlspecialchars($d["semillas"] ?? "—") ?></td>
        <td class="acciones">
            <form action="actualizar_donacion.php" method="get" style="display:inline;">
                <input type="hidden" name="id_donacion" value="<?= htmlspecialchars($d["id_donacion"]) ?>">
                <button type="submit" class="editar">✏️ Editar</button>
            </form>

            <form action="eliminar_donacion.php" method="post" style="display:inline;" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta donación?');">
                <input type="hidden" name="id_donacion" value="<?= htmlspecialchars($d["id_donacion"]) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <button type="submit" class="eliminar">🗑️ Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="../index.php"><button>Volver al inicio</button></a>

</body>
</html>
