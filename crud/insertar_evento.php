<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre_evento"];
    $tema = !empty($_POST["tema"]) ? $_POST["tema"] : null;
    $fecha = !empty($_POST["fecha_evento"]) ? $_POST["fecha_evento"] : null;

    try {
        $conn = CConexion::ConexionBD();
        $sql = "INSERT INTO evento (nombre_evento, tema, fecha_evento)
                VALUES (:nombre, :tema, :fecha)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":tema", $tema);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->execute();

        echo "✅ Evento registrado correctamente.";
        echo '<br><a href="../vistas/form_evento.php">⬅️ Volver al formulario</a>';
    } catch (PDOException $e) {
        echo "❌ Error al registrar el evento: " . $e->getMessage();
    }
}
?>
