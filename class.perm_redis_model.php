<?php

    class PermutaReedistribuicao{
        public $id;
        public $nome;
        public $cpf;
        public $cargo;
        public $matricula;
        public $telefone;
        public $email;
        public $mensagem;
        public $orgaoOrigem;
        public $lotacaoOrigem;
        public $estadoOrigem;
        public $cidadeOrigem;
        public $orgaoDestino;
        public $lotacaoDestino;
        public $estadoDestino;
        public $cidadeDestino;

        public static function fromQueryParams($params)
        {
            $obj = new PermutaReedistribuicao();                
                $obj->nome = $params['nome'];
                $obj->cpf = $params['cpf'];
                $obj->cargo = $params['cargo'];
                $obj->matricula = $params['matricula'];
                $obj->telefone = $params['telefone'];
                $obj->email = $params['email'];
                $obj->mensagem = $params['mensagem'];
                $obj->orgaoOrigem = $params['orgaoOrigem'];
                $obj->lotacaoOrigem = $params['lotacaoOrigem'];
                $obj->estadoOrigem = $params['estadoOrigem'];
                $obj->cidadeOrigem = $params['cidadeOrigem'];
                $obj->orgaoDestino = $params['orgaoDestino'];
                $obj->lotacaoDestino = $params['lotacaoDestino'];
                $obj->estadoDestino = $params['estadoDestino'];
                $obj->cidadeDestino = $params['cidadeDestino'];
    
            return $obj;
        }

        public static function fromDatabaseResult($res)
        {
            $obj = new PermutaReedistribuicao();                
                $obj->id = $res->perm_redis_id;
                $obj->nome = $res->Nome;
                $obj->cpf = $res->CPF;
                $obj->cargo = $res->Cargo;
                $obj->matricula = $res->Matricula;
                $obj->telefone = $res->Telefone;
                $obj->email = $res->Email;
                $obj->mensagem = $res->Mensagem;
                $obj->orgaoOrigem = $res->OrgaoNome;
                $obj->lotacaoOrigem = $res->OrgaoLotacao;
                $obj->estadoOrigem = $res->OrgaoEstado;
                $obj->cidadeOrigem = $res->OrgaoCidade;
                $obj->orgaoDestino = $res->OrgaoDestinoNome;
                $obj->lotacaoDestino = $res->OrgaoDestinoLotacao;
                $obj->estadoDestino = $res->OrgaoDestinoEstado;
                $obj->cidadeDestino = $res->OrgaoDestinoCidade;
    
            return $obj;
        }
    }

?>