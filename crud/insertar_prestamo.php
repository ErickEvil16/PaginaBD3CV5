<?php
// crud/insertar_prestamo.php
session_start();
include_once("../Conexion.php");
header('Content-Type: text/html; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
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

// Recoger y validar entradas
$id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
$id_semilla = isset($_POST['id_semilla']) ? (int)$_POST['id_semilla'] : 0;
$fecha_prestamo = $_POST['fecha_prestamo'] ?? '';
$fecha_devolucion = $_POST['fecha_devolucion'] ?? null;
$cantidad = $_POST['cantidad'] ?? '';
$estado = $_POST['estado'] ?? 'Pendiente';

// Validaciones básicas
$errors = [];
if ($id_usuario <= 0) $errors[] = "Usuario inválido.";
if ($id_semilla <= 0) $errors[] = "Semilla inválida.";
if (empty($fecha_prestamo)) $errors[] = "Fecha de préstamo requerida.";
if ($cantidad === '' || !is_numeric($cantidad) || $cantidad <= 0) $errors[] = "Cantidad inválida.";

$MAX_ESTADO_LEN = 50;
if (mb_strlen($estado) > $MAX_ESTADO_LEN) $errors[] = "Estado demasiado largo.";

if (!empty($errors)) {
    http_response_code(400);
    foreach ($errors as $err) {
        echo htmlspecialchars($err) . "<br>";
    }
    echo '<br><a href="../vistas/form_prestamo.php">Volver</a>';
    exit;
}

try {
    $conn = CConexion::ConexionBD();
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: comprobar que usuario y semilla existen (recomendado)
    $chkU = $conn->prepare("SELECT 1 FROM usuario WHERE id_usuario = :id LIMIT 1");
    $chkU->bindValue(':id', $id_usuario, PDO::PARAM_INT);
    $chkU->execute();
    if ($chkU->fetchColumn() === false) {
        throw new Exception("El usuario seleccionado no existe.");
    }

    $chkS = $conn->prepare("SELECT 1 FROM semilla WHERE id_semilla = :id LIMIT 1");
    $chkS->bindValue(':id', $id_semilla, PDO::PARAM_INT);
    $chkS->execute();
    if ($chkS->fetchColumn() === false) {
        throw new Exception("La semilla seleccionada no existe.");
    }

    $sql = "INSERT INTO prestamo (id_usuario, id_semilla, fecha_prestamo, fecha_devolucion, cantidad, estado)
            VALUES (:id_usuario, :id_semilla, :fecha_prestamo, :fecha_devolucion, :cantidad, :estado)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->bindValue(':id_semilla', $id_semilla, PDO::PARAM_INT);
    $stmt->bindValue(':fecha_prestamo', $fecha_prestamo, PDO::PARAM_STR);

    if (empty($fecha_devolucion)) {
        $stmt->bindValue(':fecha_devolucion', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':fecha_devolucion', $fecha_devolucion, PDO::PARAM_STR);
    }

    // Bind cantidad como string/decimal (PG procesa)
    $stmt->bindValue(':cantidad', $cantidad);
    $stmt->bindValue(':estado', $estado, PDO::PARAM_STR);

    $stmt->execute();

    echo "✅ Préstamo registrado correctamente.";
    echo '<br><a href="../vistas/form_prestamo.php">⬅️ Volver al formulario</a>';
    echo ' | <a href="../crud/consultar_prestamo.php">Ver préstamos</a>';
} catch (Exception $e) {
    error_log("Error insertar prestamo: " . $e->getMessage());
    http_response_code(500);
    echo "Ocurrió un error al registrar el préstamo.";
    echo '<br><a href="../vistas/form_prestamo.php">Volver</a>';
}
?>
