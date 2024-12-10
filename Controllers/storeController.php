<?php
require_once( __DIR__ . "/../Config/database.php");

class storeController {
    private $conn;

    // Função para inserir uma nova loja
    public function insertStore(){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); 

        $endereco = $data['endereco'];
        $parceiros = $data['parceiros'];
        $status = $data['status'];

        $this->conn = Database::getConnection();

        $sql = "INSERT INTO loja (endereco, parceiros, status)
                VALUES (:endereco, :parceiros, :status)";
        
        // Prepara a consulta
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':parceiros', $parceiros);
        $stmt->bindParam(':status', $status);

        // Executa a consulta
        $stmt->execute();

        // Fecha a conexão com o banco de dados
        $this->conn = Database::closeConnection();
        echo json_encode(["response" => "A loja foi adicionada com sucesso!"], JSON_PRETTY_PRINT);
    }

    // Função para selecionar uma loja pelo ID
    public function selectStore($id){
        $this->conn = Database::getConnection();
        
        $sql = "SELECT * FROM loja WHERE id_loja = :id_loja";
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':id_loja', $id);

        // Executa a consulta
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $data = [
            'id_loja' => $row['id_loja'],
            'endereco' => $row['endereco'],
            'parceiros' => $row['parceiros'],
            'status' => $row['status']
        ];

        $this->conn = Database::closeConnection();

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    // Função para selecionar todas as lojas
    public function selectAllStores(){
        $this->conn = Database::getConnection();
        
        $sql =  "SELECT * FROM loja WHERE status=1";
        $stmt = $this->conn->prepare($sql);
        
        // Executa a consulta
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepara um array para armazenar todos os dados
        $data = [];

        // Adiciona cada linha no array $data
        foreach ($rows as $row) {
            $data[] = [
                'id_loja' => $row['id_loja'],
                'endereco' => $row['endereco'],
                'parceiros' => $row['parceiros'],
                'status' => $row['status']
            ];  
        }

        $this->conn = Database::closeConnection();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    // Função para atualizar uma loja
    public function updateStore($id) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
    
        $this->conn = Database::getConnection();       
    
        $sql = "UPDATE loja 
        SET 
            endereco = :endereco,
            parceiros = :parceiros,
            status = :status
        WHERE id_loja = :id_loja";
    
        $stmt = $this->conn->prepare($sql);
    
        // Associa os parâmetros aos valores
        $stmt->bindParam(':endereco', $data['endereco']);
        $stmt->bindParam(':parceiros', $data['parceiros']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id_loja', $id); 
    
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        echo json_encode(["response" => "A loja foi atualizada com sucesso"], JSON_PRETTY_PRINT);
    }

    // Função para excluir uma loja
    public function deleteStore($id) {
        $this->conn = Database::getConnection();
    
        // Atualiza o status da loja para inativo
        $sql = "UPDATE loja SET status = 0 WHERE id_loja = :id_loja";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_loja', $id);
        
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        // Retorna a resposta
        echo json_encode(["response" => "A loja foi desativada"], JSON_PRETTY_PRINT);
    }
}
?>
