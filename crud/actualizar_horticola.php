<?php
session_start();
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();
} catch (PDOException $e) {
    die("Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage()));
}

// Mostrar formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id_semilla']) ? (int)$_GET['id_semilla'] : 0;
    if ($id <= 0) die("ID inválido.");

    $stmt = $conn->prepare("
        SELECT s.id_semilla, s.nombre_planta, s.variedad, s.estado, s.id_tipo,
               h.temporada_siembra, h.clima
        FROM semilla s
        INNER JOIN horticola h ON s.id_semilla = h.id_semilla
        WHERE s.id_semilla = :id
    ");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $h = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$h) die("Semilla no encontrada.");

    // Obtener tipos disponibles
    $tipos = $conn->query("SELECT id_tipo, tipo FROM tipo ORDER BY tipo")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Actualizar Semilla Hortícola</title>
        <link rel="stylesheet" href="../vistas/styleForm.css">
    </head>
    <body>
        <h2>✏️ Editar Semilla Hortícola #<?= htmlspecialchars($h['id_semilla']) ?></h2>

        <form action="actualizar_horticola.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="id_semilla" value="<?= (int)$h['id_semilla'] ?>">

            <label>Nombre de la planta:</label><br>
            <input type="text" name="nombre_planta" required value="<?= htmlspecialchars($h['nombre_planta']) ?>"><br><br>

            <label>Variedad:</label><br>
            <input type="text" name="variedad" value="<?= htmlspecialchars($h['variedad']) ?>"><br><br>

            <label>Estado:</label><br>
            <input type="text" name="estado" value="<?= htmlspecialchars($h['estado']) ?>"><br><br>

            <label>Tipo:</label><br>
            <select name="id_tipo">
                <option value="">— Selecciona —</option>
                <?php foreach ($tipos as $t): ?>
                    <option value="<?= (int)$t['id_tipo'] ?>" 
                        <?= ($h['id_tipo'] == $t['id_tipo']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['tipo']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Temporada de siembra:</label><br>
            <input type="text" name="temporada_siembra" value="<?= htmlspecialchars($h['temporada_siembra'] ?? '') ?>"><br><br>

            <label>Clima:</label><br>
            <input type="text" name="clima" value="<?= htmlspecialchars($h['clima'] ?? '') ?>"><br><br>

            <button type="submit">Guardar Cambios</button>
            <a href="consultar_horticola.php"><button type="button">Cancelar</button></a>
        </form>
    </body>
    </html>
    <?php
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || $csrf !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die("Solicitud inválida (CSRF).");
    }

    $id = (int)($_POST['id_semilla'] ?? 0);
    $nombre = trim($_POST['nombre_planta'] ?? '');
    $variedad = trim($_POST['variedad'] ?? '');
    $estado = trim($_POST['estado'] ?? '');
    $id_tipo = $_POST['id_tipo'] !== '' ? (int)$_POST['id_tipo'] : null;
    $temporada = trim($_POST['temporada_siembra'] ?? '');
    $clima = trim($_POST['clima'] ?? '');

    if ($id <= 0 || $nombre === '') die("Datos inválidos.");

    try {
        // Actualizar tabla semilla
        $stmt1 = $conn->prepare("
            UPDATE semilla 
            SET nombre_planta = :nombre, variedad = :variedad, estado = :estado, id_tipo = :id_tipo
            WHERE id_semilla = :id
        ");
        $stmt1->bindValue(':nombre', $nombre);
        $stmt1->bindValue(':variedad', $variedad !== '' ? $variedad : null);
        $stmt1->bindValue(':estado', $estado !== '' ? $estado : null);
        $stmt1->bindValue(':id_tipo', $id_tipo, PDO::PARAM_INT);
        $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();

        // Actualizar tabla horticola
        $stmt2 = $conn->prepare("
            UPDATE horticola 
            SET temporada_siembra = :temporada, clima = :clima 
            WHERE id_semilla = :id
        ");
        $stmt2->bindValue(':temporada', $temporada !== '' ? $temporada : null);
        $stmt2->bindValue(':clima', $clima !== '' ? $clima : null);
        $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();

        echo "✅ Semilla hortícola actualizada correctamente.";
        echo '<br><a href="consultar_horticola.php">Volver</a>';

    } catch (PDOException $e) {
        error_log("Error al actualizar hortícola: " . $e->getMessage());
        echo "❌ No se pudo actualizar la semilla.";
        echo '<br><a href="consultar_horticola.php">Volver</a>';
    }
}
?>
