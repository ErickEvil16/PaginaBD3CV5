<?php
include_once("../Conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualizar Semilla Medicinal</title>
<link rel="stylesheet" href="../vistas/styleForm.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_semilla"])) {
    $id = intval($_POST["id_semilla"]);

    try {
        $conn = CConexion::ConexionBD();

        if (isset($_POST["guardar"])) {
            // Actualizar datos
            $parte_usada = trim($_POST["parte_usada"]);
            $propiedades = trim($_POST["propiedades"]);

            $sql = "UPDATE medicinal
                    SET parte_usada = :parte_usada, propiedades = :propiedades
                    WHERE id_semilla = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":parte_usada", $parte_usada);
            $stmt->bindParam(":propiedades", $propiedades);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            echo "✅ Semilla medicinal actualizada correctamente.";
            header("refresh:2; url=consultar_medicinal.php");
            exit;
        } else {
            // Cargar datos actuales
            $sql = "SELECT * FROM medicinal WHERE id_semilla = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $med = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$med) {
                die("❌ Semilla no encontrada.");
            }
        }
    } catch (PDOException $e) {
        die("❌ Error: " . $e->getMessage());
    }
} else {
    die("❌ Solicitud inválida.");
}
?>

<h2>Actualizar Semilla Medicinal</h2>

<form method="POST">
    <input type="hidden" name="id_semilla" value="<?= htmlspecialchars($med["id_semilla"]) ?>">

    <label>Parte Usada:</label>
    <input type="text" name="parte_usada" value="<?= htmlspecialchars($med["parte_usada"]) ?>" required><br><br>

    <label>Propiedades:</label>
    <textarea name="propiedades" required><?= htmlspecialchars($med["propiedades"]) ?></textarea><br><br>

    <button type="submit" name="guardar">💾 Guardar Cambios</button>
</form>

<a href="consultar_medicinal.php"><button type="button">Volver</button></a>
</body>
</html>
