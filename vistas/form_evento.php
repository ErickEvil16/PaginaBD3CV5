<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Evento</title>
</head>
<body>
<h2>Registro de Evento</h2>

<form action="../crud/insertar_evento.php" method="POST">
    <label>Nombre del evento:</label>
    <input type="text" name="nombre_evento" required><br><br>

    <label>Tema:</label>
    <input type="text" name="tema"><br><br>

    <label>Fecha del evento:</label>
    <input type="date" name="fecha_evento"><br><br>

    <button type="submit">Guardar</button>
</form>

<br>
<a href="../crud/consultar_evento.php"><button>📋 Ver eventos registrados</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
