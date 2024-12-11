<?php
require_once(__DIR__ . "/../Controllers/carController.php");
require_once(__DIR__ . "/../Controllers/userController.php");
require_once(__DIR__ . "/../Controllers/storeController.php");
require_once(__DIR__ . "/../Controllers/brandController.php");
//echo "cheguei";

function handleRequest($route, $method)
{
    if ($method === 'GET') {
        if ($route === '/carros') {
            // A rota é para pegar todos os carros
            $controller = new carController();
            $controller->selectAllCars();
        } elseif (preg_match('/^\/carros\/\d+$/', $route)) {
            // A rota é para pegar um carro específico com um ID
            $id = (int) substr($route, strrpos($route, '/') + 1); // Obtém o ID após a última barra
            $controller = new carController();
            $controller->selectCar($id);
        }

        if ($route === '/users') {
            // A rota é para pegar todos os usuários
            $controller = new userController();
            $controller->selectAllUsers();
        } elseif (preg_match('/^\/users\/\d+$/', $route)) {
            // A rota é para pegar um usuário específico com um ID
            $id = (int) substr($route, strrpos($route, '/') + 1); // Obtém o ID após a última barra
            $controller = new userController();
            $controller->selectUser($id);
        }

        if ($route === '/lojas') {
            // A rota é para pegar todas as lojas
            $controller = new storeController();
            $controller->selectAllStores();
        } elseif (preg_match('/^\/lojas\/\d+$/', $route)) {
            // A rota é para pegar uma loja específica com um ID
            $id = (int) substr($route, strrpos($route, '/') + 1); // Obtém o ID após a última barra
            $controller = new storeController();
            $controller->selectStore($id);
        }

        if ($route === '/marcas') {
            // A rota é para pegar todas as brands
            $controller = new brandController();
            $controller->selectAllBrands();
        } elseif (preg_match('/^\/marcas\/\d+$/', $route)) {
            // A rota é para pegar uma brand específica com um ID
            $id = (int) substr($route, strrpos($route, '/') + 1); // Obtém o ID após a última barra
            $controller = new brandController();
            $controller->selectBrand($id);
        }

    } elseif ($method === 'POST') {
        if ($route === '/carros') {
            $controller = new carController();
            $controller->insertCar();
        }
        
        elseif ($route === "/users") {
            $controller = new userController();
            $controller->insertUser();
        }

        elseif ($route === "/lojas") {
            // A rota é para adicionar uma nova loja
            $controller = new storeController();
            $controller->insertStore();
        }

        elseif ($route === "/marcas") {
            // A rota é para adicionar uma nova brand
            $controller = new brandController();
            $controller->insertBrand();
        }

    } elseif ($method === 'PUT') {
        if (preg_match('/^\/carros\/\d+$/', $route)) {
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new carController();
            $controller->updateCar($id);
        }

        elseif (preg_match('/^\/users\/\d+$/', $route)) {
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new userController();
            $controller->updateUser($id);
        }

        elseif (preg_match('/^\/lojas\/\d+$/', $route)) {
            // A rota é para atualizar uma loja específica
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new storeController();
            $controller->updateStore($id);
        }

        elseif (preg_match('/^\/marcas\/\d+$/', $route)) {
            // A rota é para atualizar uma brand específica
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new brandController();
            $controller->updateBrand($id);
        }

    } elseif ($method === 'DELETE') {
        if (preg_match('/^\/carros\/\d+$/', $route)) {
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new carController();
            $controller->deleteCar($id);
        }

        elseif (preg_match('/^\/users\/\d+$/', $route)) {
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new userController();
            $controller->deleteUser($id);
        }

        elseif (preg_match('/^\/lojas\/\d+$/', $route)) {
            // A rota é para deletar uma loja específica
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new storeController();
            $controller->deleteStore($id);
        }

        elseif (preg_match('/^\/marcas\/\d+$/', $route)) {
            // A rota é para deletar uma brand específica
            $id = explode('/', $route)[2]; // Extrai o ID da URL
            $controller = new brandController();
            $controller->deleteBrand($id);
        }

    } else {
        // Método não suportado
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint não encontrado']);
    }
}
