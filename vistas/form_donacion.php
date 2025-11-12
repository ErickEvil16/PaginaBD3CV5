<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Obtener usuarios
    $usuarios = $conn->query("
        SELECT id_usuario, nombre FROM usuario ORDER BY nombre
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener semillas disponibles
    $semillas = $conn->query("
        SELECT id_semilla, nombre_planta FROM semilla ORDER BY nombre_planta
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Donación</title>
</head>
<body>

<h2>Registrar Donación</h2>

<form action="../crud/insertar_donacion.php" method="POST">
    <label>Usuario Donante:</label><br>
    <select name="id_usuario" required>
        <option value="">-- Selecciona un usuario --</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Fecha de Donación:</label><br>
    <input type="date" name="fecha_donacion" required><br><br>

    <label>Cantidad Donada (total):</label><br>
    <input type="number" name="cantidad_donada" min="1" required><br><br>

    <label>Seleccionar Semillas Donadas:</label><br>
    <?php foreach ($semillas as $s): ?>
        <input type="checkbox" name="semillas[]" value="<?= $s['id_semilla'] ?>">
        <?= htmlspecialchars($s['nombre_planta']) ?><br>
    <?php endforeach; ?>
    <br>

    <button type="submit">Registrar Donación</button>
</form>

<br>
<a href="../crud/consultar_donacion.php"><button>📋 Ver Donaciones</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
