<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include_once("../Conexion.php");

try {
    $conn = CConexion::ConexionBD();
    $sql = "SELECT * FROM evento ORDER BY id_evento DESC";
    $stmt = $conn->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Eventos Registrados</title>
<link rel="stylesheet" href="styleConsultas.css">
<style>
    table {
        border-collapse: collapse;
        width: 90%;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #4CAF50;
        color: white;
    }
    .acciones button {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 2px;
    }
    .editar {
        background-color: #2196F3;
        color: white;
    }
    .eliminar {
        background-color: #f44336;
        color: white;
    }
</style>
</head>
<body>

<h2>Eventos Registrados</h2>
<a href="../vistas/form_evento.php">➕ Nuevo Evento</a><br><br>

<table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Tema</th>
        <th>Fecha</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($eventos as $e): ?>
    <tr>
        <td><?= htmlspecialchars($e["id_evento"]) ?></td>
        <td><?= htmlspecialchars($e["nombre_evento"]) ?></td>
        <td><?= htmlspecialchars($e["tema"]) ?></td>
        <td><?= htmlspecialchars($e["fecha_evento"] ?? "—") ?></td>
        <td class="acciones">
            <!-- Botón Editar -->
            <form action="actualizar_evento.php" method="get" style="display:inline;">
                <input type="hidden" name="id_evento" value="<?= htmlspecialchars($e["id_evento"]) ?>">
                <button type="submit" class="editar">✏️ Editar</button>
            </form>

            <!-- Botón Eliminar -->
            <form action="eliminar_evento.php" method="post" style="display:inline;" 
                  onsubmit="return confirm('¿Seguro que deseas eliminar este evento?');">
                <input type="hidden" name="id_evento" value="<?= htmlspecialchars($e["id_evento"]) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit" class="eliminar">🗑️ Eliminar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<br>
<a href="../index.php"><button>Volver al inicio</button></a>

</body>
</html>
