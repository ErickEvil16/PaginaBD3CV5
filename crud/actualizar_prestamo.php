<?php
include_once("../Conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualizar Préstamo</title>
<link rel="stylesheet" href="../vistas/styleForm.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_prestamo"])) {
    $id = intval($_POST["id_prestamo"]);

    try {
        $conn = CConexion::ConexionBD();

        // Si el usuario envía cambios
        if (isset($_POST["guardar"])) {
            $id_usuario = intval($_POST["id_usuario"]);
            $id_semilla = intval($_POST["id_semilla"]);
            $fecha_prestamo = $_POST["fecha_prestamo"];
            $fecha_devolucion = $_POST["fecha_devolucion"] ?: null;
            $cantidad = intval($_POST["cantidad"]);
            $estado = $_POST["estado"];

            $sql = "UPDATE prestamo 
                    SET id_usuario = :id_usuario,
                        id_semilla = :id_semilla,
                        fecha_prestamo = :fecha_prestamo,
                        fecha_devolucion = :fecha_devolucion,
                        cantidad = :cantidad,
                        estado = :estado
                    WHERE id_prestamo = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id_usuario", $id_usuario);
            $stmt->bindParam(":id_semilla", $id_semilla);
            $stmt->bindParam(":fecha_prestamo", $fecha_prestamo);
            $stmt->bindParam(":fecha_devolucion", $fecha_devolucion);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":estado", $estado);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            echo "✅ Préstamo actualizado correctamente.";
            header("refresh:2; url=consultar_prestamo.php");
            exit;
        } else {
            // Obtener préstamo actual
            $sql = "SELECT * FROM prestamo WHERE id_prestamo = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$prestamo) die("❌ Préstamo no encontrado.");

            $usuarios = $conn->query("SELECT id_usuario, nombre FROM usuario ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
            $semillas = $conn->query("SELECT id_semilla, nombre_planta FROM semilla ORDER BY nombre_planta")->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        die("❌ Error al cargar préstamo: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("❌ Solicitud inválida.");
}
?>

<h2>Actualizar Préstamo</h2>

<form method="POST">
    <input type="hidden" name="id_prestamo" value="<?= htmlspecialchars($prestamo["id_prestamo"]) ?>">

    <label>Usuario:</label>
    <select name="id_usuario" required>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u["id_usuario"] ?>" <?= $u["id_usuario"] == $prestamo["id_usuario"] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u["nombre"]) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Semilla:</label>
    <select name="id_semilla" required>
        <?php foreach ($semillas as $s): ?>
            <option value="<?= $s["id_semilla"] ?>" <?= $s["id_semilla"] == $prestamo["id_semilla"] ? 'selected' : '' ?>>
                <?= htmlspecialchars($s["nombre_planta"]) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Fecha de Préstamo:</label>
    <input type="date" name="fecha_prestamo" value="<?= htmlspecialchars($prestamo["fecha_prestamo"]) ?>" required><br><br>

    <label>Fecha de Devolución:</label>
    <input type="date" name="fecha_devolucion" value="<?= htmlspecialchars($prestamo["fecha_devolucion"] ?? '') ?>"><br><br>

    <label>Cantidad:</label>
    <input type="number" name="cantidad" min="1" value="<?= htmlspecialchars($prestamo["cantidad"]) ?>" required><br><br>

    <label>Estado:</label>
    <select name="estado" required>
        <?php 
        $estados = ["Pendiente", "Devuelto", "Retrasado"];
        foreach ($estados as $e):
        ?>
            <option value="<?= $e ?>" <?= $e == $prestamo["estado"] ? 'selected' : '' ?>><?= $e ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit" name="guardar">💾 Guardar Cambios</button>
</form>

<a href="consultar_prestamo.php"><button type="button">Volver</button></a>
</body>
</html>
