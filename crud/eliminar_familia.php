<?php
// crud/eliminar_familia.php
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
$id = isset($_POST['id_familia']) ? (int)$_POST['id_familia'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo "ID inválido.";
    exit;
}

try {
    $conn = CConexion::ConexionBD();

    // Si hay usuarios asociados, se actualizan para quitarles la referencia a la familia
    $stmt1 = $conn->prepare("UPDATE usuario SET id_familia = NULL WHERE id_familia = :id");
    $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();

    // Eliminar la familia
    $stmt2 = $conn->prepare("DELETE FROM familia WHERE id_familia = :id");
    $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();

    echo "✅ Familia eliminada correctamente.";
    echo '<br><a href="consultar_familia.php">Volver</a>';

} catch (PDOException $e) {
    error_log("Error al eliminar familia: " . $e->getMessage());
    echo "❌ No se pudo eliminar la familia.";
    echo '<br><a href="consultar_familia.php">Volver</a>';
}
?>
