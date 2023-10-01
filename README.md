# Cardinal System for XAMPP (Windows)

Es un sistema para el manejo de proyectos en XAMPP, por el momento, solo para plataformas Windows. Muestra el contenido de la carpeta htdocs de XAMPP de manera agradable para el desarrollador y permite la gestion de los proyectos desde la web.

#Acciones soportadas.
 -  Abrir el proyecto directamente en el explorer.
 -  Eliminar.
 -  Renombrar.
 -  Salvar en formato ZIP.
 -  Abrir directamente en un IDE de preferencia.

#IDE reconocidos por CS
 -  SublimeText.
 -  VSCode.
 -  Notepad++.
 -  Notepad (Nativo).

#Acciones de Menu

 -  Abrir XAMPP CP.
 -  Permite abrir Navicat (De encontrarse instalado).
 -  Abrir el gestor de documentacion Zeal (de encontrarse instalado).
 -  Abrir el IDE de preferencia.
 -  Abrir la carpeta de salvas asignada.
 -  Abrir la carpeta htdocs.
 -  Filtrar entre los proyectos.
 -  Muestra estado del servidor MySQL.
 -  Acceso a configuraciones.
 -  Soporte para cambio entre versiones de PHP en el mismo XAMPP.
 -  Agregar nuevos proyectos desde la web.
 -  Posibilidad de agregar una estructura de carpetas predefinida para los nuevos proyectos.

#Tags de proyectos
 -  Proyectos propios.
 -  Proyectos colaborativos.
 -  Proyectos de terceros.

#Frameworks soportados automaticamente
 -  Laravel
 -  Wordpress
 -  Prestashop
 -  Drupal

# Instalacion
El index.php que se encuentra en htdocs debe reemplazarse con el siguiente contenido:

```php

<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.'/cardinal/');
	exit;
?>

```
