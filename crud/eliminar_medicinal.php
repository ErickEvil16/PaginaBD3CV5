<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_semilla"])) {
    $id = intval($_POST["id_semilla"]);

    try {
        $conn = CConexion::ConexionBD();
        $sql = "DELETE FROM medicinal WHERE id_semilla = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "✅ Semilla medicinal eliminada correctamente.";
        header("refresh:2; url=consultar_medicinal.php");
    } catch (PDOException $e) {
        die("❌ Error al eliminar: " . $e->getMessage());
    }
} else {
    echo "❌ Solicitud inválida.";
}
?>
