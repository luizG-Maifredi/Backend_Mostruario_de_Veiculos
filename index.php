<?php
chdir(__DIR__);
require_once(__DIR__ . "/Routes/routes.php");

$route = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remover query strings, caso existam
if (strpos($route, '?') !== false) {
    $route = explode('?', $route)[0];
}

// Processa a requisição
handleRequest($route, $method);
