<?php
// include database and object files
include_once './conexion.php';

echo $_SERVER['REQUEST_METHOD'];

switch($_SERVER['REQUEST_METHOD']){
    case 'POST':
    echo 'Llamada hecha a traves de POST';
    break;
    case 'PUT':
    echo 'Llamada hecha a traves de PUT';
    case 'GET':
    echo 'Llamada hecha a traves de GET';
    break;
    case 'DELETE':
    echo 'Llamada hecha a traves de DELETE';
    break;
    default:
    echo 'ERROR';
}