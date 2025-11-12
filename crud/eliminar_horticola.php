<?php
session_start();
include_once("../Conexion.php");

// Solo POST permitido
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido.";
    exit;
}

// Validar token CSRF
$csrf = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || $csrf !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo "Solicitud inválida (CSRF).";
    exit;
}

// Validar ID
$id = isset($_POST['id_semilla']) ? (int)$_POST['id_semilla'] : 0;
if ($id <= 0) {
    echo "ID inválido.";
    exit;
}

try {
    $conn = CConexion::ConexionBD();

    // Eliminar primero el registro en horticola
    $stmt1 = $conn->prepare("DELETE FROM horticola WHERE id_semilla = :id");
    $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();

    // Luego eliminar la semilla asociada
    $stmt2 = $conn->prepare("DELETE FROM semilla WHERE id_semilla = :id");
    $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();

    echo "✅ Semilla hortícola eliminada correctamente.";
    echo '<br><a href="consultar_horticola.php">Volver</a>';

} catch (PDOException $e) {
    error_log("Error al eliminar semilla hortícola: " . $e->getMessage());
    echo "❌ No se pudo eliminar la semilla hortícola.";
    echo '<br><a href="consultar_horticola.php">Volver</a>';
}
?>
