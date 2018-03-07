function Main(){

    var _self = this;
    var _registros = [];
    var _pessoaEditada = null;
    var _cargos = [];
    var _regioes = [];

    var _init = function(){        
        _loadRegioes();
    }   

    var _loadRegioes = function () {        

        httpRequest("/regioes/", "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {
                    _regioes = _res;
                    for (var i = 0; i < _res.length; i++) {
                        jQuery('.perm_redis_form #orgaoOrigem')
                            .append(jQuery('<option>').attr('value', _res[i].Id).text(_res[i].Nome));

                        jQuery('.perm_redis_form #orgaoDestino')
                            .append(jQuery('<option>').attr('value', _res[i].Id).text(_res[i].Nome));
                    }
                    _loadCargos();
                } else {
                    showFeedback(_res)
                }
            }
        );
    }

    var _loadCargos = function () {
        httpRequest("/cargos", "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {
                    _cargos = _res;
                    for (var i = 0; i < _cargos.length; i++) {
                        jQuery('.perm_redis_form #cargo')
                            .append(jQuery('<option>').attr('value', _cargos[i].Id).text(_cargos[i].Nome));
                    }
                    _loadRegistros();
                } else {
                    showFeedback(_res)
                }
            }
        );        
    }


    var _loadRegistros = function () {
        httpRequest("/registros-front", "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {
                    _registros = _res;
                   _prepareList();
                } else {
                    showFeedback(_res)
                }
            }
        )
    }

    /**
     * preparo de listagem
     */
    var _prepareList = function()
    {
        for(var i = 0; i < _registros.length; i++)
        {
            jQuery('#perm_redis_main .anuncios-content').append(`
                <div class="perm_redis_main-item-list tipo_${_registros[i].tipo}">
                    <div class="perm_redis_main-item-list-header text-right">
                        Cadastrada em: ${dateFormat(_registros[i].data)}
                    </div>
                    <div class="perm_redis_main-item-list-info">
                        <p><strong>Servidor:</strong> ${_registros[i].nome}</p>
                        <p><strong>Cargo:</strong> ${_cargo_by_id(_registros[i].cargo).Nome}</p>                        
                        <p><strong>Lotação Origem:</strong> ${_regiao_by_id(_registros[i].orgaoOrigem).Nome}</p>
                        <p><strong>Cidade Origem:</strong> ${_registros[i].origem}</p>
                        <p><strong>Orgão destino:</strong> ${_regiao_by_id(_registros[i].orgaoDestino).Nome}</p>                        
                        <p><strong>Cidade destino:</strong> ${_registros[i].destino}</p>                                                
                    </div>
                    <hr />
                    <div class="perm_redis_main-item-list-content">
                        <p><strong>Telefone:</strong> ${_registros[i].telefone}</p>
                        <p><strong>E-mail:</strong> ${_registros[i].email}</p>
                        <p><strong>Mensagem:</strong> ${_registros[i].mensagem}</p>
                    </div>
                </div>
            `);
        }
    }


    var _pessoa_by_id = function(_id){
        for (var i = 0; i < _registros.length; i++){
            if (_registros[i].id == _id) 
                return _registros[i];
        }
    }

    var _cargo_by_id = function (_id) {
        for (var i = 0; i < _cargos.length; i++) {
            if (_cargos[i].Id == _id)
                return _cargos[i];
        }
    }

    var _regiao_by_id = function (_id) {
        for (var i = 0; i < _regioes.length; i++) {
            if (_regioes[i].Id == _id)
                return _regioes[i];
        }
    }    
    var _tipo_by_id = function(_id)
    {
        return ["Não informado","Permuta", "Reedistribuição"][_id];
    }

    _init();
}
jQuery( document ).ready( () => {
    new Main();
});