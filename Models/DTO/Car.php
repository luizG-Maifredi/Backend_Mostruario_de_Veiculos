<?php 
    require_once ('../DAO/CarDAO.php');

    class Car{
        private $nome;
        private $preco;
        private $combustivel;
        private $modelo;
        private $finalPlaca;
        private $ano;
        private $cor;
        private $quilometragem;
        private $cambio;


        public function getData(){
            $data = ['nome'=> $this->nome, 'preco'=> $this->preco, 'combustivel'=> $this->combustivel,
                    'modelo'=> $this->modelo, 'finalPlaca'=> $this->finalPlaca, 'ano'=> $this->ano,
                    'cor'=> $this->cor, 'quilometragem'=> $this->quilometragem, 'cambio'=> $this->cambio];
            return $data;
        }

    }
?>