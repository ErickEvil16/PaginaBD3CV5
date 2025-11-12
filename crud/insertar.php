<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST["correo"];
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];

    try {
        $conn = CConexion::ConexionBD();
        $sql = "INSERT INTO usuario (correo, nombre, telefono) VALUES (:correo, :nombre, :telefono)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->execute();
        echo "✅ Usuario agregado correctamente.";
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
