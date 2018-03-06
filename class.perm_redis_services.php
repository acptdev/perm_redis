<?php
    class PermRedisService{
        
        private $dao;
        public function __construct()
        {
            $this->dao = new PermRedisDAO();
            $this->iniciarRestApi();
        }

        private function iniciarRestApi()
        {
            
            add_action('rest_api_init', function($server){

                $server->register_route( "list", '/perm-redis/registros', array(
                    array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'listar' )
                        )
                    ) 
                );

                $server->register_route( "list_estados", '/perm-redis/estados', array(
                    array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'list_estados' )
                        )
                    ) 
                );

                $server->register_route( "list_estados_id", '/perm-redis/estados/(?P<id>[0-9]+)', array(
                        array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'list_estados' ),           
                            'args'           => array(
                                'id' => array(
                                    'validate_callback' => function($param, $request, $key){
                                        return is_numeric($param);
                                    }
                                )
                            )
                        )
                    )
                );

                $server->register_route( "list_cidades", '/perm-redis/cidades/(?P<id>[0-9]+)', array(
                        array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'list_cidades' ),           
                            'args'           => array(
                                'id' => array(
                                    'validate_callback' => function($param, $request, $key){
                                        return is_numeric($param);
                                    }
                                )
                            )
                        )
                    )
                );

                $server->register_route( "list_regioes", '/perm-redis/regioes', array(
                    array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'list_regioes' )
                        )
                    ) 
                );

                $server->register_route( "list_cargos", '/perm-redis/cargos', array(
                    array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'list_cargos' )
                        )
                    ) 
                );

                $server->register_route( "dao", '/perm-redis/especialidades/(?P<id>[0-9]+)', array(
                        array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'list_especialidades' ),           
                            'args'           => array(
                                'id' => array(
                                    'validate_callback' => function($param, $request, $key){
                                        return is_numeric($param);
                                    }
                                )
                            )
                        )
                    )
                );

                $server->register_route( "registro", '/perm-redis/registro', array(
                        array(
                            'methods'         => "POST",
                            'callback'        => array( $this, 'persist_registro' )                            
                        ),
                    )
                );

                $server->register_route( "dao", '/perm-redis/registro/(?P<id>[0-9]+)', array(
                        array(
                            'methods'         => "PUT",
                            'callback'        => array( $this, 'persist_registro' ),           
                            'args'           => array(
                                'id' => array(
                                    'validate_callback' => function($param, $request, $key){
                                        return is_numeric($param);
                                    }
                                )
                            )
                        ),
                        array(
                            'methods'         => WP_REST_Server::DELETABLE,
                            'callback'        => array( $this, 'deletar' ),           
                            'args'           => array(
                                'id' => array(
                                    'validate_callback' => function($param, $request, $key){
                                        return is_numeric($param);
                                    }
                                )
                            )
                        ),
                        array(
                            'methods'         => WP_REST_Server::READABLE,
                            'callback'        => array( $this, 'buscar_por_id' ),           
                            'args'           => array(
                                'id' => array(
                                    'validate_callback' => function($param, $request, $key){
                                        return is_numeric($param);
                                    }
                                )
                            )
                        )
                    ) 
                );
            });
        }

        public function listar($request)
        {         
            return $this->dao->listar();
        }

        public function buscar_por_id($request)
        {
            $parameters = $request->get_params();            
            return $this->dao->buscar($parameters['id']);
        }

        public function deletar($request)
        {
            $parameters = $request->get_params();

            $this->dao->deletar($parameters['id']);
            
            $result[] = array(
                'sucesso' => true,
                'data' => "removido com sucesso!"
            );

            return $result;
        }

        public function persist_registro($request)
        {            
            $parameters = $request->get_params();

            $permutaReedistribuicao = PermutaReedistribuicao::fromQueryParams($parameters);
            $mensagem = "";

            if(@$parameters['id'])
            {
                $this->dao->atualizar($permutaReedistribuicao, $parameters['id']);
                $mensagem = "Atualização realizada com sucesso!";
                
            }else{
                $this->dao->inserir($permutaReedistribuicao);
                $mensagem = "Cadastro realizado com sucesso!";
            }

            $result[] = array(
                'sucesso' => true,
                'data' => $mensagem
            );

            return $result;
        }       

        public function list_estados($request)
        {
            $parameters = $request->get_params();

            if(@$parameters['id'] && $parameters['id'] != 1){                             
                $estados = REDIS_REGIOES[$parameters['id'] - 1]['Estados'];
                return $this->dao->listarEstadosPorRegiao($estados);
            }else{
                return $this->dao->listarEstados();
            }            
        }

        public function list_cidades($request)
        {
            $parameters = $request->get_params();
            return $this->dao->listarCidades(@$parameters['id']);
        }

        public function list_cargos()
        {
            return REDIS_CARGOS;
        }

        public function list_especialidades($request)
        {
            $parameters = $request->get_params();
            if(@$parameters['id']){                                             
                return REDIS_ESPECIALIDADES[$parameters['id'] - 1];
            }else{
                return null;
            }
        }

        public function list_regioes()
        {
            return REDIS_REGIOES;
        }
        
    }

?>