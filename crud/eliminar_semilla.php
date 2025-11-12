<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_semilla"])) {
    $id = intval($_POST["id_semilla"]);

    try {
        $conn = CConexion::ConexionBD();
        $sql = "DELETE FROM semilla WHERE id_semilla = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "✅ Semilla eliminada correctamente.";
        header("refresh:2; url=consultar_semilla.php");
    } catch (PDOException $e) {
        die("❌ Error al eliminar semilla: " . htmlspecialchars($e->getMessage()));
    }
} else {
    echo "❌ Solicitud inválida.";
}
?>
