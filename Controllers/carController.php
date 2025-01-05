<?php
require_once(__DIR__ . "/../Config/database.php");


class carController
{
    private $conn;

    public function insertCar()
    {
        // Dados do formulário via form-data
        $nome = $_POST['nome'];
        $modelo = $_POST['modelo'];
        $ano = $_POST['ano'];
        $cor = $_POST['cor'];
        $preco = $_POST['preco'];
        $quilometragem = $_POST['quilometragem'];
        $cilindradas = $_POST['cilindradas'];
        $estado_do_carro = $_POST['estadoDoCarro'];
        $cambio = $_POST['cambio'];
        $finalDaPlaca = $_POST['finalDaPlaca'];
        $descricao = $_POST['descricao'];
        $combustivel = $_POST['combustivel'];
        $itens = implode(', ', explode(',', $_POST['itens'])); // String separada por vírgulas
        $id_loja = $_POST['loja'];
        $id_marca = $_POST['marca'];

        // Tratamento do arquivo (imagem)
        $imageName = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/uploads/";
            $imageName = uniqid() . '_' . basename($_FILES['imagem']['name']);
            $imagePath = $uploadDir . $imageName;

            // Cria o diretório, se necessário
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move o arquivo enviado para o diretório de uploads
            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $imagePath)) {
                echo json_encode([
                    "error" => "Falha ao salvar a imagem no servidor."
                ], JSON_PRETTY_PRINT);
                http_response_code(500);
                return;
            }
        }

        // Conexão e inserção no banco
        $this->conn = Database::getConnection();

        $sql = "INSERT INTO carro (nome, modelo, ano_carro, cor, preco, quilometragem, cilindradas, estado_do_carro, cambio, final_da_placa, descricao, combustivel, itens, id_loja, id_marca, imagem)
            VALUES (:nome, :modelo, :ano_carro, :cor, :preco, :quilometragem, :cilindradas, :estado_do_carro, :cambio, :final_da_placa, :descricao, :combustivel, :itens, :id_loja, :id_marca, :imagem)";

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
        $stmt->bindParam(':imagem', $imageName);

        $stmt->execute();

        $this->conn = Database::closeConnection();

        echo json_encode([
            "response" => "O carro {$nome} foi inserido com sucesso.",
            "imagem" => $imageName ? "uploads/{$imageName}" : "Nenhuma imagem enviada."
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
            carro.imagem, -- Adiciona o campo de imagem
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
            defaultdb.marca ON carro.id_marca = marca.id_marca 
        WHERE carro.status = 1;";

        $stmt = $this->conn->prepare($sql);

        // Executa a consulta
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepara um array para armazenar todos os dados
        $data = [];

        // Define o diretório base para as imagens
        $baseUrl = "http://localhost/uploads/";

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
                'imagem' => $row['imagem'] ? $baseUrl . $row['imagem'] : null, // Constrói a URL completa
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