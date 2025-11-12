<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_participacion"])) {
    $id = intval($_POST["id_participacion"]);

    try {
        $conn = CConexion::ConexionBD();
        $sql = "DELETE FROM participacion_evento WHERE id_participacion = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "✅ Participación eliminada correctamente.";
        header("refresh:2; url=consultar_participacion.php");
    } catch (PDOException $e) {
        die("❌ Error al eliminar participación: " . $e->getMessage());
    }
} else {
    echo "❌ Solicitud inválida.";
}
?>
