<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre_planta"]);
    $variedad = $_POST["variedad"] ?? null;
    $estado = $_POST["estado"] ?? null;
    $cantidad = !empty($_POST["cantidad_disponible"]) ? $_POST["cantidad_disponible"] : null;
    $tiempo = $_POST["tiempo_viabilidad"] ?? null;
    $fecha = $_POST["fecha_recoleccion"] ?? null;
    $id_tipo = $_POST["id_tipo"];

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO semilla 
                (nombre_planta, variedad, estado, cantidad_disponible, tiempo_viabilidad, fecha_recoleccion, id_tipo)
                VALUES (:nombre, :variedad, :estado, :cantidad, :tiempo, :fecha, :id_tipo)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":variedad", $variedad);
        $stmt->bindParam(":estado", $estado);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":tiempo", $tiempo);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":id_tipo", $id_tipo);

        $stmt->execute();

        echo "✅ Semilla registrada correctamente.";
        echo '<br><a href="../vistas/form_semilla.php">⬅️ Volver al formulario</a>';

    } catch (PDOException $e) {
        echo "❌ Error al registrar la semilla: " . $e->getMessage();
    }
}
?>
