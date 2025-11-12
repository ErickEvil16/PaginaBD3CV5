<?php
include_once("../Conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualizar Organización</title>
<link rel="stylesheet" href="../vistas/styleForm.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_organizacion"])) {
    $id = intval($_POST["id_organizacion"]);

    try {
        $conn = CConexion::ConexionBD();

        if (isset($_POST["guardar"])) {
            // Actualizar registro
            $nombre_instituto = trim($_POST["nombre_instituto"]);
            $responsable = trim($_POST["responsable"]);

            $sql = "UPDATE organizacion
                    SET nombre_instituto = :nombre_instituto,
                        responsable = :responsable
                    WHERE id_organizacion = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":nombre_instituto", $nombre_instituto);
            $stmt->bindParam(":responsable", $responsable);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            echo "✅ Organización actualizada correctamente.";
            header("refresh:2; url=consultar_organizacion.php");
            exit;
        } else {
            // Consultar datos actuales
            $sql = "SELECT * FROM organizacion WHERE id_organizacion = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $org = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$org) {
                die("❌ Organización no encontrada.");
            }
        }
    } catch (PDOException $e) {
        die("❌ Error al cargar organización: " . $e->getMessage());
    }
} else {
    die("❌ Solicitud inválida.");
}
?>

<h2>Actualizar Organización</h2>

<form method="POST">
    <input type="hidden" name="id_organizacion" value="<?= htmlspecialchars($org["id_organizacion"]) ?>">

    <label>Nombre del Instituto:</label>
    <input type="text" name="nombre_instituto" value="<?= htmlspecialchars($org["nombre_instituto"]) ?>" required><br><br>

    <label>Responsable:</label>
    <input type="text" name="responsable" value="<?= htmlspecialchars($org["responsable"]) ?>" required><br><br>

    <button type="submit" name="guardar">💾 Guardar Cambios</button>
</form>

<a href="consultar_organizacion.php"><button type="button">Volver</button></a>
</body>
</html>
