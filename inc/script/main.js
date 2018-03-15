angular.module("PermRedisApp", ['ngMask'])
    .config(function ($locationProvider){
        $locationProvider.html5Mode(true);
    })
    .controller("PermRedisAppController", function ($scope, $http, $q, $location, $timeout){        

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
                $scope.regioes = datas[0].data;
                $scope.cargos = datas[1].data;
                $scope.registros = datas[2].data;
                _loadEspecialidades(1);
                
            })
        }

        var _recuperarRegistro = function()
        {
            var hash = $location.search().chave;
            if( hash )
            {
                $scope.loaded = true;
                $http.post(`${window.BASE_URL}/registro-front`, { 'hash': hash})
                    .then( res => {
                        if(res.data.sucesso){
                            $scope.trocarAba('cadastro');
                            $scope.cadastro = res.data.registro                        
                            $scope.cadastro.cargo = parseInt($scope.cadastro.cargo);
                            $scope.cadastro.especialidade = parseInt($scope.cadastro.especialidade);
                            $scope.cadastro.orgaoOrigem = parseInt($scope.cadastro.orgaoOrigem);
                            $scope.cadastro.orgaoDestino = parseInt($scope.cadastro.orgaoDestino);                        
                            $scope.selecionarRegiaoDestino();
                            $scope.selecionarRegiaoOrigem();
                        }
                        $scope.loaded = false;
                    } )
                    .catch(error => {
                        $scope.loaded = true;
                    })

            }
        }

        var _loadEspecialidades = function(_id)
        {
            $http.get(`${window.BASE_URL}/especialidades/${_id}`)
                .then(
                    res => { 
                        $scope.especialidades = res.data;
                        $scope.cadastro.especialidade = $scope.especialidades[0].Id;
                        $scope.loaded = false;
                        _recuperarRegistro();
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

        $scope.excluirRegistro = function()
        {
            if( window.confirm('Confirma a exclusão deste registro?') )
            {
                $http.delete(`${window.BASE_URL}/registro/${$scope.cadastro.id}`)
                    .then( res => {
                        $scope.cadastro = {
                            tipo: "1",
                            orgaoOrigem: 1,
                            orgaoDestino: 1,
                            cargo: 1,
                        };
                        $scope.trocarAba('');
                        _loadAll();
                    }).catch(
                        err => window.alert("Erro ao tentar excluir, tente novamente!")
                    )
            }
        }
        $scope.cadastrar = function(event)
        {
            event.preventDefault();
            $scope.sendingCadastro = true;
            if(!$scope.cadastro.id)
            {
                $http.post(`${window.BASE_URL}/registro`, $scope.cadastro)
                    .then(
                        res => {
                            console.log(res);
                            if(res.data.sucesso)
                            {                            
                                $scope.cadastro = {
                                    tipo: "1",
                                    orgaoOrigem: 1,
                                    orgaoDestino: 1,
                                    cargo: 1,
                                };
                                $scope.trocarAba('');
                                window.alert("Cadastro realizado com sucesso");
                                _loadAll();
                            }else{
                                window.alert(res.data.data);
                            }
                            $scope.sendingCadastro = false;
                        }
                    ).catch(
                        error => {
                            
                            $scope.sendingCadastro = false;
                            window.alert("Erro ao cadastrar registro");
                        }
                    )
            }else{
                $http.put(`${window.BASE_URL}/registro/${$scope.cadastro.id}`, $scope.cadastro)
                    .then(
                        res => {                            
                            if (res.data.sucesso) {
                                $scope.cadastro = {
                                    tipo: "1",
                                    orgaoOrigem: 1,
                                    orgaoDestino: 1,
                                    cargo: 1,
                                };
                                $scope.trocarAba('');
                                window.alert("Cadastro alterado com sucesso");
                                _loadAll();
                            } else {
                                window.alert(res.data.data);
                            }
                            $scope.sendingCadastro = false;
                        }
                    ).catch(
                        error => {

                            $scope.sendingCadastro = false;
                            window.alert("Erro ao alterar registro");
                        }
                    )
            }
            return false;
        }

        $scope.selecionarRegiaoOrigem = function()
        {
            $http.get(`${window.BASE_URL}/estados/${$scope.cadastro.orgaoOrigem}`)
                .then(
                    res => { 
                        $scope.estadosOrigem = res.data;
                        $scope.cidadesOrigem = [];
                        if ($scope.cadastro.estadoDestino) {
                            $scope.mudarEstadoOrigem();
                        }
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
                        if($scope.cadastro.estadoDestino){
                            $scope.mudarEstadoDestino();
                        }
                    }
                )
        }
        $scope.mudarEstadoOrigem = function()
        {
            $http.get(`${window.BASE_URL}/cidades/${$scope.cadastro.estadoOrigem}`)
                .then( res => {

                    $scope.cidadesOrigem = res.data;
                    if(!$scope.cadastro.cidadeOrigem)
                        $scope.cadastro.cidadeOrigem = $scope.cidadesOrigem[0].perm_redis_cidade_id;
                });

        }
        $scope.mudarEstadoDestino = function () {            
            $http.get(`${window.BASE_URL}/cidades/${$scope.cadastro.estadoDestino}`)
                .then(res => {

                    $scope.cidadesDestino = res.data;
                    if(!$scope.cadastro.cidadeDestino)
                        $scope.cadastro.cidadeDestino = $scope.cidadesDestino[0].perm_redis_cidade_id;
                });
        }
        $scope.checkTelefone = function()
        {
            if ($scope.cadastro.telefone.length != 15 && $scope.cadastro.telefone.length != 14)
                $scope.cadastro.telefone = "";
        }
        $scope.checkCPF = function () {
            if ($scope.cadastro.cpf.length != 14)
                $scope.cadastro.cpf = "";
        }
        _init();
    });