<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    $sql = "
        SELECT s.id_semilla, s.nombre_planta, s.variedad, s.estado, 
               s.cantidad_disponible, s.tiempo_viabilidad, s.fecha_recoleccion,
               t.tipo
        FROM semilla s
        LEFT JOIN tipo t ON s.id_tipo = t.id_tipo
        ORDER BY s.nombre_planta;
    ";
    $stmt = $conn->query($sql);
    $semillas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error al consultar las semillas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleConsultas.css">
<meta charset="UTF-8">
<title>Semillas Registradas</title>
</head>
<body>

<h2>Semillas Registradas</h2>
<a href="../vistas/form_semilla.php">➕ Nueva Semilla</a><br><br>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Planta</th>
        <th>Variedad</th>
        <th>Estado</th>
        <th>Cantidad</th>
        <th>Viabilidad (meses)</th>
        <th>Fecha Recolección</th>
        <th>Tipo</th>
        <th>Acciones</th>
    </tr>

    <?php if (empty($semillas)): ?>
        <tr><td colspan="9">No hay semillas registradas.</td></tr>
    <?php else: ?>
        <?php foreach ($semillas as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s["id_semilla"]) ?></td>
            <td><?= htmlspecialchars($s["nombre_planta"]) ?></td>
            <td><?= htmlspecialchars($s["variedad"]) ?></td>
            <td><?= htmlspecialchars($s["estado"]) ?></td>
            <td><?= htmlspecialchars($s["cantidad_disponible"]) ?></td>
            <td><?= htmlspecialchars($s["tiempo_viabilidad"]) ?></td>
            <td><?= htmlspecialchars($s["fecha_recoleccion"]) ?></td>
            <td><?= htmlspecialchars($s["tipo"]) ?></td>
            <td>
                <form action="actualizar_semilla.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id_semilla" value="<?= $s["id_semilla"] ?>">
                    <button type="submit">✏️ Actualizar</button>
                </form>

                <form action="eliminar_semilla.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta semilla?');">
                    <input type="hidden" name="id_semilla" value="<?= $s["id_semilla"] ?>">
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
