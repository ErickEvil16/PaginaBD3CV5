<?php
class CConexion {
    public static function ConexionBD() {
        // Leer variables del archivo .env
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');

        $conn = null;
        try {
            $conn = new PDO("pgsql:host=$host;dbname=$dbname;user=$username;password=$password");
            echo "Conexión correcta a la BD";
        } catch (PDOException $exp) {
            echo "Error al conectar con la BD: " . $exp->getMessage();
        }

        return $conn;
    }
}
?>
