<?php
// crud/eliminar_donacion.php
session_start();
include_once("../Conexion.php");

// Aceptar solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido.";
    exit;
}

// CSRF
$csrf = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || $csrf !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo "Solicitud inválida (CSRF).";
    exit;
}

$id = isset($_POST['id_donacion']) ? (int)$_POST['id_donacion'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo "ID de donación inválido.";
    exit;
}

try {
    $conn = CConexion::ConexionBD();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();

    // Eliminar relaciones en donacion_semilla (si existen)
    $delRel = $conn->prepare("DELETE FROM donacion_semilla WHERE id_donacion = :id");
    $delRel->bindValue(':id', $id, PDO::PARAM_INT);
    $delRel->execute();

    // Eliminar la donación
    $del = $conn->prepare("DELETE FROM donacion WHERE id_donacion = :id");
    $del->bindValue(':id', $id, PDO::PARAM_INT);
    $del->execute();

    $conn->commit();

    echo "✅ Donación eliminada correctamente.";
    echo '<br><a href="../crud/consultar_donacion.php">Volver a Donaciones</a>';
} catch (Exception $e) {
    if ($conn && $conn->inTransaction()) $conn->rollBack();
    error_log("Error eliminar donacion: " . $e->getMessage());
    http_response_code(500);
    echo "Ocurrió un error al eliminar la donación.";
    echo '<br><a href="../crud/consultar_donacion.php">Volver a Donaciones</a>';
}
?>
