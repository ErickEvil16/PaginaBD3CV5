<?php
// crud/eliminar_evento.php
session_start();
include_once("../Conexion.php");

// Aceptar solo POST
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

$id = isset($_POST['id_evento']) ? (int)$_POST['id_evento'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo "ID de evento inválido.";
    exit;
}

try {
    $conn = CConexion::ConexionBD();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si hay participaciones asociadas, eliminarlas primero
    $delPart = $conn->prepare("DELETE FROM participacion_evento WHERE id_evento = :id");
    $delPart->bindValue(':id', $id, PDO::PARAM_INT);
    $delPart->execute();

    // Eliminar el evento
    $del = $conn->prepare("DELETE FROM evento WHERE id_evento = :id");
    $del->bindValue(':id', $id, PDO::PARAM_INT);
    $del->execute();

    echo "✅ Evento eliminado correctamente.";
    echo '<br><a href="../crud/consultar_evento.php">Volver a Eventos</a>';
} catch (Exception $e) {
    error_log("Error eliminar evento: " . $e->getMessage());
    http_response_code(500);
    echo "❌ Ocurrió un error al eliminar el evento.";
    echo '<br><a href="../crud/consultar_evento.php">Volver</a>';
}
?>
