<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Consultar familias y organizaciones existentes
    $familias = $conn->query("
        SELECT id_familia, ubicacion
        FROM familia
        ORDER BY id_familia;
    ")->fetchAll(PDO::FETCH_ASSOC);

    $organizaciones = $conn->query("
        SELECT id_organizacion, nombre_instituto
        FROM organizacion
        ORDER BY nombre_instituto;
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error al obtener listas: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Usuario</title>
</head>
<body>

<h2>Registrar Nuevo Usuario</h2>

<form action="../crud/insertar_usuario.php" method="POST">
    <label>Correo:</label>
    <input type="email" name="correo" required>

    <label>Nombre:</label>
    <input type="text" name="nombre" maxlength="100" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono" maxlength="20">

    <label>Familia (opcional):</label>
    <select name="id_familia">
        <option value="">-- Seleccionar Familia --</option>
        <?php foreach ($familias as $f): ?>
            <option value="<?= $f['id_familia'] ?>">
                ID <?= $f['id_familia'] ?> - <?= htmlspecialchars($f['ubicacion']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Organización (opcional):</label>
    <select name="id_organizacion">
        <option value="">-- Seleccionar Organización --</option>
        <?php foreach ($organizaciones as $o): ?>
            <option value="<?= $o['id_organizacion'] ?>">
                <?= htmlspecialchars($o['nombre_instituto']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Guardar Usuario</button>
</form>

<br>
<a href="../crud/consultar_usuario.php"><button>📋 Consultar Usuarios</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>
</body>
</html>
