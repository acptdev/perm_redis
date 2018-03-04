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
                            'callback'        => array( $this, 'list_regioes' )
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
                            'callback'        => array( $this, 'persist_registro' ),           
                            'args'           => array(
                                'id' => array(
                                    'validate_callback' => function($param, $request, $key){
                                        return is_numeric($param);
                                    }
                                )
                            )
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

        public function list_cargos()
        {
            return [
                [ "Id" => 1, "Nome" => "I - Analista Judiciário" ],
                [ "Id" => 2, "Nome" => "II - Técnico Judiciário" ],
                [ "Id" => 3, "Nome" => "III - Auxiliar Judiciário" ]
            ];
        }

        public function list_especialidades($id)
        {
            $especialidades = [
                [
                    ["Id" => 1, "Nome" => "Especialidade"],
                    [ "Id" => 2, "Nome" => "Não especificar"],
                    [ "Id" => 3, "Nome" => "Arquitetura"],
                    [ "Id" => 4, "Nome" => "Arquivologia"],
                    [ "Id" => 5, "Nome" => "Biblioteconomia"],
                    [ "Id" => 6, "Nome" => "Comunicação Social"],
                    [ "Id" => 7, "Nome" => "Comunicação Social (Jornalismo)"],
                    [ "Id" => 8, "Nome" => "Comunicação Social (Publicidade e Propaganda)"],
                    [ "Id" => 9, "Nome" => "Comunicação Social (Rádio e TV)"],
                    [ "Id" => 10, "Nome" => "Comunicação Social (Relações Públicas)"],
                    [ "Id" => 11, "Nome" => "Contabilidade"],
                    [ "Id" => 12, "Nome" => "Educação Física"],
                    [ "Id" => 13, "Nome" => "Enfermagem"],
                    [ "Id" => 14, "Nome" => "Engenharia"],
                    [ "Id" => 15, "Nome" => "Engenharia (Engenharia Civil)"],
                    [ "Id" => 16, "Nome" => "Engenharia (Engenharia Elétrica)"],
                    [ "Id" => 17, "Nome" => "Engenharia (Engenharia Mecânica)"],
                    [ "Id" => 18, "Nome" => "Engenharia (Segurança do Trabalho)"],
                    [ "Id" => 19, "Nome" => "Estatística"],
                    [ "Id" => 20, "Nome" => "Execução de Mandados"],
                    [ "Id" => 21, "Nome" => "Fisioterapia"],
                    [ "Id" => 22, "Nome" => "Historiador"],
                    [ "Id" => 23, "Nome" => "Medicina"],
                    [ "Id" => 24, "Nome" => "Medicina (Cardiologia)"],
                    [ "Id" => 25, "Nome" => "Medicina (Medicina do Trabalho)"],
                    [ "Id" => 26, "Nome" => "Medicina (Psiquiatria)"],
                    [ "Id" => 27, "Nome" => "Nutrição"],
                    [ "Id" => 28, "Nome" => "Odontologia"],
                    [ "Id" => 29, "Nome" => "Odontologia (Endodontia)"],
                    [ "Id" => 30, "Nome" => "Odontologia (Pediatria)"],
                    [ "Id" => 31, "Nome" => "Odontologia (Prótese)"],
                    [ "Id" => 32, "Nome" => "Oficial de Justiça Avaliador Federal"],
                    [ "Id" => 33, "Nome" => "Pedagogia"],
                    [ "Id" => 34, "Nome" => "Psicologia"],
                    [ "Id" => 35, "Nome" => "Serviço Social"],
                    [ "Id" => 36, "Nome" => "Tecnologia da Informação"],
                    [ "Id" => 37, "Nome" => "Tecnologia da Informação (Análise de Sistema)"]                
                ],
                [
                    [ "Id" => 38, "Nome" => "Especialidade"],
                    [ "Id" => 39, "Nome" => "Não especificar"],
                    [ "Id" => 40, "Nome" => "Agente de segurança"],
                    [ "Id" => 41, "Nome" => "Enfermagem"],
                    [ "Id" => 42, "Nome" => "Higiene Dental"],
                    [ "Id" => 43, "Nome" => "Operação de Computador"],
                    [ "Id" => 44, "Nome" => "Programação de Sistemas"],
                    [ "Id" => 45, "Nome" => "Tecnologia da Informação"],
                    [ "Id" => 46, "Nome" => "Telecomunicações e Eletricidade"]
                ],
                [
                    [ "Id" => 47, "Nome" => "Especialidade"],
                    [ "Id" => 48, "Nome" => "Não especificar"]
                ]
            ];
        }

        public function list_regioes()
        {
            return [
                [ "Id" => 1, "Nome" => "Região"],
                [ "Id" => 2, "Nome" => "TRT 01ª REGIÃO"],
                [ "Id" => 3, "Nome" => "TRT 02ª REGIÃO"],
                [ "Id" => 4, "Nome" => "TRT 03ª REGIÃO"],
                [ "Id" => 5, "Nome" => "TRT 04ª REGIÃO"],
                [ "Id" => 6, "Nome" => "TRT 05ª REGIÃO"],
                [ "Id" => 7, "Nome" => "TRT 06ª REGIÃO"],
                [ "Id" => 8, "Nome" => "TRT 07ª REGIÃO"],
                [ "Id" => 9, "Nome" => "TRT 08ª REGIÃO"],
                [ "Id" => 10, "Nome" => "TRT 09ª REGIÃO"],
                [ "Id" => 11, "Nome" => "TRT 10ª REGIÃO"],
                [ "Id" => 12, "Nome" => "TRT 11ª REGIÃO"],
                [ "Id" => 13, "Nome" => "TRT 12ª REGIÃO"],
                [ "Id" => 14, "Nome" => "TRT 13ª REGIÃO"],
                [ "Id" => 15, "Nome" => "TRT 14ª REGIÃO"],
                [ "Id" => 16, "Nome" => "TRT 15ª REGIÃO"],
                [ "Id" => 17, "Nome" => "TRT 16ª REGIÃO"],
                [ "Id" => 18, "Nome" => "TRT 17ª REGIÃO"],
                [ "Id" => 19, "Nome" => "TRT 18ª REGIÃO"],
                [ "Id" => 20, "Nome" => "TRT 19ª REGIÃO"],
                [ "Id" => 21, "Nome" => "TRT 20ª REGIÃO"],
                [ "Id" => 22, "Nome" => "TRT 21ª REGIÃO"],
                [ "Id" => 23, "Nome" => "TRT 22ª REGIÃO"],
                [ "Id" => 24, "Nome" => "TRT 23ª REGIÃO"],
                [ "Id" => 25, "Nome" => "TRT 24ª REGIÃO"],
                [ "Id" => 26, "Nome" => "TST"],
                [ "Id" => 27, "Nome" => "CSJT"]
            ];
        }
        
    }

?>