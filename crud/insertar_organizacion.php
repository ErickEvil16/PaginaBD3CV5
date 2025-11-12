<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre_instituto = $_POST["nombre_instituto"];
    $responsable = $_POST["responsable"];

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO organizacion (nombre_instituto, responsable)
                VALUES (:nombre_instituto, :responsable)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":nombre_instituto", $nombre_instituto);
        $stmt->bindParam(":responsable", $responsable);

        $stmt->execute();

        echo "✅ Organización registrada correctamente.";
        echo '<br><a href="../vistas/form_organizacion.php">⬅️ Volver al formulario</a>';

    } catch (PDOException $e) {
        echo "❌ Error al insertar organización: " . $e->getMessage();
    }
}
?>
