<?php
    class PermRedisDAO{
        
        private $wpdb;

        public function  __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;            
            $this->inicializarBanco();
        }
        
        public function inserir(PermutaReedistribuicao $perm_redis)
        {
            $this->wpdb->query(
                "                                        
                    INSERT INTO `".$this->wpdb->prefix."perm_redis` 
                        (`perm_redis_id`, `Nome`, `Email`, `CPF`, `Cargo`, `Matricula`, `Telefone`, `OrgaoNome`, `OrgaoLotacao`, `OrgaoEstado`, `OrgaoCidade`, 
                        `OrgaoDestinoNome`, `OrgaoDestinoLotacao`, `OrgaoDestinoEstado`, `OrgaoDestinoCidade`, `Mensagem`) 
                        VALUES (NULL, '$perm_redis->nome', '$perm_redis->email', '$perm_redis->cpf', '$perm_redis->cargo', 
                            '$perm_redis->matricula', '$perm_redis->telefone', '$perm_redis->orgaoOrigem', '$perm_redis->lotacaoOrigem', 
                            '$perm_redis->estadoOrigem', '$perm_redis->cidadeOrigem', '$perm_redis->orgaoDestino', '$perm_redis->lotacaoDestino', 
                            '$perm_redis->estadoDestino', '$perm_redis->cidadeDestino', '$perm_redis->mensagem');
                "
             );
        }

        public function atualizar(PermutaReedistribuicao $perm_redis, $id)
        {
            $this->wpdb->query(
                "    
                    UPDATE `".$this->wpdb->prefix."perm_redis` 
                    SET `Nome` = '$perm_redis->nome', `Email` = '$perm_redis->email', 
                    `CPF` = '$perm_redis->cpf', `Cargo` = '$perm_redis->cargo', `Matricula` = '$perm_redis->matricula', 
                    `Telefone` = '$perm_redis->telefone', `OrgaoNome` = '$perm_redis->orgaoOrigem', `OrgaoLotacao` = '$perm_redis->lotacaoOrigem', 
                    `OrgaoEstado` = '$perm_redis->estadoOrigem', `OrgaoCidade` = '$perm_redis->cidadeOrigem', `OrgaoDestinoNome` = '$perm_redis->orgaoDestino', 
                    `OrgaoDestinoLotacao` = '$perm_redis->lotacaoDestino', `OrgaoDestinoEstado` = '$perm_redis->estadoDestino', 
                    `OrgaoDestinoCidade` = ' $perm_redis->cidadeDestino', `Mensagem` = '$perm_redis->mensagem' 
                    WHERE `wp_perm_redis`.`perm_redis_id` = $id;                                    
                "
             );
        }

        public function deletar($id)
        {
            $this->wpdb->query(                
                "
                    DELETE FROM `".$this->wpdb->prefix."perm_redis` WHERE `perm_redis_id` = $id
                "
            );
        }

        public function buscar($id)
        {
            $resultados = $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis` WHERE `perm_redis_id` = $id" );
            if( sizeof($resultados) > 0)
                return PermutaReedistribuicao::fromDatabaseResult($resultados[0]);                

            return null;
        }

        public function listar()
        {
            $results = array();
            $resultados = $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis`" );
            if( sizeof($resultados) > 0)
            {
                foreach($resultados as $res){
                    array_push($results, PermutaReedistribuicao::fromDatabaseResult($res));
                }
            }         
            
            return $results;
        }
        
        private function inicializarBanco()
        {
            $sqlCreateTable = "CREATE  TABLE IF NOT EXISTS `".$this->wpdb->prefix."perm_redis` ( ".TABELA_REDIS_CAMPOS." )";

            $sqlCreateTableEstado = "CREATE TABLE IF NOT EXISTS `".$this->wpdb->prefix."perm_redis_estado` (
  							`perm_redis_estado_id` INT NOT NULL AUTO_INCREMENT , ".TABELA_REDIS_ESTADOS_CAMPOS." )";

            $sqlCreateTableCidade = "CREATE TABLE IF NOT EXISTS `".$this->wpdb->prefix."perm_redis_cidade` ( ".TABELA_REDIS_CIDADES_CAMPOS." )";
                          
            $this->wpdb->query($sqlCreateTable);//cria base para redistribuição
            $this->wpdb->query($sqlCreateTableEstado);            
            $this->wpdb->query($sqlCreateTableCidade);            
            
            $this->populateEstados();
            $this->populateCidades();
        }

        
        private function populateEstados()
        {
            $results = $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis_estado`" );
            if( sizeof($results) == 0 )
            {
                $this->wpdb->query( "INSERT INTO `".$this->wpdb->prefix."perm_redis_estado` ".INSERT_REDIS_ESTADOS );
            }
        }
        
        private function populateCidades()
        {
            $results = $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis_cidade`" );
            if( sizeof($results) == 0 )
            {
                $this->wpdb->query("INSERT INTO `".$this->wpdb->prefix."perm_redis_cidade` ".INSERT_REDIS_CIDADES_1);
                
                $this->wpdb->query("INSERT INTO `".$this->wpdb->prefix."perm_redis_cidade` ".INSERT_REDIS_CIDADES_2);

                $this->wpdb->query("INSERT INTO `".$this->wpdb->prefix."perm_redis_cidade` ".INSERT_REDIS_CIDADES_3);
            }
        }
    }
    ?>