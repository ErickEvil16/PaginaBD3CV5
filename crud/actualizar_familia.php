<?php
// crud/actualizar_familia.php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage()));
}

// Si es GET → mostrar formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id_familia']) ? (int)$_GET['id_familia'] : 0;
    if ($id <= 0) die("ID de familia inválido.");

    $stmt = $conn->prepare("SELECT * FROM familia WHERE id_familia = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $familia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$familia) die("Familia no encontrada.");

    // Generar token CSRF
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Actualizar Familia</title>
        <link rel="stylesheet" href="../vistas/styleForm.css">
    </head>
    <body>
        <h2>✏️ Editar Familia #<?= htmlspecialchars($familia['id_familia']) ?></h2>

        <form action="actualizar_familia.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="id_familia" value="<?= (int)$familia['id_familia'] ?>">

            <label>Número de Integrantes:</label><br>
            <input type="number" name="numero_integrantes" required min="1" 
                   value="<?= htmlspecialchars($familia['numero_integrantes']) ?>"><br><br>

            <label>Ubicación:</label><br>
            <input type="text" name="ubicacion" value="<?= htmlspecialchars($familia['ubicacion'] ?? '') ?>"><br><br>

            <button type="submit">Guardar Cambios</button>
            <a href="consultar_familia.php"><button type="button">Cancelar</button></a>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Si es POST → procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || $csrf !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die("Solicitud inválida (CSRF).");
    }

    $id = (int)($_POST['id_familia'] ?? 0);
    $num = (int)($_POST['numero_integrantes'] ?? 0);
    $ubic = trim($_POST['ubicacion'] ?? '');

    if ($id <= 0 || $num <= 0) {
        die("Datos inválidos.");
    }

    try {
        $stmt = $conn->prepare("
            UPDATE familia 
            SET numero_integrantes = :num, ubicacion = :ubic 
            WHERE id_familia = :id
        ");
        $stmt->bindValue(':num', $num, PDO::PARAM_INT);
        $stmt->bindValue(':ubic', $ubic !== '' ? $ubic : null, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "✅ Familia actualizada correctamente.";
        echo '<br><a href="consultar_familia.php">Volver</a>';

    } catch (PDOException $e) {
        error_log("Error al actualizar familia: " . $e->getMessage());
        echo "❌ No se pudo actualizar la familia.";
        echo '<br><a href="consultar_familia.php">Volver</a>';
    }
}
?>
