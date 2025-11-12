<?php
header("Access-Control-Allow-Origin: *");

/**
 * Conexion.php
 * Clase CConexion usando PDO y leyendo credenciales desde config.local.php
 */

$config = require __DIR__ . '/config.local.php';

class CConexion {
    public static function ConexionBD() {
        // Cargar configuración
        $cfg = require __DIR__ . '/config.local.php';

        $host = $cfg['DB_HOST'] ?? '';
        $dbname = $cfg['DB_NAME'] ?? '';
        $username = $cfg['DB_USER'] ?? '';
        $password = $cfg['DB_PASSWORD'] ?? '';
        $port = $cfg['DB_PORT'] ?? '';

        $conn = null;

        try {
            // Conexión PDO a PostgreSQL con codificación UTF-8
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;options='--client_encoding=UTF8'";
            $conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);

            // Asegurar codificación UTF-8
            $conn->exec("SET CLIENT_ENCODING TO 'UTF8'");

        } catch (PDOException $exp) {
            die("❌ Error al conectar con la BD: " . htmlspecialchars($exp->getMessage()));
        }

        return $conn;
    }
}
?>
