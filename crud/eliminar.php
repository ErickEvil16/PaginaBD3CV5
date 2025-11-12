<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id_usuario"];

    try {
        $conn = CConexion::ConexionBD();
        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        echo "🗑️ Usuario eliminado correctamente.";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
