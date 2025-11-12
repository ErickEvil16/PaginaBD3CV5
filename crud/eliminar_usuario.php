<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_usuario"])) {
    $id_usuario = $_POST["id_usuario"];

    try {
        $conn = CConexion::ConexionBD();

        $stmt = $conn->prepare("DELETE FROM usuario WHERE id_usuario = :id");
        $stmt->bindParam(":id", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: consultar_usuario.php");
        exit;
    } catch (PDOException $e) {
        die("❌ Error al eliminar usuario: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("⚠️ Solicitud no válida.");
}
?>
