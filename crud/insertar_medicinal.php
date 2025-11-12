<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_semilla = $_POST["id_semilla"];
    $parte_usada = $_POST["parte_usada"] ?? null;
    $propiedades = $_POST["propiedades"] ?? null;

    try {
        $conn = CConexion::ConexionBD();

        $sql = "INSERT INTO medicinal (id_semilla, parte_usada, propiedades)
                VALUES (:id_semilla, :parte_usada, :propiedades)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_semilla", $id_semilla);
        $stmt->bindParam(":parte_usada", $parte_usada);
        $stmt->bindParam(":propiedades", $propiedades);
        $stmt->execute();

        echo "✅ Registro medicinal agregado correctamente.";
        echo '<br><a href="../vistas/form_medicinal.php">⬅️ Volver al formulario</a>';

    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'duplicate key')) {
            echo "⚠️ Esta semilla ya está registrada como medicinal.";
        } else {
            echo "❌ Error al registrar: " . $e->getMessage();
        }
    }
}
?>
