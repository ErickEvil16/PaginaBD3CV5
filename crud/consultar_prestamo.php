<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();
    $sql = "
        SELECT p.id_prestamo,
               u.nombre AS usuario,
               COALESCE(s.nombre_planta, '—') AS semilla,
               p.fecha_prestamo,
               p.fecha_devolucion,
               p.cantidad,
               p.estado
        FROM prestamo p
        LEFT JOIN usuario u ON p.id_usuario = u.id_usuario
        LEFT JOIN semilla s ON p.id_semilla = s.id_semilla
        ORDER BY p.fecha_prestamo DESC, p.id_prestamo DESC
    ";
    $stmt = $conn->query($sql);
    $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error al consultar préstamos: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Préstamos Registrados</title>
<link rel="stylesheet" href="styleConsultas.css">
</head>
<body>

<h2>Listado de Préstamos</h2>
<a href="../vistas/form_prestamo.php">➕ Registrar nuevo préstamo</a><br><br>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Semilla</th>
        <th>Fecha Préstamo</th>
        <th>Fecha Devolución</th>
        <th>Cantidad</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>

    <?php if (empty($prestamos)): ?>
    <tr><td colspan="8">No hay préstamos registrados.</td></tr>
    <?php else: ?>
        <?php foreach ($prestamos as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p["id_prestamo"]) ?></td>
            <td><?= htmlspecialchars($p["usuario"]) ?></td>
            <td><?= htmlspecialchars($p["semilla"]) ?></td>
            <td><?= htmlspecialchars($p["fecha_prestamo"]) ?></td>
            <td><?= $p["fecha_devolucion"] ? htmlspecialchars($p["fecha_devolucion"]) : "—" ?></td>
            <td><?= htmlspecialchars($p["cantidad"]) ?></td>
            <td><?= htmlspecialchars($p["estado"]) ?></td>
            <td>
                <form action="actualizar_prestamo.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id_prestamo" value="<?= $p["id_prestamo"] ?>">
                    <button type="submit">✏️ Actualizar</button>
                </form>

                <form action="eliminar_prestamo.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este préstamo?');">
                    <input type="hidden" name="id_prestamo" value="<?= $p["id_prestamo"] ?>">
                    <button type="submit">🗑️ Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<a href="../index.php"><button>Volver al inicio</button></a>
</body>
</html>
