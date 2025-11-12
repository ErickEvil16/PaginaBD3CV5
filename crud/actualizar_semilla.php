<?php
include_once("../Conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualizar Semilla</title>
<link rel="stylesheet" href="../vistas/styleForm.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_semilla"])) {
    $id = intval($_POST["id_semilla"]);

    try {
        $conn = CConexion::ConexionBD();

        if (isset($_POST["guardar"])) {
            $nombre = $_POST["nombre_planta"];
            $variedad = $_POST["variedad"];
            $estado = $_POST["estado"];
            $cantidad = intval($_POST["cantidad_disponible"]);
            $viabilidad = intval($_POST["tiempo_viabilidad"]);
            $fecha = $_POST["fecha_recoleccion"];
            $id_tipo = intval($_POST["id_tipo"]);

            $sql = "UPDATE semilla 
                    SET nombre_planta = :nombre,
                        variedad = :variedad,
                        estado = :estado,
                        cantidad_disponible = :cantidad,
                        tiempo_viabilidad = :viabilidad,
                        fecha_recoleccion = :fecha,
                        id_tipo = :id_tipo
                    WHERE id_semilla = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":variedad", $variedad);
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":viabilidad", $viabilidad);
            $stmt->bindParam(":fecha", $fecha);
            $stmt->bindParam(":id_tipo", $id_tipo);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            echo "✅ Semilla actualizada correctamente.";
            header("refresh:2; url=consultar_semilla.php");
            exit;
        } else {
            $sql = "SELECT * FROM semilla WHERE id_semilla = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $semilla = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$semilla) die("❌ Semilla no encontrada.");

            $tipos = $conn->query("SELECT id_tipo, tipo FROM tipo ORDER BY tipo")->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        die("❌ Error al cargar semilla: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("❌ Solicitud inválida.");
}
?>

<h2>Actualizar Semilla</h2>

<form method="POST">
    <input type="hidden" name="id_semilla" value="<?= htmlspecialchars($semilla["id_semilla"]) ?>">

    <label>Nombre de Planta:</label>
    <input type="text" name="nombre_planta" value="<?= htmlspecialchars($semilla["nombre_planta"]) ?>" required><br><br>

    <label>Variedad:</label>
    <input type="text" name="variedad" value="<?= htmlspecialchars($semilla["variedad"]) ?>" required><br><br>

    <label>Estado:</label>
    <select name="estado" required>
        <?php 
        $estados = ["Disponible", "No disponible", "Reservada"];
        foreach ($estados as $e): ?>
            <option value="<?= $e ?>" <?= $e == $semilla["estado"] ? 'selected' : '' ?>><?= $e ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Cantidad Disponible:</label>
    <input type="number" name="cantidad_disponible" min="0" value="<?= htmlspecialchars($semilla["cantidad_disponible"]) ?>" required><br><br>

    <label>Tiempo de Viabilidad (meses):</label>
    <input type="number" name="tiempo_viabilidad" min="1" value="<?= htmlspecialchars($semilla["tiempo_viabilidad"]) ?>" required><br><br>

    <label>Fecha de Recolección:</label>
    <input type="date" name="fecha_recoleccion" value="<?= htmlspecialchars($semilla["fecha_recoleccion"]) ?>"><br><br>

    <label>Tipo:</label>
    <select name="id_tipo" required>
        <?php foreach ($tipos as $t): ?>
            <option value="<?= $t["id_tipo"] ?>" <?= $t["id_tipo"] == $semilla["id_tipo"] ? 'selected' : '' ?>>
                <?= htmlspecialchars($t["tipo"]) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit" name="guardar">💾 Guardar Cambios</button>
</form>

<a href="consultar_semilla.php"><button type="button">Volver</button></a>
</body>
</html>
