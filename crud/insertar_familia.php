<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numero_integrantes = $_POST["numero_integrantes"];
    $ubicacion = $_POST["ubicacion"];

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO familia (numero_integrantes, ubicacion)
                VALUES (:numero_integrantes, :ubicacion)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":numero_integrantes", $numero_integrantes);
        $stmt->bindParam(":ubicacion", $ubicacion);

        $stmt->execute();

        echo "✅ Familia registrada correctamente.";
        echo '<br><a href="../vistas/form_familia.php">⬅️ Volver al formulario</a>';

    } catch (PDOException $e) {
        echo "❌ Error al insertar familia: " . $e->getMessage();
    }
}
?>
