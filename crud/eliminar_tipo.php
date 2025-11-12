<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_tipo = $_POST["id_tipo"] ?? null;

    if (!$id_tipo) {
        die("❌ No se proporcionó el ID del tipo a eliminar.");
    }

    try {
        $conn = CConexion::ConexionBD();
        $sql = "DELETE FROM tipo WHERE id_tipo = :id_tipo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_tipo", $id_tipo, PDO::PARAM_INT);
        $stmt->execute();

        echo "🗑️ Tipo eliminado correctamente.<br>";
        echo "<a href='consultar_tipo.php'><button>Volver</button></a>";
    } catch (PDOException $e) {
        die("❌ Error al eliminar el tipo: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("⚠️ Acceso no válido.");
}
?>
