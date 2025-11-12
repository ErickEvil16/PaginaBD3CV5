<?php
include_once("../Conexion.php");

$conn = CConexion::ConexionBD();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_tipo"])) {
    $id_tipo = $_POST["id_tipo"];

    // Consulta de todos los tipos para mostrar en el select
    $stmt = $conn->query("SELECT id_tipo, tipo FROM tipo ORDER BY id_tipo ASC");
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Buscar el tipo actual
    $stmt2 = $conn->prepare("SELECT tipo FROM tipo WHERE id_tipo = :id_tipo");
    $stmt2->bindParam(":id_tipo", $id_tipo, PDO::PARAM_INT);
    $stmt2->execute();
    $tipo_actual = $stmt2->fetch(PDO::FETCH_ASSOC);

    if (!$tipo_actual) {
        die("❌ Tipo no encontrado.");
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nuevo_tipo"])) {
    // Actualización del tipo
    $id_tipo = $_POST["id_tipo"];
    $nuevo_tipo = $_POST["nuevo_tipo"];

    try {
        $sql = "UPDATE tipo SET tipo = :nuevo_tipo WHERE id_tipo = :id_tipo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nuevo_tipo", $nuevo_tipo);
        $stmt->bindParam(":id_tipo", $id_tipo, PDO::PARAM_INT);
        $stmt->execute();

        echo "✅ Tipo actualizado correctamente.<br>";
        echo "<a href='consultar_tipo.php'><button>Volver</button></a>";
        exit;
    } catch (PDOException $e) {
        die("❌ Error al actualizar: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("⚠️ Acceso no válido.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../vistas/styleForm.css">
    <meta charset="UTF-8">
    <title>Actualizar Tipo</title>
</head>
<body>

<h2>Actualizar Tipo</h2>
<p>Tipo actual: <strong><?= htmlspecialchars($tipo_actual["tipo"]) ?></strong></p>

<form method="POST" action="actualizar_tipo.php">
    <input type="hidden" name="id_tipo" value="<?= htmlspecialchars($id_tipo) ?>">

    <label for="nuevo_tipo">Selecciona un nuevo tipo:</label>
    <select name="nuevo_tipo" id="nuevo_tipo" required>
        <?php foreach ($tipos as $t): ?>
            <option value="<?= htmlspecialchars($t['tipo']) ?>">
                <?= htmlspecialchars($t['tipo']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>
    <button type="submit">Actualizar</button>
</form>

<a href="consultar_tipo.php"><button>Cancelar</button></a>

</body>
</html>
