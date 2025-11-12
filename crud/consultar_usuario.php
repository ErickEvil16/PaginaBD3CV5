<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    $sql = "
        SELECT 
            u.id_usuario,
            u.correo,
            u.nombre,
            u.telefono,
            f.id_familia,
            f.ubicacion AS nombre_familia,
            o.id_organizacion,
            o.nombre_instituto AS nombre_organizacion
        FROM usuario u
        LEFT JOIN familia f ON u.id_familia = f.id_familia
        LEFT JOIN organizacion o ON u.id_organizacion = o.id_organizacion
        ORDER BY u.id_usuario;
    ";

    $stmt = $conn->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error al consultar usuarios: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios Registrados</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f6f7; padding: 20px; }
    h2 { color: #276749; }
    table { border-collapse: collapse; width: 100%; background: #fff; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #2f855a; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    button { background-color: #2f855a; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px; }
    button:hover { background-color: #276749; }
</style>
</head>
<body>

<h2>Usuarios Registrados</h2>
<a href="../vistas/form_usuario.php">➕ Registrar Nuevo Usuario</a>
<br><br>

<table>
    <tr>
        <th>ID</th>
        <th>Correo</th>
        <th>Nombre</th>
        <th>Teléfono</th>
        <th>Familia</th>
        <th>Organización</th>
        <th>Acciones</th>
    </tr>

<?php foreach ($usuarios as $u): ?>
<tr>
    <td><?= htmlspecialchars($u["id_usuario"]) ?></td>
    <td><?= htmlspecialchars($u["correo"]) ?></td>
    <td><?= htmlspecialchars($u["nombre"]) ?></td>
    <td><?= htmlspecialchars($u["telefono"]) ?></td>
    <td><?= $u["id_familia"] ? "🏠 " . htmlspecialchars($u["nombre_familia"]) : "—" ?></td>
    <td><?= $u["id_organizacion"] ? "🏢 " . htmlspecialchars($u["nombre_organizacion"]) : "—" ?></td>
    <td>
        <form action="actualizar_usuario.php" method="POST" style="display:inline;">
            <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
            <button type="submit">Actualizar</button>
        </form>

        <form action="eliminar_usuario.php" method="POST" style="display:inline;">
            <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
            <button type="submit" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>
</table>

<a href="../index.php"><button>Volver al inicio</button></a>
</body>
</html>
