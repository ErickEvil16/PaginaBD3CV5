<?php
// vistas/form_prestamo.php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Obtener usuarios
    $usuarios = $conn->query("SELECT id_usuario, nombre FROM usuario ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener semillas (compatibilidad con distintos nombres de columna)
    $sqlSem = "SELECT id_semilla, COALESCE(nombre_planta) AS nombre_semilla FROM semilla ORDER BY nombre_semilla";
    $semillas = $conn->query($sqlSem)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En producción maneja mejor el error
    die("Error al cargar datos: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Préstamo</title>
</head>
<body>

<h2>Registrar un nuevo préstamo</h2>

<form action="../crud/insertar_prestamo.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <label>Usuario:</label><br>
    <select name="id_usuario" required>
        <option value="">-- Selecciona un usuario --</option>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= (int)$u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Semilla:</label><br>
    <select name="id_semilla" required>
        <option value="">-- Selecciona una semilla --</option>
        <?php foreach ($semillas as $s): ?>
            <option value="<?= (int)$s['id_semilla'] ?>"><?= htmlspecialchars($s['nombre_semilla']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Fecha de préstamo:</label><br>
    <input type="date" name="fecha_prestamo" required><br><br>

    <label>Fecha de devolución (opcional):</label><br>
    <input type="date" name="fecha_devolucion"><br><br>

    <label>Cantidad (en gramos o unidades):</label><br>
    <input type="number" name="cantidad" step="0.01" min="0" required><br><br>

    <label>Estado:</label><br>
    <select name="estado">
        <option value="Pendiente">Pendiente</option>
        <option value="Devuelto">Devuelto</option>
    </select><br><br>

    <button type="submit">Registrar préstamo</button>
</form>

<br>
<a href="../crud/consultar_prestamo.php"><button>📋 Ver préstamos</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>
</body>
</html>
