<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Obtener los tipos desde la base de datos
    $tipos = $conn->query("SELECT id_tipo, tipo FROM tipo ORDER BY tipo ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error al conectar o consultar la BD: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Semilla</title>
</head>
<body>

<h2>Registrar una Nueva Semilla</h2>

<form action="../crud/insertar_semilla.php" method="POST">
    <label>Nombre de la Planta:</label><br>
    <input type="text" name="nombre_planta" maxlength="100" required><br><br>

    <label>Variedad:</label><br>
    <input type="text" name="variedad" maxlength="100"><br><br>

    <label>Estado:</label><br>
    <select name="estado" required>
        <option value="">-- Selecciona un tipo --</option>
        <option value="Disponible">Disponible-</option>
        <option value="No Disponible">No Disponible</option>

    </select><br><br>

    <label>Cantidad Disponible:</label><br>
    <input type="number" name="cantidad_disponible" min="0"><br><br>

    <label>Tiempo de Viabilidad:</label><br>
    <input type="text" name="tiempo_viabilidad" maxlength="50"><br><br>

    <label>Fecha de Recolección:</label><br>
    <input type="date" name="fecha_recoleccion"><br><br>

    <label>Tipo de Semilla:</label><br>
    <select name="id_tipo" required>
        <option value="">-- Selecciona un tipo --</option>
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['id_tipo'] ?>"><?= htmlspecialchars($t['tipo']) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Guardar</button>
</form>

<br>
<a href="../crud/consultar_semilla.php"><button>📋 Ver Semillas Registradas</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
