<?php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    $sql = "
        SELECT m.id_semilla, s.nombre_planta, s.variedad, s.estado, 
               m.parte_usada, m.propiedades, t.tipo
        FROM medicinal m
        INNER JOIN semilla s ON m.id_semilla = s.id_semilla
        LEFT JOIN tipo t ON s.id_tipo = t.id_tipo
        ORDER BY s.nombre_planta;
    ";
    $stmt = $conn->query($sql);
    $medicinales = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('❌ Error al consultar: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleConsultas.css">
<meta charset="UTF-8">
<title>Semillas Medicinales Registradas</title>
</head>
<body>

<h2>Semillas Medicinales Registradas</h2>
<a href="../vistas/form_medicinal.php">➕ Nueva Semilla Medicinal</a><br><br>

<table border="1">
    <tr>
        <th>ID Semilla</th>
        <th>Planta</th>
        <th>Variedad</th>
        <th>Estado</th>
        <th>Tipo</th>
        <th>Parte Usada</th>
        <th>Propiedades</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($medicinales as $m): ?>
    <tr>
        <td><?= htmlspecialchars($m["id_semilla"]) ?></td>
        <td><?= htmlspecialchars($m["nombre_planta"]) ?></td>
        <td><?= htmlspecialchars($m["variedad"]) ?></td>
        <td><?= htmlspecialchars($m["estado"]) ?></td>
        <td><?= htmlspecialchars($m["tipo"]) ?></td>
        <td><?= htmlspecialchars($m["parte_usada"]) ?></td>
        <td><?= htmlspecialchars($m["propiedades"]) ?></td>
        <td>
            <form action="actualizar_medicinal.php" method="POST" style="display:inline;">
                <input type="hidden" name="id_semilla" value="<?= $m["id_semilla"] ?>">
                <button type="submit">✏️ Actualizar</button>
            </form>
            <form action="eliminar_medicinal.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta semilla medicinal?');">
                <input type="hidden" name="id_semilla" value="<?= $m["id_semilla"] ?>">
                <button type="submit">🗑️ Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="../index.php"><button>Volver al inicio</button></a>
</body>
</html>
