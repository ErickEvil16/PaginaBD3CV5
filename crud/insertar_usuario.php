<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = $_POST["correo"];
    $nombre = $_POST["nombre"];
    $telefono = $_POST["telefono"];
    $id_familia = !empty($_POST["id_familia"]) ? $_POST["id_familia"] : null;
    $id_organizacion = !empty($_POST["id_organizacion"]) ? $_POST["id_organizacion"] : null;

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO usuario (correo, nombre, telefono, id_familia, id_organizacion)
                VALUES (:correo, :nombre, :telefono, :id_familia, :id_organizacion)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":id_familia", $id_familia);
        $stmt->bindParam(":id_organizacion", $id_organizacion);

        $stmt->execute();

        echo "✅ Usuario registrado correctamente.";
        echo '<br><a href="../vistas/form_usuario.php">⬅️ Volver al formulario</a>';

    } catch (PDOException $e) {
        echo "❌ Error al insertar usuario: " . $e->getMessage();
    }
}
?>
