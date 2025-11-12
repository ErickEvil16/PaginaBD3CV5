<?php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Consulta de todos los tipos registrados
    $sql = "SELECT id_tipo, tipo FROM tipo ORDER BY id_tipo ASC";
    $stmt = $conn->query($sql);
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error al consultar los tipos: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tipos Registrados</title>
    <link rel="stylesheet" href="styleConsultas.css">
</head>
<body>

<h2>Tipos Registrados</h2>
<a href="../vistas/form_tipo.php">➕ Registrar nuevo tipo</a><br><br>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>ID Tipo</th>
        <th>Tipo</th>
    </tr>

    <?php foreach ($tipos as $t): ?>
    <tr>
        <td><?= htmlspecialchars($t["id_tipo"]) ?></td>
        <td><?= htmlspecialchars($t["tipo"]) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="../index.php"><button>Volver al inicio</button></a>

</body>
</html>
