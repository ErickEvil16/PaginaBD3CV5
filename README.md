Proyecto de Gestión de Semillas y Tipos de Cultivo
Sistema web con base de datos para administración de información agrícola
1. Introducción

El presente proyecto tiene como finalidad el diseño, construcción y documentación de una base de datos relacional y el desarrollo de un sistema web CRUD para la consulta, registro, actualización y eliminación de información relacionada con semillas y tipos de cultivo.
El objetivo central es la creación de una base de datos correctamente estructurada, normalizada y funcional, mientras que el sistema web funciona como una herramienta práctica para demostrar el uso real de dicha base de datos. Debido a su naturaleza académica, la evaluación del proyecto se centra principalmente en la calidad del modelo de datos, su diseño y su correcta implementación.

2. Objetivos del Proyecto
Objetivo general
• Desarrollar una base de datos robusta, normalizada y funcional que permita gestionar información agrícola, complementada con una aplicación web que realice operaciones CRUD sobre los datos almacenados.
Objetivos específicos

• Diseñar un modelo entidad–relación adecuado para representar semillas, tipos y su relación.
• Implementar la base de datos en un SGDB relacional (PostgreSQL) con integridad referencial.
• Desarrollar un sistema web en PHP con PDO capaz de interactuar con la base de datos.
• Aplicar buenas prácticas de programación, seguridad de consultas y manejo de errores.
• Probar, depurar y documentar tanto la base de datos como el funcionamiento de la aplicación.

3. Descripción General del Sistema
El sistema está compuesto por :

Sistema web CRUD
• Se desarrolló una aplicación en PHP que permite:
• Registrar nuevos tipos y semillas.
• Consultar registros existentes mediante consultas directas y consultas por categoría.
• Actualizar información mediante formularios validados.
• Eliminar registros con confirmación.
• El sistema usa PDO para asegurar una correcta conexión y protección contra inyecciones SQL (prepared statements).

4. Funcionalidades Implementadas

CRUD completo de la entidad Tipo
• Registrar tipo
• Consultar tipo
• Actualizar tipo
• Eliminar tipo

CRUD completo de la entidad Semilla
• Registrar semilla
• Consultar semillas por tipo
• Consultar todas las semillas
• Editar y eliminar semillas

Vistas y formularios estructurados
• Uso de HTML, CSS y PHP
• Mensajes de validación y retroalimentación al usuario

Módulo de conexión a la base de datos
• Conexión centralizada con PDO
• Manejo de excepciones y errores controlados

5. Arquitectura del Proyecto
El proyecto se organiza en los siguientes módulos:

/crud/ → Contiene las operaciones CRUD para cada entidad.
/conexion/ → Archivo de conexión con PDO.
/consultas/ → Consultas específicas, filtradas o personalizadas.
/vistas/ → Formularios y pantallas de interacción.
/sql/ → Scripts de creación de tablas e inserción de datos.

Esta estructura permite mantener un código ordenado, modular y fácil de modificar o ampliar.

6. Metodología de Desarrollo
• Se siguió una metodología estructurada en las siguientes etapas:
• Análisis de requerimientos
• Diseño del modelo entidad–relación
• Traducción del modelo al esquema SQL
• Programación del sistema web
• Pruebas de funcionamiento y depuración
• Documentación del proyecto
• Cada etapa se completó validando que la base de datos fuera coherente, funcional y correctamente enlazada con la aplicación.

7. Resultados
• El proyecto cumple con los objetivos planteados:
• La base de datos es completamente funcional, normalizada y respaldada por claves foráneas.
• La aplicación permite manipular los datos mediante operaciones CRUD de forma segura.
• Las consultas muestran la correcta relación entre las tablas.
• Todo el flujo de datos opera adecuadamente desde la interfaz web hasta la base de datos.
