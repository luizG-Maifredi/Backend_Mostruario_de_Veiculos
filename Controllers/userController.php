<?php
require_once( __DIR__ . "/../Config/database.php");


 class userController{
    private $conn;

    public function insertUser(){

        $input = file_get_contents("php://input");
        $data = json_decode($input, true); 

        $nome = $data['nome'];
        $sobrenome = $data['sobrenome'];
        $email = $data['email'];
        $status = $data['status'];



        $this->conn = Database::getConnection();
        
        $sql = "INSERT INTO usuario (nome, sobrenome, email, status)
        VALUES (:nome, :sobrenome, :email , :status)";
        // Prepara a consulta
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':status', $status);

        // Executa a consulta
        $stmt->execute();

        // Fecha a conexão com o banco de dados
        $this->conn = Database::closeConnection();
        echo json_encode(["response" => "o usuario ". $data['nome']." foi inserido"], JSON_PRETTY_PRINT);
    }

    public function selectUser($id){
        $this->conn = Database::getConnection();
        
        $sql = "SELECT * FROM usuario WHERE id_usuario = :IDusuario";
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':IDusuario', $id);


        // Executa a consulta
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        
        $data = [
           
            'id_usuario' => $row['id_usuario'],
            'nome' => $row['nome'],
            'sobrenome' => $row['sobrenome'],
            'email' => $row['email']
        ];

        $this->conn = Database::closeConnection();

        echo json_encode($data, JSON_PRETTY_PRINT);
        
    }

    public function selectAllUsers(){
        $this->conn = Database::getConnection();
        
        $sql =  "SELECT * FROM usuario WHERE status=1";
        $stmt = $this->conn->prepare($sql);
        // Executa a consulta
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepara um array para armazenar todos os dados
        $data = [];

        // Adiciona cada linha no array $data
        foreach ($rows as $row) {


        $data[] = [
            'id_usuario' => $row['id_usuario'],
            'nome' => $row['nome'],
            'sobrenome' => $row['sobrenome'],
            'email' => $row['email']
        ];  
    }
        Database::closeConnection();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    

    public function updateUser($id) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
    
        // Verifica se os dados foram recebidos corretamente
        var_dump($data);
    
        $this->conn = Database::getConnection();       
    
        $sql = "UPDATE usuario 
        SET 
            nome = :nome,
            sobrenome = :sobrenome,
            email = :email,
            status = :status
        WHERE id_usuario = :id_usuario";
    
        $stmt = $this->conn->prepare($sql);
    
        // Verifica os parâmetros
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':sobrenome', $data['sobrenome']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':id_usuario', $id); 
    
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        echo json_encode(["response" => "O usuario " . $data['nome'] . " foi atualizado"], JSON_PRETTY_PRINT);
    }
    

    public function deleteUser($id) {
        $this->conn = Database::getConnection();
    
        // Atualiza o carro para inativo
        $sql = "UPDATE  usuario SET status = 0 WHERE id_usuario = :id_usuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $id);
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        // Retorna a resposta
        echo json_encode(["response" => "O usuario foi excluído"], JSON_PRETTY_PRINT);
    }
    


}

?>