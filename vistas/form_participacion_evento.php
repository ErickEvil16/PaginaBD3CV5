<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Obtener usuarios
    $usuarios = $conn->query("SELECT id_usuario, nombre FROM usuario ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener eventos
    $eventos = $conn->query("SELECT id_evento, nombre_evento FROM evento ORDER BY nombre_evento")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Participación en Evento</title>
</head>
<body>

<h2>Registrar Participación en Evento</h2>

<form action="../crud/insertar_participacion.php" method="POST">

    <label>Seleccionar Usuario:</label><br>
    <select name="id_usuario" required>
        <option value="">-- Selecciona un usuario --</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Seleccionar Evento:</label><br>
    <select name="id_evento" required>
        <option value="">-- Selecciona un evento --</option>
        <?php foreach ($eventos as $e): ?>
            <option value="<?= $e['id_evento'] ?>"><?= htmlspecialchars($e['nombre_evento']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Registrar Participación</button>
</form>

<br>
<a href="../crud/consultar_participacion.php"><button>📋 Ver participaciones</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
