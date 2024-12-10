<?php
require_once( __DIR__ . "/../Config/database.php");

class brandController {
    private $conn;

    // Função para inserir uma nova marca
    public function insertBrand(){
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); 

        $nome_brand = $data['nome_marca'];
        $quantidade = $data['quantidade'];
        $status = $data['status'];

        $this->conn = Database::getConnection();

        $sql = "INSERT INTO marca (nome_marca, quantidade, status)
                VALUES (:nome_marca, :quantidade, :status)";
        
        // Prepara a consulta
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':nome_marca', $nome_brand);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':status', $status);

        // Executa a consulta
        $stmt->execute();

        // Fecha a conexão com o banco de dados
        $this->conn = Database::closeConnection();
        echo json_encode(["response" => "A marca foi adicionada com sucesso!"], JSON_PRETTY_PRINT);
    }

    // Função para selecionar uma marca pelo ID
    public function selectBrand($id){
        $this->conn = Database::getConnection();
        
        $sql = "SELECT * FROM marca WHERE id_marca = :id_marca";
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':id_marca', $id);

        // Executa a consulta
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $data = [
            'id_marca' => $row['id_marca'],
            'nome_marca' => $row['nome_marca'],
            'quantidade' => $row['quantidade'],
            'status' => $row['status']
        ];

        $this->conn = Database::closeConnection();

        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    // Função para selecionar todas as marcas
    public function selectAllBrands(){
        $this->conn = Database::getConnection();
        
        $sql =  "SELECT * FROM marca WHERE status=1";
        $stmt = $this->conn->prepare($sql);
        
        // Executa a consulta
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepara um array para armazenar todos os dados
        $data = [];

        // Adiciona cada linha no array $data
        foreach ($rows as $row) {
            $data[] = [
                'id_marca' => $row['id_marca'],
                'nome_marca' => $row['nome_marca'],
                'quantidade' => $row['quantidade'],
                'status' => $row['status']
            ];  
        }

        $this->conn = Database::closeConnection();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    // Função para atualizar uma marca
    public function updateBrand($id) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
    
        $this->conn = Database::getConnection();       
    
        $sql = "UPDATE marca 
        SET 
            nome_marca = :nome_marca,
            quantidade = :quantidade,
            status = :status
        WHERE id_marca = :id_marca";
    
        $stmt = $this->conn->prepare($sql);
    
        // Associa os parâmetros aos valores
        $stmt->bindParam(':nome_marca', $data['nome_marca']);
        $stmt->bindParam(':quantidade', $data['quantidade']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id_marca', $id); 
    
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        echo json_encode(["response" => "A marca foi atualizada com sucesso"], JSON_PRETTY_PRINT);
    }

    // Função para desativar uma marca
    public function deleteBrand($id) {
        $this->conn = Database::getConnection();
    
        // Atualiza o status da marca para inativo
        $sql = "UPDATE marca SET status = 0 WHERE id_marca = :id_marca";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_marca', $id);
        
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        // Retorna a resposta
        echo json_encode(["response" => "A marca foi desativada"], JSON_PRETTY_PRINT);
    }
}
?>
