<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Familia</title>
<link rel="stylesheet" href="styleForm.css">
</head>
<body>

<h2>Registrar Nueva Familia</h2>

<form action="../crud/insertar_familia.php" method="POST">
    <label>Número de Integrantes:</label>
    <input type="number" name="numero_integrantes" min="1" required>

    <label>Ubicación:</label>
    <input type="text" name="ubicacion" maxlength="150" required>

    <button type="submit">Guardar Familia</button>
</form>

<br>
<a href="../crud/consultar_familia.php"><button>📋 Consultar Familias</button></a>
<a href="../index.php"><button >Volver al inicio</button></a>

</body>
</html>
