Guía de Instalación de Convivesys

Requisitos previos:
==================
Un servidor web con PHP compatible (versión mínima recomendada: PHP 8.2.12 ).
Un servidor de base de datos MySQL/MariaDB (versión mínima recomendada: 5.2.1).

Pasos de instalación:
====================
-Despliegue de archivos PHP:
Copie la carpeta "codigo_php" al directorio raíz de su servidor web. Asegúrese de que el servidor tenga permisos de lectura y escritura en esta carpeta.

-Configuración de la base de datos:
Importe el archivo SQL ubicado en la carpeta "base_datos" creando una base de datos llamada "disciplina".

-Esta aplicación usa las librerias, que el usuario debe incluir por su cuenta:
phpmailer (debe incluirse en la carpeta "codigo_php/phpmailer". (https://github.com/PHPMailer/PHPMailer/tree/master/src)
fpdf184 (debe incluirse en la carpeta "codigo_php/fpdf184". (https://github.com/AM-ASKY-97/online-application-form-for-the-ILS-Institute/tree/master/fpdf184)

Consideraciones de seguridad:
============================
El software ha sido diseñado por un programador novato. Se recomienda encarecidamente realizar la instalación en un entorno de servidor local aislado, sin conexión directa a internet, para maximizar la seguridad de los datos.

Responsabilidad del usuario:
===========================
Este programa se distribuye con la esperanza de que sea útil, pero SIN NINGUNA GARANTÍA.
El usuario es el único responsable de la seguridad y el manejo adecuado de los datos almacenados en la aplicación.


Copyright (C) [2025] [Santigo Morales Domingo]
