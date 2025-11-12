<?php
// crud/actualizar_usuario.php
include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();

    // Mostrar formulario: recibimos id_usuario por POST (desde consultar_usuario.php)
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_usuario"]) && !isset($_POST["nombre"])) {
        $id_usuario = (int)$_POST["id_usuario"];

        // Obtener datos actuales del usuario
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
        $stmt->bindValue(":id", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            die("⚠️ Usuario no encontrado.");
        }

        // Obtener todas las familias disponibles
        $familias = $conn->query("SELECT id_familia, ubicacion FROM familia ORDER BY id_familia")->fetchAll(PDO::FETCH_ASSOC);

        // Obtener todas las organizaciones disponibles
        $organizaciones = $conn->query("SELECT id_organizacion, nombre_instituto FROM organizacion ORDER BY id_organizacion")->fetchAll(PDO::FETCH_ASSOC);

    // Procesar actualización: recibimos el formulario con "nombre"
    } elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["nombre"])) {
        $id_usuario      = (int)($_POST["id_usuario"] ?? 0);
        $nombre          = trim($_POST["nombre"] ?? '');
        $correo          = trim($_POST["correo"] ?? '');
        $telefono        = trim($_POST["telefono"] ?? '');
        $id_familia      = isset($_POST["id_familia"]) && $_POST["id_familia"] !== '' ? (int)$_POST["id_familia"] : null;
        $id_organizacion = isset($_POST["id_organizacion"]) && $_POST["id_organizacion"] !== '' ? (int)$_POST["id_organizacion"] : null;

        // Validaciones mínimas
        if ($id_usuario <= 0 || $nombre === '' || $correo === '') {
            die("❌ Datos inválidos. Nombre y correo son obligatorios.");
        }

        try {
            $sql = "UPDATE usuario 
                    SET nombre = :nombre, correo = :correo, telefono = :telefono, 
                        id_familia = :id_familia, id_organizacion = :id_organizacion
                    WHERE id_usuario = :id_usuario";
            $stmt = $conn->prepare($sql);

            // valores sencillos con bindValue
            $stmt->bindValue(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindValue(":correo", $correo, PDO::PARAM_STR);
            // telefono puede ser NULL
            if ($telefono === '') {
                $stmt->bindValue(":telefono", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":telefono", $telefono, PDO::PARAM_STR);
            }

            // id_familia puede ser NULL o entero
            if ($id_familia === null) {
                $stmt->bindValue(":id_familia", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":id_familia", $id_familia, PDO::PARAM_INT);
            }

            // id_organizacion puede ser NULL o entero
            if ($id_organizacion === null) {
                $stmt->bindValue(":id_organizacion", null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(":id_organizacion", $id_organizacion, PDO::PARAM_INT);
            }

            $stmt->bindValue(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            // Redirigir de vuelta a la lista
            header("Location: consultar_usuario.php");
            exit;
        } catch (PDOException $e) {
            die("❌ Error al actualizar el usuario: " . htmlspecialchars($e->getMessage()));
        }
    } else {
        die("⚠️ Solicitud no válida.");
    }
} catch (PDOException $e) {
    die("❌ Error: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Actualizar Usuario</title>
<link rel="stylesheet" href="../vistas/styleForm.css">
</head>
<body>
<h2>Actualizar Usuario</h2>

<form method="POST" action="actualizar_usuario.php">
    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>">

    <label for="nombre">Nombre:</label>
    <input id="nombre" type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

    <label for="correo">Correo:</label>
    <input id="correo" type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

    <label for="telefono">Teléfono:</label>
    <input id="telefono" type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">

    <label for="id_familia">Familia:</label>
    <select id="id_familia" name="id_familia">
        <option value="">— Sin familia —</option>
        <?php foreach ($familias as $f): ?>
            <option value="<?= (int)$f['id_familia'] ?>" <?= ($usuario['id_familia'] == $f['id_familia']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($f['ubicacion']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="id_organizacion">Organización:</label>
    <select id="id_organizacion" name="id_organizacion">
        <option value="">— Sin organización —</option>
        <?php foreach ($organizaciones as $org): ?>
            <option value="<?= (int)$org['id_organizacion'] ?>" <?= ($usuario['id_organizacion'] == $org['id_organizacion']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($org['nombre_instituto']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Guardar Cambios</button>
</form>

<br>
<a href="consultar_usuario.php"><button>Cancelar</button></a>
</body>
</html>
