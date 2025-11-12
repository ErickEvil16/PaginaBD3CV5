<?php
include_once("Conexion.php");
CConexion::ConexionBD();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>🌱 Sistema de Gestión de Semillas</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f7f6;
        color: #333;
        margin: 0;
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #2f855a;
    }
    h2 {
        color: #276749;
        border-bottom: 2px solid #276749;
        padding-bottom: 5px;
    }
    .section {
        margin-bottom: 25px;
    }
    ul {
        list-style-type: none;
        padding: 0;
    }
    li {
        margin: 8px 0;
    }
    a {
        text-decoration: none;
        color: #2b6cb0;
        font-weight: bold;
    }
    a:hover {
        color: #2c5282;
    }
    .footer {
        text-align: center;
        margin-top: 40px;
        font-size: 0.9em;
        color: #555;
    }
</style>
</head>
<body>

<h1>🌿 Sistema de Gestión de Semillas 🌿</h1>

<div class="section">
    <h2>👤 Usuarios</h2>
    <ul>
        <li><a href="vistas/form_usuario.php">➕ Registrar Usuario</a></li>
        <li><a href="crud/consultar_usuario.php">📋 Consultar Usuarios</a></li>
    </ul>
</div>

<div class="section">
    <h2>🏠 Familias y Organizaciones</h2>
    <ul>
        <li><a href="vistas/form_familia.php">➕ Registrar Familia</a></li>
        <li><a href="crud/consultar_familia.php">📋 Consultar Familias</a></li>
        <li><a href="vistas/form_organizacion.php">➕ Registrar Organización</a></li>
        <li><a href="crud/consultar_organizacion.php">📋 Consultar Organizaciones</a></li>
    </ul>
</div>

<div class="section">
    <h2>💰 Donaciones y Préstamos</h2>
    <ul>
        <li><a href="vistas/form_donacion.php">➕ Registrar Donación</a></li>
        <li><a href="crud/consultar_donacion.php">📋 Consultar Donaciones</a></li>
        <li><a href="vistas/form_prestamo.php">➕ Registrar Préstamo</a></li>
        <li><a href="crud/consultar_prestamo.php">📋 Consultar Préstamos</a></li>
    </ul>
</div>

<div class="section">
    <h2>🌾 Semillas</h2>
    <ul>
        <li><a href="vistas/form_tipo.php">➕ Registrar Tipo de Semilla</a></li>
        <li><a href="crud/consultar_tipo.php">📋 Consultar Tipos de Semilla</a></li>
        <li><a href="vistas/form_semilla.php">➕ Registrar Semilla</a></li>
        <li><a href="crud/consultar_semilla.php">📋 Consultar Semillas</a></li>
        <li><a href="vistas/form_horticola.php">➕ Registrar Semilla Hortícola</a></li>
        <li><a href="crud/consultar_horticola.php">📋 Consultar Semillas Hortícolas</a></li>
        <li><a href="vistas/form_medicinal.php">➕ Registrar Semilla Medicinal</a></li>
        <li><a href="crud/consultar_medicinal.php">📋 Consultar Semillas Medicinales</a></li>
    </ul>
</div>

<div class="section">
    <h2>🎉 Eventos y Participación</h2>
    <ul>
        <li><a href="vistas/form_evento.php">➕ Registrar Evento</a></li>
        <li><a href="crud/consultar_evento.php">📋 Consultar Eventos</a></li>
        <li><a href="vistas/form_participacion_evento.php">➕ Registrar Participación en Evento</a></li>
        <li><a href="crud/consultar_participacion.php">📋 Consultar Participaciones</a></li>
    </ul>
</div>

<div class="footer">
    <p>Desarrollado con 💚 para la gestión de semillas — PostgreSQL + PHP + PDO</p>
</div>

</body>
</html>
