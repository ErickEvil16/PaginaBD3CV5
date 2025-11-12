<?php
header("Access-Control-Allow-Origin: *");

class CConexion {
    public static function ConexionBD() {
        // Datos de conexión
        $host = "pg-2bdbe7d3-erickgamer15yt-5f14.j.aivencloud.com";
        $dbname = "defaultdb";
        $username = "avnadmin";
        $password = "AVNS_RsCCFQnu5hSG4ioe3ES";
        $port = "12340";

        $conn = null;

        try {
            // 🔹 Conexión PDO a PostgreSQL con codificación UTF-8
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;options='--client_encoding=UTF8'";
            $conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);

            // 🔹 Asegurar codificación UTF-8
            $conn->exec("SET CLIENT_ENCODING TO 'UTF8'");

            // echo "✅ Conexión correcta a la BD"; // (opcional, puede omitirse en producción)
        } catch (PDOException $exp) {
            die("❌ Error al conectar con la BD: " . htmlspecialchars($exp->getMessage()));
        }

        return $conn;
    }
}
?>
