angular.module("PermRedisApp", ['ui.utils.masks'])
    .controller("PermRedisAppController",function($scope, $http, $q){

        $scope.loaded = true;
        $scope.regioes = [];
        $scope.cargos = [];
        $scope.registros = [];
        $scope.tipoRegistro = "0";
        $scope.tipoAba = "";
        $scope.lotacaoOrigem = 1;
        $scope.lotacaoDestino = 1;
        $scope.emailRecuperacao = "";
        $scope.sendingEdicao = false;
        $scope.especialidades = [];
        $scope.sendingCadastro = false;
        $scope.cadastro = {
            tipo: "1",
            orgaoOrigem: 1,
            orgaoDestino: 1,
            cargo: 1,
        };

        var _init = function()
        {
            _loadAll();
        }

        var _loadAll = function()
        {
            $q.all([
                $http.get(`${window.BASE_URL}/regioes`),
                $http.get(`${window.BASE_URL}/cargos`),
                $http.get(`${window.BASE_URL}/registros-front`)
            ]).then( datas => {
                $scope.loaded = false;
                $scope.regioes = datas[0].data;
                $scope.cargos = datas[1].data;
                $scope.registros = datas[2].data;
                _loadEspecialidades(1);
            })
        }

        var _loadEspecialidades = function(_id)
        {
            $http.get(`${window.BASE_URL}/especialidades/${_id}`)
                .then(
                    res => { 
                        $scope.especialidades = res.data;
                        $scope.cadastro.especialidade = $scope.especialidades[0].Id;
                    }
                )
        }

        $scope.trocarAba = function(_val)
        {
            $scope.tipoAba = $scope.tipoAba == _val ? '' : _val;
            if($scope.tipoAba == ""){
                $scope.lotacaoOrigem = 1;
                $scope.lotacaoDestino = 1;
                $scope.cadastro = {
                    tipo: "1",
                    orgaoOrigem: 1,
                    orgaoDestino: 1,
                    cargo: 1,
                };
            }
        }

        $scope.edicaoPerfil = function(event)
        {
            event.preventDefault();
            $scope.sendingEdicao = true;
            $http.post(`${window.BASE_URL}/registro-edicao`, { "email": $scope.emailRecuperacao})
                .then( res => {
                    $scope.emailRecuperacao = "";
                    window.alert('Email enviado com sucesso!');
                    $scope.sendingEdicao = false;
                }).catch(error => { $scope.sendingEdicao = false })
        }

        $scope.cadastrar = function()
        {
            $scope.sendingCadastro = true;
            $http.post(`${window.BASE_URL}/registro`, $scope.cadastro)
                .then(
                    res => {
                        $scope.sendingCadastro = false;
                        $scope.cadastro = {
                            tipo: "1",
                            orgaoOrigem: 1,
                            orgaoDestino: 1,
                            cargo: 1,
                        };
                        $scope.trocarAba('');
                        window.alert("Cadastro realizado com sucesso");
                    }
                ).catch(
                    error => {
                        $scope.sendingCadastro = false;
                        window.alert("Erro ao cadastrar registro");
                    }
                )
        }

        $scope.selecionarRegiaoOrigem = function()
        {
            $http.get(`${window.BASE_URL}/estados/${$scope.cadastro.orgaoOrigem}`)
                .then(
                    res => { 
                        $scope.estadosOrigem = res.data;
                        $scope.cidadesOrigem = [];
                    }
                )
            
        }
        $scope.selecionarRegiaoDestino = function()
        {
            $http.get(`${window.BASE_URL}/estados/${$scope.cadastro.orgaoDestino}`)
                .then(
                    res => { 
                        $scope.estadosDestino = res.data;
                        $scope.cidadesDestino = [];
                    }
                )
        }
        $scope.mudarEstadoOrigem = function()
        {
            $http.get(`${window.BASE_URL}/cidades/${$scope.cadastro.estadoOrigem}`)
                .then( res => {

                    $scope.cidadesOrigem = res.data;
                    $scope.cadastro.cidadeOrigem = $scope.cidadesOrigem[0].perm_redis_cidade_id;
                });

        }
        $scope.mudarEstadoDestino = function () {            
            $http.get(`${window.BASE_URL}/cidades/${$scope.cadastro.estadoDestino}`)
                .then(res => {

                    $scope.cidadesDestino = res.data;
                    $scope.cadastro.cidadeDestino = $scope.cidadesDestino[0].perm_redis_cidade_id;
                });
        }
        _init();
    });