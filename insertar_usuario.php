<?php
// insertar_usuario.php

$config = require __DIR__ . '/config.local.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Método no permitido";
    exit;
}

// Validar entradas
$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

if (empty($usuario) || empty($contrasena)) {
    echo "Usuario o contraseña vacíos";
    exit;
}

// Cargar credenciales desde config.local.php
$host = $config['DB_HOST'];
$dbname = $config['DB_NAME'];
$user = $config['DB_USER'];
$password = $config['DB_PASSWORD'];
$port = $config['DB_PORT'];

// Conexión a PostgreSQL
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    echo "Error al conectar a la base de datos";
    exit;
}

// Insert usando parámetros (seguro)
$sql = "INSERT INTO usuariossqli (usuario, contrasena) VALUES ($1, $2)";
$result = pg_query_params($dbconn, $sql, array($usuario, $contrasena));

if ($result) {
    echo "✅ Usuario insertado correctamente";
} else {
    echo "❌ Error al insertar usuario: " . pg_last_error($dbconn);
}

pg_close($dbconn);
?>
