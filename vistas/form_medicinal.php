<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Obtener únicamente las semillas de tipo medicinal
    $sql = "
        SELECT s.id_semilla, s.nombre_planta, t.tipo
        FROM semilla s
        LEFT JOIN tipo t ON s.id_tipo = t.id_tipo
        WHERE t.tipo = 'Medicinal'
        ORDER BY s.nombre_planta ASC
    ";

    $stmt = $conn->query($sql);
    $semillas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error al conectar o consultar: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Semilla Medicinal</title>
</head>
<body>

<h2>Registrar Semilla Medicinal</h2>

<form action="../crud/insertar_medicinal.php" method="POST">
    <label>Seleccionar Semilla:</label><br>
    <select name="id_semilla" required>
        <option value="">-- Selecciona una semilla --</option>
        <?php foreach ($semillas as $s): ?>
            <option value="<?= htmlspecialchars($s['id_semilla']) ?>">
                <?= htmlspecialchars($s['nombre_planta']) ?> (<?= htmlspecialchars($s['tipo']) ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Parte usada de la planta:</label><br>
    <input type="text" name="parte_usada" maxlength="100"><br><br>

    <label>Propiedades medicinales:</label><br>
    <textarea name="propiedades" rows="4" cols="40"></textarea><br><br>

    <button type="submit">Guardar</button>
</form>

<a href="../crud/consultar_medicinal.php"><button>📋 Consultar Semillas Medicinales</button></a>
<a href="../index.php"><button>Volver al inicio</button></a>

</body>
</html>
