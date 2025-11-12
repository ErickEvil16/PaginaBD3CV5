<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo = trim($_POST["tipo"]);

    if (empty($tipo)) {
        die("❌ El campo 'tipo' no puede estar vacío.");
    }

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO tipo (tipo) VALUES (:tipo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":tipo", $tipo);
        $stmt->execute();

        echo "✅ Tipo registrado correctamente.";
        echo '<br><a href="../vistas/form_tipo.php">⬅️ Volver al formulario</a>';
    } catch (PDOException $e) {
        echo "❌ Error al registrar el tipo: " . $e->getMessage();
    }
}
?>
