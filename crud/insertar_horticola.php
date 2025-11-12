<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_semilla = $_POST["id_semilla"];
    $temporada = $_POST["temporada_siembra"] ?? null;
    $clima = $_POST["clima"] ?? null;

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO horticola (id_semilla, temporada_siembra, clima)
                VALUES (:id_semilla, :temporada, :clima)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_semilla", $id_semilla);
        $stmt->bindParam(":temporada", $temporada);
        $stmt->bindParam(":clima", $clima);
        $stmt->execute();

        echo "✅ Registro hortícola creado correctamente.";
        echo '<br><a href="../vistas/form_horticola.php">⬅️ Volver al formulario</a>';

    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'duplicate key')) {
            echo "⚠️ Esta semilla ya está registrada como hortícola.";
        } else {
            echo "❌ Error al registrar: " . $e->getMessage();
        }
    }
}
?>
