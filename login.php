<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Método no permitido";
    exit;
}

$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';

if ($usuario === '' || $contrasena === '') {
    echo "Usuario o contraseña vacíos";
    exit;
}
    $host = "pg-2bdbe7d3-erickgamer15yt-5f14.j.aivencloud.com";
    $dbname = "defaultdb";
    $user = "avnadmin";
    $password = "AVNS_RsCCFQnu5hSG4ioe3ES";
    $port = "12340";

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$dbconn = pg_connect($conn_string);


if (!$dbconn) {
    echo "Error al conectar a la BD";
    exit;
}

//Linea de SQL donde concatena los datos a buscar
$sql = "SELECT id, usuario, contrasena FROM usuariossqli 
        WHERE usuario = '" . $usuario . "' AND contrasena = '" . $contrasena . "';";

//Busqueda del resultado de la busqueda
$result = pg_query($dbconn, $sql);

if (!$result) {
    echo "Error en la consulta: " . pg_last_error($dbconn);
    pg_close($dbconn);
    exit;
}

if (pg_num_rows($result) === 0) {
    echo "Credenciales incorrectas (0 coincidencias)";
    pg_free_result($result);
    pg_close($dbconn);
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Panel de usuario (pruebas)</title>
    <style>
        table { border-collapse: collapse; margin-top:20px; width: 100%; max-width: 800px; }
        th, td { border:1px solid #333; padding:8px 12px; text-align: left; }
        thead { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Resultados de búsqueda</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Contraseña (texto plano)</th>
            </tr>
        </thead>
        <tbody>
            <?php

            //Impresión de los usuarios con ese mismo nombre y contraseña

            while ($row = pg_fetch_assoc($result)) {

                $id = (int)$row['id'];
                $usuario_row = $row['usuario'];
                $contrasena_row =$row['contrasena'];

                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$usuario_row}</td>";
                echo "<td>{$contrasena_row}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
<?php
// Liberar y cerrar
pg_free_result($result);
pg_close($dbconn);
?>


a