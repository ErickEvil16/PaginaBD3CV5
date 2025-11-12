<?php
// crud/actualizar_donacion.php
session_start();
include_once("../Conexion.php");

$conn = null;
try {
    $conn = CConexion::ConexionBD();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Error de conexión: " . htmlspecialchars($e->getMessage()));
}

// Si es GET -> mostrar formulario rellenado
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id_donacion']) ? (int)$_GET['id_donacion'] : 0;
    if ($id <= 0) {
        echo "ID inválido.";
        exit;
    }

    try {
        // Obtener donación
        $sql = "SELECT id_donacion, id_usuario, fecha_donacion, cantidad_donada
                FROM donacion WHERE id_donacion = :id LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $don = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$don) {
            echo "Donación no encontrada.";
            exit;
        }

        // Obtener semillas asociadas a esta donación
        $stmt2 = $conn->prepare("SELECT id_semilla FROM donacion_semilla WHERE id_donacion = :id");
        $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt2->execute();
        $semillas_sel = $stmt2->fetchAll(PDO::FETCH_COLUMN, 0);

        // Obtener lista de usuarios y semillas para selects
        $usuarios = $conn->query("SELECT id_usuario, nombre FROM usuario ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
        $semillas = $conn->query("SELECT id_semilla, nombre_planta FROM semilla ORDER BY nombre_planta")->fetchAll(PDO::FETCH_ASSOC);

        // CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    } catch (Exception $e) {
        die("Error al obtener datos: " . htmlspecialchars($e->getMessage()));
    }

    // Mostrar formulario
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../vistas/styleForm.css">
    <title>Editar Donación #<?= htmlspecialchars($don['id_donacion']) ?></title>
    </head>
    <body>
    <h2>Editar Donación #<?= htmlspecialchars($don['id_donacion']) ?></h2>

    <form action="actualizar_donacion.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input type="hidden" name="id_donacion" value="<?= (int)$don['id_donacion'] ?>">

        <label>Usuario donante:</label><br>
        <select name="id_usuario" required>
            <option value="">-- Selecciona un usuario --</option>
            <?php foreach ($usuarios as $u): ?>
                <option value="<?= (int)$u['id_usuario'] ?>" <?= ((int)$u['id_usuario'] === (int)$don['id_usuario']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Fecha de donación:</label><br>
        <input type="date" name="fecha_donacion" value="<?= htmlspecialchars($don['fecha_donacion'] ?? '') ?>"><br><br>

        <label>Cantidad donada:</label><br>
        <input type="number" name="cantidad_donada" step="0.01" min="0" value="<?= htmlspecialchars($don['cantidad_donada'] ?? '') ?>"><br><br>

        <label>Semillas donadas (mantén Ctrl/Cmd para seleccionar múltiples):</label><br>
        <select name="semillas[]" multiple size="6">
            <?php foreach ($semillas as $s): 
                $sel = in_array($s['id_semilla'], $semillas_sel) ? 'selected' : '';
            ?>
                <option value="<?= (int)$s['id_semilla'] ?>" <?= $sel ?>><?= htmlspecialchars($s['nombre_planta']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Guardar cambios</button>
        <a href="../crud/consultar_donacion.php"><button type="button">Cancelar</button></a>
    </form>
    </body>
    </html>
    <?php
    exit;
}

// Si es POST -> procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF
    $csrf = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || $csrf !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo "Solicitud inválida (CSRF).";
        exit;
    }

    $id = isset($_POST['id_donacion']) ? (int)$_POST['id_donacion'] : 0;
    $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
    $fecha = $_POST['fecha_donacion'] ?? null;
    $cantidad = $_POST['cantidad_donada'] ?? null;
    $semillas = $_POST['semillas'] ?? []; // array de ids

    // Validaciones básicas
    $errors = [];
    if ($id <= 0) $errors[] = "ID inválido.";
    if ($id_usuario <= 0) $errors[] = "Usuario inválido.";
    if ($cantidad !== null && $cantidad !== '' && !is_numeric($cantidad)) $errors[] = "Cantidad inválida.";

    if (!empty($errors)) {
        http_response_code(400);
        foreach ($errors as $err) echo htmlspecialchars($err) . "<br>";
        echo '<br><a href="../crud/consultar_donacion.php">Volver</a>';
        exit;
    }

    try {
        $conn->beginTransaction();

        // Verificar que el usuario exista
        $chkU = $conn->prepare("SELECT 1 FROM usuario WHERE id_usuario = :id LIMIT 1");
        $chkU->bindValue(':id', $id_usuario, PDO::PARAM_INT);
        $chkU->execute();
        if ($chkU->fetchColumn() === false) throw new Exception("Usuario no existe.");

        // Actualizar tabla donacion
        $upd = $conn->prepare("UPDATE donacion
                               SET id_usuario = :id_usuario,
                                   fecha_donacion = :fecha,
                                   cantidad_donada = :cantidad
                               WHERE id_donacion = :id");
        $upd->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        if (empty($fecha)) {
            $upd->bindValue(':fecha', null, PDO::PARAM_NULL);
        } else {
            $upd->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        }
        if ($cantidad === null || $cantidad === '') {
            $upd->bindValue(':cantidad', null, PDO::PARAM_NULL);
        } else {
            $upd->bindValue(':cantidad', $cantidad);
        }
        $upd->bindValue(':id', $id, PDO::PARAM_INT);
        $upd->execute();

        // Reemplazar filas en donacion_semilla: primero eliminar existentes
        $delRel = $conn->prepare("DELETE FROM donacion_semilla WHERE id_donacion = :id");
        $delRel->bindValue(':id', $id, PDO::PARAM_INT);
        $delRel->execute();

        // Insertar nuevas relaciones (si hay)
        if (!empty($semillas) && is_array($semillas)) {
            $ins = $conn->prepare("INSERT INTO donacion_semilla (id_donacion, id_semilla) VALUES (:id_donacion, :id_semilla)");
            foreach ($semillas as $s_id) {
                $s_int = (int)$s_id;
                if ($s_int <= 0) continue;
                $ins->bindValue(':id_donacion', $id, PDO::PARAM_INT);
                $ins->bindValue(':id_semilla', $s_int, PDO::PARAM_INT);
                $ins->execute();
            }
        }

        $conn->commit();

        echo "✅ Donación actualizada correctamente.";
        echo '<br><a href="../crud/consultar_donacion.php">Volver a Donaciones</a>';
    } catch (Exception $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        error_log("Error actualizar donacion: " . $e->getMessage());
        http_response_code(500);
        echo "Ocurrió un error al actualizar la donación.";
        echo '<br><a href="../crud/consultar_donacion.php">Volver a Donaciones</a>';
    }
    exit;
}
?>
