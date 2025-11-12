<?php
// crud/actualizar_evento.php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Error al conectar a la base de datos: " . htmlspecialchars($e->getMessage()));
}

// Si es GET → mostrar formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id_evento']) ? (int)$_GET['id_evento'] : 0;
    if ($id <= 0) {
        die("ID de evento inválido.");
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM evento WHERE id_evento = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$evento) {
            die("Evento no encontrado.");
        }

        // Generar token CSRF si no existe
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

    } catch (Exception $e) {
        die("Error al obtener evento: " . htmlspecialchars($e->getMessage()));
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Evento</title>
        <link rel="stylesheet" href="../vistas/styleForm.css">
    </head>
    <body>
        <h2>✏️ Editar Evento #<?= htmlspecialchars($evento['id_evento']) ?></h2>

        <form action="actualizar_evento.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="id_evento" value="<?= (int)$evento['id_evento'] ?>">

            <label>Nombre del Evento:</label><br>
            <input type="text" name="nombre_evento" required value="<?= htmlspecialchars($evento['nombre_evento']) ?>"><br><br>

            <label>Tema:</label><br>
            <input type="text" name="tema" value="<?= htmlspecialchars($evento['tema'] ?? '') ?>"><br><br>

            <label>Fecha del Evento:</label><br>
            <input type="date" name="fecha_evento" value="<?= htmlspecialchars($evento['fecha_evento'] ?? '') ?>"><br><br>

            <button type="submit">Guardar Cambios</button>
            <a href="../crud/consultar_evento.php"><button type="button">Cancelar</button></a>
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

    $id = isset($_POST['id_evento']) ? (int)$_POST['id_evento'] : 0;
    $nombre = trim($_POST['nombre_evento'] ?? '');
    $tema = trim($_POST['tema'] ?? '');
    $fecha = $_POST['fecha_evento'] ?? null;

    if ($id <= 0 || $nombre === '') {
        die("Datos inválidos.");
    }

    try {
        $sql = "UPDATE evento
                SET nombre_evento = :nombre, tema = :tema, fecha_evento = :fecha
                WHERE id_evento = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':tema', $tema !== '' ? $tema : null, PDO::PARAM_STR);
        if ($fecha) {
            $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':fecha', null, PDO::PARAM_NULL);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo "✅ Evento actualizado correctamente.";
        echo '<br><a href="../crud/consultar_evento.php">Volver a Eventos</a>';
    } catch (Exception $e) {
        error_log("Error actualizar evento: " . $e->getMessage());
        echo "❌ Ocurrió un error al actualizar el evento.";
        echo '<br><a href="../crud/consultar_evento.php">Volver</a>';
    }
}
?>
