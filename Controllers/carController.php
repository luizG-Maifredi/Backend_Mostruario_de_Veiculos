<?php
require_once(__DIR__ . "/../Config/database.php");


class carController
{
    private $conn;

    public function insertCar()
    {

        $input = file_get_contents("php://input");
        $data = json_decode($input, true);
        $nome = $data['nome'];
        $modelo = $data['modelo'];
        $ano = $data['ano'];
        $cor = $data['cor'];
        $preco = $data['preco'];
        $quilometragem = $data['quilometragem'];
        $cilindradas = $data['cilindradas'];
        $estado_do_carro = $data['estadoDoCarro'];
        $cambio = $data['cambio'];
        $finalDaPlaca = $data['finalDaPlaca'];
        $descricao = $data['descricao'];
        $combustivel = $data['combustivel'];
        $itens = $data['itens']; // Converte array para string, se necessário
        $id_loja = $data['loja'];
        $id_marca = $data['marca'];


        $this->conn = Database::getConnection();

        $sql = "INSERT INTO carro (nome, modelo, ano_carro, cor, preco, quilometragem, cilindradas, estado_do_carro, cambio, final_da_placa, descricao,combustivel, itens, id_loja, id_marca)
        VALUES (:nome, :modelo, :ano_carro, :cor, :preco, :quilometragem, :cilindradas, :estado_do_carro, :cambio, :final_da_placa, :descricao, :combustivel, :itens, :id_loja, :id_marca )";

        // Prepara a consulta
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':modelo', $modelo);
        $stmt->bindParam(':ano_carro', $ano);
        $stmt->bindParam(':cor', $cor);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':quilometragem', $quilometragem);
        $stmt->bindParam(':cilindradas', $cilindradas);
        $stmt->bindParam(':estado_do_carro', $estado_do_carro);
        $stmt->bindParam(':cambio', $cambio);
        $stmt->bindParam(':final_da_placa', $finalDaPlaca);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':combustivel', $combustivel);
        $stmt->bindParam(':itens', $itens);
        $stmt->bindParam(':id_loja', $id_loja);
        $stmt->bindParam(':id_marca', $id_marca);

        // Executa a consulta
        $stmt->execute();

        // Fecha a conexão com o banco de dados
        $this->conn = Database::closeConnection();
        echo json_encode([
            "response" => "o carro " . $data['nome'] . " foi inserido",
            "imagem" => $data['imagem']
        ], JSON_PRETTY_PRINT);
    }

    public function selectCar($id)
    {
        $this->conn = Database::getConnection();

        $sql = "SELECT 
                carro.id_carro,
                carro.nome AS nome_carro,
                carro.modelo,
                carro.ano_carro,
                carro.cor,
                carro.preco,
                carro.quilometragem,
                carro.cilindradas,
                carro.estado_do_carro,
                carro.cambio,
                carro.final_da_placa,
                carro.descricao,
                carro.combustivel,
                carro.itens,
                carro.status AS status_carro,
                loja.id_loja,
                loja.endereco,
                loja.parceiros,
                loja.status AS status_loja,
                marca.id_marca,
                marca.nome_marca,
                marca.status AS status_marca
            FROM 
                defaultdb.carro
            LEFT JOIN 
                defaultdb.loja ON carro.id_loja = loja.id_loja
            LEFT JOIN 
                defaultdb.marca ON carro.id_marca = marca.id_marca WHERE carro.id_carro = :id_carro;";
        $stmt = $this->conn->prepare($sql);

        // Associa os parâmetros aos valores
        $stmt->bindParam(':id_carro', $id);


        // Executa a consulta
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);


        $data[] = [
            'id' => $row['id_carro'],
            'nome' => $row['nome_carro'],
            'ano' => $row['ano_carro'],
            'modelo' => $row['modelo'],
            'cor' => $row['cor'],
            'preco' => $row['preco'],
            'quilometragem' => $row['quilometragem'],
            'cilindradas' => $row['cilindradas'],
            'estadoDoCarro' => $row['estado_do_carro'],
            'cambio' => $row['cambio'],
            'finalDaPlaca' => $row['final_da_placa'],
            'descricao' => $row['descricao'],
            'combustivel' => $row['combustivel'],
            'itens' => $row['itens'],
            'endereco_loja' => $row['endereco'],
            'nome_marca' => $row['nome_marca']
        ];

        $this->conn = Database::closeConnection();

        echo json_encode($data, JSON_PRETTY_PRINT);

    }

    public function selectAllCars()
    {
        $this->conn = Database::getConnection();

        $sql = "SELECT 
                carro.id_carro,
                carro.nome AS nome_carro,
                carro.modelo,
                carro.ano_carro,
                carro.cor,
                carro.preco,
                carro.quilometragem,
                carro.cilindradas,
                carro.estado_do_carro,
                carro.cambio,
                carro.final_da_placa,
                carro.descricao,
                carro.combustivel,
                carro.itens,
                carro.status AS status_carro,
                loja.id_loja,
                loja.nome_loja,
                loja.endereco,
                loja.parceiros,
                loja.status AS status_loja,
                marca.id_marca,
                marca.nome_marca,
                marca.status AS status_marca
            FROM 
                defaultdb.carro
            LEFT JOIN 
                defaultdb.loja ON carro.id_loja = loja.id_loja
            LEFT JOIN 
                defaultdb.marca ON carro.id_marca = marca.id_marca WHERE carro.status = 1;";

        $stmt = $this->conn->prepare($sql);
        // Executa a consulta
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepara um array para armazenar todos os dados
        $data = [];

        // Adiciona cada linha no array $data
        foreach ($rows as $row) {


            $data[] = [
                'id' => $row['id_carro'],
                'nome' => $row['nome_carro'],
                'ano' => $row['ano_carro'],
                'modelo' => $row['modelo'],
                'cor' => $row['cor'],
                'preco' => $row['preco'],
                'quilometragem' => $row['quilometragem'],
                'cilindradas' => $row['cilindradas'],
                'estadoDoCarro' => $row['estado_do_carro'],
                'cambio' => $row['cambio'],
                'finalDaPlaca' => $row['final_da_placa'],
                'descricao' => $row['descricao'],
                'combustivel' => $row['combustivel'],
                'itens' => $row['itens'],
                'loja' => $row['nome_loja'],
                'endereco_loja' => $row['endereco'],
                'marca' => $row['nome_marca']
            ];
        }
        Database::closeConnection();
        echo json_encode($data, JSON_PRETTY_PRINT);
    }


    public function updateCar($id)
    {
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        // Verifica se os dados foram recebidos corretamente
        var_dump($data);

        $this->conn = Database::getConnection();

        $sql = "UPDATE carro 
        SET 
            nome = :nome,
            modelo = :modelo,
            ano_carro = :ano_carro,
            cor = :cor,
            preco = :preco,
            quilometragem = :quilometragem,
            cilindradas = :cilindradas,
            estado_do_carro = :estado_do_carro,
            cambio = :cambio,
            final_da_placa = :final_da_placa,
            descricao = :descricao,
            combustivel = :combustivel,
            itens = :itens,
            id_loja = :id_loja,
            id_marca = :id_marca
        WHERE id_carro = :id_carro";

        $stmt = $this->conn->prepare($sql);

        // Verifica os parâmetros
        $stmt->bindParam(':nome', $data['nome']);
        $stmt->bindParam(':modelo', $data['modelo']);
        $stmt->bindParam(':ano', $data['ano']);
        $stmt->bindParam(':cor', $data['cor']);
        $stmt->bindParam(':preco', $data['preco']);
        $stmt->bindParam(':quilometragem', $data['quilometragem']);
        $stmt->bindParam(':cilindradas', $data['cilindradas']);
        $stmt->bindParam(':estado_do_carro', $data['estadoDoCarro']);
        $stmt->bindParam(':cambio', $data['cambio']);
        $stmt->bindParam(':final_da_placa', $data['finalDaPlaca']);
        $stmt->bindParam(':descricao', $data['descricao']);
        $stmt->bindParam(':combustivel', $data['combustivel']);
        $stmt->bindParam(':itens', $data['itens']);
        $stmt->bindParam(':id_loja', $data['loja']);
        $stmt->bindParam(':id_marca', $data['marca']);
        $stmt->bindParam(':id_carro', $id);

        // Executa a consulta
        $stmt->execute();

        // Fecha a conexão
        $this->conn = Database::closeConnection();

        echo json_encode(["response" => "O carro " . $data['nome'] . " foi atualizado"], JSON_PRETTY_PRINT);
    }


    public function deleteCar($id)
    {

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