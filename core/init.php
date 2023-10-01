<?php
require "./core/autoload.php";

$rute = dirname(__FILE__);
$baseDeDatos = new PDO("sqlite:$rute/system.db");
$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sentencia = $baseDeDatos->prepare("SELECT * FROM configs WHERE id = 1 LIMIT 1;");
$sentencia->execute();
$config = $sentencia->fetch(PDO::FETCH_OBJ);