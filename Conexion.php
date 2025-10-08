<?php
class CConexion {
    function Conexion BD(){
        $host = "pg-2bdbe7d3-erickgamer15yt-5f14.j.aivencloud.com";
        $dbname = "12340";
        $username = "avnadmin";
        $pasword = "AVNS_RsCCFQnu5hSG4ioe3ES";

        try {
            $conn = new PDO("pgsql:host = $host; dbname = $dbname; $username; $pasword");
            echo "Conexion Correcta a la BD";
        } catch (PDOException $exp) {
            echo ("Error al conectar con la BD, " $exp);
        }
        return $conn
    }
}

?>