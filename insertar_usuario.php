<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Metodo no permitido";
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

$sql = "INSERT INTO usuariossqli (usuario, contrasena) VALUES ('" . $usuario . "', '" . $contrasena . "');";

$result = pg_query($dbconn, $sql);

if ($result) {
    echo "Usuario insertado";
} else {
    echo "Error al insertar usuario: " . pg_last_error($dbconn);
}

pg_close($dbconn);
?>
