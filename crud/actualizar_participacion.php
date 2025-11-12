<?php
include_once("../Conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualizar Participación</title>
<link rel="stylesheet" href="../vistas/styleForm.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_participacion"])) {
    $id = intval($_POST["id_participacion"]);

    try {
        $conn = CConexion::ConexionBD();

        // Guardar cambios
        if (isset($_POST["guardar"])) {
            $id_usuario = intval($_POST["id_usuario"]);
            $id_evento = intval($_POST["id_evento"]);

            $sql = "UPDATE participacion_evento
                    SET id_usuario = :id_usuario, id_evento = :id_evento
                    WHERE id_participacion = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id_usuario", $id_usuario);
            $stmt->bindParam(":id_evento", $id_evento);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            echo "✅ Participación actualizada correctamente.";
            header("refresh:2; url=consultar_participacion.php");
            exit;
        } else {
            // Obtener datos actuales
            $sql = "SELECT * FROM participacion_evento WHERE id_participacion = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $participacion = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$participacion) die("❌ Participación no encontrada.");

            // Listas de usuarios y eventos para seleccionar
            $usuarios = $conn->query("SELECT id_usuario, nombre FROM usuario ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
            $eventos = $conn->query("SELECT id_evento, nombre_evento FROM evento ORDER BY nombre_evento")->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        die("❌ Error al cargar participación: " . $e->getMessage());
    }
} else {
    die("❌ Solicitud inválida.");
}
?>

<h2>Actualizar Participación</h2>

<form method="POST">
    <input type="hidden" name="id_participacion" value="<?= htmlspecialchars($participacion["id_participacion"]) ?>">

    <label>Usuario:</label>
    <select name="id_usuario" required>
        <?php foreach ($usuarios as $u): ?>
            <option value="<?= $u["id_usuario"] ?>" <?= $u["id_usuario"] == $participacion["id_usuario"] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u["nombre"]) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Evento:</label>
    <select name="id_evento" required>
        <?php foreach ($eventos as $e): ?>
            <option value="<?= $e["id_evento"] ?>" <?= $e["id_evento"] == $participacion["id_evento"] ? 'selected' : '' ?>>
                <?= htmlspecialchars($e["nombre_evento"]) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit" name="guardar">💾 Guardar Cambios</button>
</form>

<a href="consultar_participacion.php"><button type="button">Volver</button></a>
</body>
</html>
