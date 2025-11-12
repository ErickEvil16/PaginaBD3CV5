<?php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();
    $sql = "SELECT * FROM familia ORDER BY id_familia";
    $stmt = $conn->query($sql);
    $familias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Token CSRF para formularios de eliminación
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

} catch (PDOException $e) {
    die("❌ Error al consultar familias: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Familias Registradas</title>
<link rel="stylesheet" href="styleConsultas.css">
</head>
<body>

<h2>Familias Registradas</h2>
<a href="../vistas/form_familia.php">➕ Registrar Nueva Familia</a>
<br><br>

<table>
    <tr>
        <th>ID Familia</th>
        <th>Número de Integrantes</th>
        <th>Ubicación</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($familias as $f): ?>
    <tr>
        <td><?= htmlspecialchars($f["id_familia"]) ?></td>
        <td><?= htmlspecialchars($f["numero_integrantes"]) ?></td>
        <td><?= htmlspecialchars($f["ubicacion"]) ?></td>
        <td>
            <form action="actualizar_familia.php" method="get" style="display:inline;">
                <input type="hidden" name="id_familia" value="<?= (int)$f['id_familia'] ?>">
                <button type="submit">✏️ Editar</button>
            </form>

            <form action="eliminar_familia.php" method="post" style="display:inline;" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta familia?');">
                <input type="hidden" name="id_familia" value="<?= (int)$f['id_familia'] ?>">
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
