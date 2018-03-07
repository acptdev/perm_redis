<?php

    class PermutaReedistribuicao{
        public $id;
        public $tipo;
        public $nome;
        public $cpf;
        public $cargo;
        public $especialidade;
        public $matricula;
        public $telefone;
        public $email;
        public $mensagem;
        public $orgaoOrigem;        
        public $estadoOrigem;
        public $cidadeOrigem;
        public $orgaoDestino;        
        public $estadoDestino;
        public $cidadeDestino;
        public $data;

        public static function fromQueryParams($params)
        {
            $obj = new PermutaReedistribuicao();                
                $obj->nome = $params['nome'];
                $obj->tipo = $params['tipo'];
                $obj->cpf = $params['cpf'];
                $obj->cargo = $params['cargo'];
                $obj->especialidade = $params['especialidade'];
                $obj->matricula = $params['matricula'];
                $obj->telefone = $params['telefone'];
                $obj->email = $params['email'];
                $obj->mensagem = $params['mensagem'];
                $obj->orgaoOrigem = $params['orgaoOrigem'];                
                $obj->estadoOrigem = $params['estadoOrigem'];
                $obj->cidadeOrigem = $params['cidadeOrigem'];
                $obj->orgaoDestino = $params['orgaoDestino'];                
                $obj->estadoDestino = $params['estadoDestino'];
                $obj->cidadeDestino = $params['cidadeDestino'];
    
            return $obj;
        }

        public static function fromDatabaseResult($res, $isFront = false)
        {
            $obj = new PermutaReedistribuicao();                
                $obj->id = $res->perm_redis_id;
                $obj->nome = $res->Nome;
                $obj->tipo = $res->Tipo;
                $obj->cpf = $isFront ? '--' : $res->CPF;
                $obj->cargo = $res->Cargo;
                $obj->especialidade = $res->Especialidade;
                $obj->matricula = $res->Matricula;
                $obj->telefone = $res->Telefone;
                $obj->email = $res->Email;
                $obj->mensagem = $res->Mensagem;
                $obj->origem = $res->CidadeOrigem .'/'. $res->EstadoOrigem;                
                $obj->destino = $res->CidadeDestino .'/'. $res->EstadoDestino;                
                $obj->orgaoOrigem = $res->OrgaoOrigem;                
                $obj->estadoOrigem = $res->OrgaoEstado;
                $obj->cidadeOrigem = $res->OrgaoCidade;
                $obj->orgaoDestino = $res->OrgaoDestino;                
                $obj->estadoDestino = $res->OrgaoDestinoEstado;
                $obj->cidadeDestino = $res->OrgaoDestinoCidade;
                $obj->data = $res->Data;
    
            return $obj;
        }
    }

?>