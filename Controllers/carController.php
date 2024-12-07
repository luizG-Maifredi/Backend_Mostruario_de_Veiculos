<?php
require_once( __DIR__ . "/../Config/database.php");


 class carController{
    private $conn;

    public function insertCar(){

        $input = file_get_contents("php://input");
        $data = json_decode($input, true); 
        $nome = $data['nome'];
        $preco = $data['preco'];
        $combustivel = $data['combustivel'];
        $modelo = $data['modelo'];
        $finalPlaca = $data['final_da_placa'];
        $ano = $data['ano'];
        $cor = $data['cor'];
        $quilometragem = $data['quilometragem'];
        $cambio = $data['cambio'];


        $this->conn = Database::getConnection();
        
        $sql = "INSERT INTO Carro (nome, preco, combustivel, modelo, final_da_placa, ano, cor, quilometragem, cambio)
        VALUES (:nome, :preco, :combustivel, :modelo, :final_da_placa, :ano, :cor, :quilometragem, :cambio)";
        // Prepara a consulta
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':combustivel', $combustivel);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->bindParam(':final_da_placa', $finalPlaca);
        $stmt->bindParam(':ano', $ano);
        $stmt->bindParam(':cor', $cor);
        $stmt->bindParam(':quilometragem', $quilometragem);
        $stmt->bindParam(':cambio', $cambio);

        // Executa a consulta
        $stmt->execute();

        // Fecha a conexão com o banco de dados
        $this->conn = Database::closeConnection();
        echo json_encode(["response" => "o carro ". $data['nome']." foi inserido"], JSON_PRETTY_PRINT);
    }

    public function selectCar($id){
        $this->conn = Database::getConnection();
        
        $sql = "SELECT * FROM CARRO WHERE ID_CARRO = :IDCARRO";
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':IDCARRO', $id);


        // Executa a consulta
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        
        $data = [
            'nome' => $row['nome'],
            'preco' => $row['preco'],
            'combustivel' => $row['combustivel'],
            'modelo' => $row['modelo'],
            'finalPlaca' => $row['final_da_placa'],
            'ano' => $row['ano'],
            'cor' => $row['cor'],
            'quilometragem' => $row['quilometragem'],
            'cambio' => $row['cambio']
        ];

        $this->conn = Database::closeConnection();

        echo json_encode($data, JSON_PRETTY_PRINT);
        
    }

    public function selectAllCars(){
        $this->conn = Database::getConnection();
        
        $sql =  "SELECT * FROM carro";
        $stmt = $this->conn->prepare($sql);
        // Executa a consulta
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepara um array para armazenar todos os dados
        $data = [];

        // Adiciona cada linha no array $data
        foreach ($rows as $row) {


        $data[] = [
        'nome' => $row['nome'],
        'preco' => $row['preco'],
        'combustivel' => $row['combustivel'],
        'modelo' => $row['modelo'],
        'finalPlaca' => $row['final_da_placa'],
        'ano' => $row['ano'],
        'cor' => $row['cor'],
        'quilometragem' => $row['quilometragem'],
        'cambio' => $row['cambio']
        ];  
    }
        Database::closeConnection();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
    

    public function updateCar($id) {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
    
        // Verifica se os dados foram recebidos corretamente
        var_dump($data);
    
        $this->conn = Database::getConnection();       
    
        $sql = "UPDATE Carro 
        SET 
            nome = :nome,
            preco = :preco,
            combustivel = :combustivel,
            modelo = :modelo,
            final_da_placa = :final_da_placa,
            ano = :ano,
            cor = :cor,
            quilometragem = :quilometragem,
            cambio = :cambio
        WHERE id_carro = :id_carro";
    
        $stmt = $this->conn->prepare($sql);
    
        // Verifica os parâmetros
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':preco', $data['preco']);
        $stmt->bindParam(':combustivel', $data['combustivel']);
        $stmt->bindParam(':modelo', $data['modelo']);
        $stmt->bindParam(':final_da_placa', $data['final_da_placa']);
        $stmt->bindParam(':ano', $data['ano']);
        $stmt->bindParam(':cor', $data['cor']);
        $stmt->bindParam(':quilometragem', $data['quilometragem']);
        $stmt->bindParam(':cambio', $data['cambio']);
        $stmt->bindParam(':id_carro', $id);  // Certifique-se de que $id está correto
    
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        echo json_encode(["response" => "O carro " . $data['nome'] . " foi atualizado"], JSON_PRETTY_PRINT);
    }
    

    public function deleteCar($id) {

        $this->conn = Database::getConnection();
        
    
        // Atualiza o carro para inativo
        $sql = "UPDATE carro SET status = 0 WHERE id_carro = :id_carro";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_carro', $id);
        // Executa a consulta
        $stmt->execute();
    
        // Fecha a conexão
        $this->conn = Database::closeConnection();
    
        // Retorna a resposta
        echo json_encode(["response" => "O carro foi excluído"], JSON_PRETTY_PRINT);
    }
    


}

?>