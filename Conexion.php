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
            // Conexión con PDO (forma recomendada)
            $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "✅ Conexión correcta a la BD";
        } catch (PDOException $exp) {
            echo "Error al conectar con la BD: " . $exp->getMessage();
        }

        return $conn;
    }
}
?>
