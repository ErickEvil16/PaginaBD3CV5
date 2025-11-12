<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id_usuario"];
    $correo = $_POST["correo"];
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];

    try {
        $conn = CConexion::ConexionBD();
        $sql = "UPDATE usuario 
                SET correo = :correo, nombre = :nombre, telefono = :telefono 
                WHERE id_usuario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        echo "✅ Usuario actualizado correctamente.";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
