<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_organizacion"])) {
    $id = intval($_POST["id_organizacion"]);

    try {
        $conn = CConexion::ConexionBD();
        $sql = "DELETE FROM organizacion WHERE id_organizacion = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "✅ Organización eliminada correctamente.";
        header("refresh:2; url=consultar_organizacion.php");
    } catch (PDOException $e) {
        die("❌ Error al eliminar organización: " . $e->getMessage());
    }
} else {
    echo "❌ Solicitud inválida.";
}
?>
