Convivesys: ¿Para qué sirve? (6 de abril de 2025)
===========================
Convivesys es una aplicación para la gestión de la convivencia y absentismo en un centro educativo. Funciones fundamentales de esta aplicación están adaptadas a la obtención de datos en el formato facilitado por la aplicación Séneca para el profesorado de la Junta de Andalucía. Por una parte gestiona la convivencia:
- Partes disciplinarios,
- Sanciones asociadas,
- Comunicaciones de partes,
- Informes de sanciones semanal
- Informes por alumnos.
Por otro lado en la gestión del absentismo escolar, gestiona:
- Control de faltas del alumando,
- Gestión de los pasos del protocolo de absentismo.
- Generación de documentación relacionada.
- Comunicaciones con el profesorado.
- Obtención de informes.  


GUÍA DE INSTALACIÓN DE CONVIVESYS
=================================

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
fpdf184 (debe incluirse en la carpeta "codigo_php/fpdf184". (https://github.com/AM-ASKY-97/online-application-form-for-the-ILS-Institute/tree/master/fpdf184).

-Será necesario para el envío de correos electrónicos por parte de la aplicación, la obtención (y/o configuración) de una cuenta de correo que permita el envío de los mismos desde aplicaciones externas, por ejemplo: Mailersend.com, entre otros muchos.

Consideraciones de seguridad:
============================
El software ha sido diseñado por un programador novato. Se recomienda encarecidamente realizar la instalación en un entorno de servidor local aislado, sin conexión directa a internet, para maximizar la seguridad de los datos.

Responsabilidad del usuario:
===========================
Este programa se distribuye con la esperanza de que sea útil, pero SIN NINGUNA GARANTÍA.
El usuario es el único responsable de la seguridad y el manejo adecuado de los datos almacenados en la aplicación.

Copyright (C) [2025] [Santigo Morales Domingo]
