<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_POST["id_usuario"];
    $id_evento = $_POST["id_evento"];

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO participacion_evento (id_usuario, id_evento)
                VALUES (:id_usuario, :id_evento)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":id_evento", $id_evento);
        $stmt->execute();

        echo "✅ Participación registrada correctamente.";
        echo '<br><a href="../vistas/form_participacion_evento.php">⬅️ Volver al formulario</a>';
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>
