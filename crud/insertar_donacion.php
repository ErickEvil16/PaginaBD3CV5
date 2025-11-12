<?php
include_once("../Conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $_POST["id_usuario"];
    $fecha = $_POST["fecha_donacion"];
    $cantidad = $_POST["cantidad_donada"];
    $semillas = $_POST["semillas"] ?? [];

    try {
        $conn = CConexion::ConexionBD();
        $conn->beginTransaction();

        // Insertar la donación
        $sql = "INSERT INTO donacion (id_usuario, fecha_donacion, cantidad_donada)
                VALUES (:id_usuario, :fecha_donacion, :cantidad_donada)
                RETURNING id_donacion";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_usuario);
        $stmt->bindParam(":fecha_donacion", $fecha);
        $stmt->bindParam(":cantidad_donada", $cantidad);
        $stmt->execute();

        $id_donacion = $stmt->fetchColumn();

        // Insertar las semillas asociadas (si hay)
        if (!empty($semillas)) {
            $sql2 = "INSERT INTO donacion_semilla (id_donacion, id_semilla)
                     VALUES (:id_donacion, :id_semilla)";
            $stmt2 = $conn->prepare($sql2);

            foreach ($semillas as $id_semilla) {
                $stmt2->bindParam(":id_donacion", $id_donacion);
                $stmt2->bindParam(":id_semilla", $id_semilla);
                $stmt2->execute();
            }
        }

        $conn->commit();

        echo "✅ Donación registrada correctamente junto con las semillas.";
        echo '<br><a href="../vistas/form_donacion.php">⬅️ Volver al formulario</a>';

    } catch (PDOException $e) {
        $conn->rollBack();
        echo "❌ Error al registrar la donación: " . $e->getMessage();
    }
}
?>
