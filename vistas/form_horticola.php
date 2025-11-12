<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Obtener las semillas disponibles
    $sql = "
        SELECT s.id_semilla, s.nombre_planta, t.tipo
        FROM semilla s
        LEFT JOIN tipo t ON s.id_tipo = t.id_tipo
        WHERE s.id_tipo = 1
        ORDER BY s.nombre_planta ASC
    ";

    $stmt = $conn->query($sql);
    $semillas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error de conexión o consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Semilla Hortícola</title>
</head>
<body>

<h2>Registrar Semilla Hortícola</h2>

<form action="../crud/insertar_horticola.php" method="POST">
    <label>Seleccionar Semilla:</label><br>

    <select name="id_semilla" required>
        <option value="">-- Selecciona una semilla --</option>
        <?php foreach ($semillas as $s): ?>
            <option value="<?= $s['id_semilla'] ?>">
                <?= htmlspecialchars($s['nombre_planta']) ?> (<?= htmlspecialchars($s['tipo'] ?? 'Sin tipo') ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Temporada de Siembra:</label><br>
    <input type="text" name="temporada_siembra" maxlength="100"><br><br>

    <label>Clima Recomendado:</label><br>
    <input type="text" name="clima" maxlength="100"><br><br>

    <button type="submit">Guardar</button>
</form>

<br>
<a href="../crud/consultar_horticola.php"><button>📋 Ver Semillas Hortícolas</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
