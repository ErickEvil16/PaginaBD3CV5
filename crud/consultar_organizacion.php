<?php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    $sql = "SELECT * FROM organizacion ORDER BY id_organizacion";
    $stmt = $conn->query($sql);
    $organizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error al consultar organizaciones: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Organizaciones Registradas</title>
<link rel="stylesheet" href="styleConsultas.css">
</head>
<body>

<h2>Organizaciones Registradas</h2>
<a href="../vistas/form_organizacion.php">➕ Registrar Nueva Organización</a>
<br><br>

<table border="1">
    <tr>
        <th>ID Organización</th>
        <th>Nombre del Instituto</th>
        <th>Responsable</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($organizaciones as $o): ?>
    <tr>
        <td><?= htmlspecialchars($o["id_organizacion"]) ?></td>
        <td><?= htmlspecialchars($o["nombre_instituto"]) ?></td>
        <td><?= htmlspecialchars($o["responsable"]) ?></td>
        <td>
            <form action="actualizar_organizacion.php" method="POST" style="display:inline;">
                <input type="hidden" name="id_organizacion" value="<?= $o["id_organizacion"] ?>">
                <button type="submit">✏️ Actualizar</button>
            </form>

            <form action="eliminar_organizacion.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta organización?');">
                <input type="hidden" name="id_organizacion" value="<?= $o["id_organizacion"] ?>">
                <button type="submit">🗑️ Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="../index.php"><button>Volver al inicio</button></a>
</body>
</html>
