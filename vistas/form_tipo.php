<!DOCTYPE html>
<html lang="es">
<head>
<link rel="stylesheet" href="styleForm.css">
<meta charset="UTF-8">
<title>Registrar Tipo</title>
</head>
<body>

<h2>Registrar un Nuevo Tipo</h2>

<form action="../crud/insertar_tipo.php" method="POST">
    <label>Nombre del Tipo:</label><br>
    <input type="text" name="tipo" maxlength="50" required><br><br>

    <button type="submit">Guardar</button>
</form>

<br>
<a href="../crud/consultar_tipo.php"><button>📋 Ver Tipos Registrados</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
