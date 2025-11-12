<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Organización</title>
<link rel="stylesheet" href="styleForm.css">
</head>
<body>

<h2>Registrar Nueva Organización</h2>

<form action="../crud/insertar_organizacion.php" method="POST">
    <label>Nombre del Instituto:</label>
    <input type="text" name="nombre_instituto" maxlength="150" required>

    <label>Responsable:</label>
    <input type="text" name="responsable" maxlength="100" required>

    <button type="submit">Guardar Organización</button>
</form>

<br>
<a href="../crud/consultar_organizacion.php"><button>📋 Consultar Organizaciones</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
