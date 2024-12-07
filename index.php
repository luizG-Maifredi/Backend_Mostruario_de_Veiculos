<?php
chdir(__DIR__);
require_once( __DIR__ . "/Routes/routes.php");
// Inclui o roteador

$route = $_SERVER['REQUEST_URI'];// Obtém a rota requisitada
$method = $_SERVER['REQUEST_METHOD']; // Obtém o método HTTP usado

// Roteador processa a requisição
handleRequest($route, $method);