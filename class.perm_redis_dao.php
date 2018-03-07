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
                        (`perm_redis_id`, `Tipo`, `Nome`, `Email`, `CPF`, `Cargo`, `Especialidade`, `Matricula`, `Telefone`, `OrgaoOrigem`, `OrgaoEstado`, 
                        `OrgaoCidade`, `OrgaoDestino`, `OrgaoDestinoEstado`, `OrgaoDestinoCidade`, `Mensagem`) 
                        VALUES (NULL, '$perm_redis->tipo', '$perm_redis->nome', '$perm_redis->email', '$perm_redis->cpf', '$perm_redis->cargo', 
                            '$perm_redis->especialidade', '$perm_redis->matricula', '$perm_redis->telefone', '$perm_redis->orgaoOrigem', 
                            '$perm_redis->estadoOrigem', '$perm_redis->cidadeOrigem', '$perm_redis->orgaoDestino',
                            '$perm_redis->estadoDestino', '$perm_redis->cidadeDestino', '$perm_redis->mensagem');
                "
             );
        }

        public function atualizar(PermutaReedistribuicao $perm_redis, $id)
        {
            $this->wpdb->query(
                "    
                    UPDATE `".$this->wpdb->prefix."perm_redis` 
                    SET `Tipo` = '$perm_redis->tipo', `Nome` = '$perm_redis->nome', `Email` = '$perm_redis->email', 
                    `CPF` = '$perm_redis->cpf', `Cargo` = '$perm_redis->cargo', `Especialidade` = '$perm_redis->especialidade', `Matricula` = '$perm_redis->matricula', 
                    `Telefone` = '$perm_redis->telefone', `OrgaoOrigem` = '$perm_redis->orgaoOrigem', `OrgaoEstado` = '$perm_redis->estadoOrigem', 
                    `OrgaoCidade` = '$perm_redis->cidadeOrigem', `OrgaoDestino` = '$perm_redis->orgaoDestino', 
                    `OrgaoDestinoEstado` = '$perm_redis->estadoDestino', `OrgaoDestinoCidade` = ' $perm_redis->cidadeDestino', 
                    `Mensagem` = '$perm_redis->mensagem' 
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

        public function listar($isFront = false)
        {
            $results = array();
            $resultados = $this->wpdb->get_results("SELECT *, c1.Nome as CidadeOrigem, c2.Nome as CidadeDestino, e1.Nome as EstadoOrigem, e2.Nome as EstadoDestino 
            FROM `".$this->wpdb->prefix."perm_redis` p
            LEFT JOIN ".$this->wpdb->prefix."perm_redis_cidade c1 ON c1.perm_redis_cidade_id = p.OrgaoCidade
            LEFT JOIN ".$this->wpdb->prefix."perm_redis_cidade c2 ON c2.perm_redis_cidade_id = p.OrgaoDestinoCidade
            LEFT JOIN ".$this->wpdb->prefix."perm_redis_estado e1 ON e1.perm_redis_estado_id = p.OrgaoEstado
            LEFT JOIN ".$this->wpdb->prefix."perm_redis_estado e2 ON e2.perm_redis_estado_id = p.OrgaoDestinoEstado
            ORDER BY `perm_redis_id` DESC");
            //$resultados = $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis` ORDER BY `perm_redis_id` DESC" );
            if( sizeof($resultados) > 0)
            {
                foreach($resultados as $res){
                    array_push($results, PermutaReedistribuicao::fromDatabaseResult($res, $isFront));
                }
            }         
            
            return $results;
        }

        public function listarEstadosPorRegiao($regioes){
            
            $estados = implode(",",$regioes);
            return $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis_estado` WHERE `perm_redis_estado_id` IN ($estados)" );
        }

        public function listarEstados()
        {
            return $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis_estado`" );
        }            

        public function listarCidades($estado)
        {
            return $this->wpdb->get_results( "SELECT * FROM `".$this->wpdb->prefix."perm_redis_cidade` WHERE estado_id = $estado" );
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