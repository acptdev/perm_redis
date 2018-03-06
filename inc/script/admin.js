function Admin(){

    var _self = this;
    var _pessoas = [];
    var _pessoaEditada = null;
    var _cargos = [];
    var _regioes = [];

    var _init = function(){        
        _loadRegioes();
        _prepareEvents();
    }
    
    var _prepareEvents = function()
    {
        jQuery('.perm_redis_form #orgaoOrigem').change(_loadEstadosOrigem);
        jQuery('.perm_redis_form #orgaoDestino').change(_loadEstadosDestino);
        jQuery('.perm_redis_form #estadoDestino').change(_loadCidadesDestino);
        jQuery('.perm_redis_form #estadoOrigem').change(_loadCidadesOrigem);
        jQuery('.perm_redis_form form').submit(_alterarCadastro);
        jQuery('.perm_redis_form a.close, .perm_redis_form button[type=button]').click(_closeModalForm);
    }

    var _loadRegioes = function () {
        showLoading();
        jQuery('.perm_redis_form #orgaoOrigem').empty();
        jQuery('.perm_redis_form #orgaoDestino').empty();

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
        )
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
        )
        jQuery('.perm_redis_form #cargo').change(_loadEspecialidades);
    }
    

    var _loadRegistros = function()
    {        
        httpRequest("/registros","GET", 
            null, 
            (_sucesso, _res) => {
                if(_sucesso){
                    _pessoas = _res;
                    _prepareTable();
                }else{
                    hideLoading();
                    showFeedback(_res)
                }
            }
        )
    }

    var _loadEstadosOrigem = function(){

        jQuery('.perm_redis_form #estadoOrigem').html('<option value="0">selecione</option>');        
        jQuery('.perm_redis_form #cidadeOrigem').empty();
        var id = jQuery('.perm_redis_form #orgaoOrigem').val();
        httpRequest("/estados/"+id, "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {                    
                    for (var i = 0; i < _res.length; i++) {
                        jQuery('.perm_redis_form #estadoOrigem')
                            .append(jQuery('<option>').attr('value', _res[i].perm_redis_estado_id).text(_res[i].Nome));

                    }
                        if(_pessoaEditada.estadoOrigem){
                            jQuery('.perm_redis_form #estadoOrigem').val(_pessoaEditada.estadoOrigem);
                            _loadCidadesOrigem();
                        }
                        if(id != _pessoaEditada.orgaoOrigem)
                            _pessoaEditada.estadoOrigem = null;
                } else {
                    showFeedback(_res)
                }
            }
        )
        
    }
    var _loadEstadosDestino = function () {

        jQuery('.perm_redis_form #estadoDestino').html('<option value="0">selecione</option>');
        jQuery('.perm_redis_form #cidadeDestino').empty();
        var id = jQuery('.perm_redis_form #orgaoDestino').val();
        
        httpRequest("/estados/" + id, "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {                    
                    for (var i = 0; i < _res.length; i++) {
                        jQuery('.perm_redis_form #estadoDestino')
                            .append(jQuery('<option>').attr('value', _res[i].perm_redis_estado_id).text(_res[i].Nome));

                    }
                    if (_pessoaEditada.estadoDestino) {
                        jQuery('.perm_redis_form #estadoDestino').val(_pessoaEditada.estadoDestino);
                         _loadCidadesDestino();
                    }
                    if (id != _pessoaEditada.orgaoDestino)
                        _pessoaEditada.estadoDestino = null;
                } else {
                    showFeedback(_res)
                }
            }
        )
        
    }

    var _loadCidadesOrigem = function () {

        var id = jQuery('.perm_redis_form #estadoOrigem').val();
        if(!id) return;

        jQuery('.perm_redis_form #cidadeOrigem').html('<option value="0">selecione</option>');

        httpRequest("/cidades/" + id, "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {                    
                    for (var i = 0; i < _res.length; i++) {
                        jQuery('.perm_redis_form #cidadeOrigem')
                            .append(jQuery('<option>').attr('value', _res[i].perm_redis_cidade_id).text(_res[i].Nome));

                    }
                    if (_pessoaEditada.cidadeOrigem) {
                        jQuery('.perm_redis_form #cidadeOrigem').val(_pessoaEditada.cidadeOrigem);
                    }
                    if (id != _pessoaEditada.estadoOrigem)
                        _pessoaEditada.cidadeOrigem = null;
                } else {
                    showFeedback(_res)
                }
            }
        )

    }

    var _loadCidadesDestino = function () {
        
        var id = jQuery('.perm_redis_form #estadoDestino').val();
        if (!id) return;

        jQuery('.perm_redis_form #cidadeDestino').html('<option value="0">selecione</option>');

        httpRequest("/cidades/" + id, "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {                    
                    for (var i = 0; i < _res.length; i++) {
                        jQuery('.perm_redis_form #cidadeDestino')
                            .append(jQuery('<option>').attr('value', _res[i].perm_redis_cidade_id).text(_res[i].Nome));

                    }
                        if (_pessoaEditada.cidadeDestino) {
                            jQuery('.perm_redis_form #cidadeDestino').val(_pessoaEditada.cidadeDestino);
                        }
                        if (id != _pessoaEditada.estadoDestino)
                            _pessoaEditada.cidadeDestino = null;
                } else {
                    showFeedback(_res)
                }
            }
        )

    }    

    var _loadEspecialidades = function()
    {
        showLoading();
        jQuery('.perm_redis_form #especialidade').empty();
        var _cargoId = jQuery('.perm_redis_form #cargo').val();
        httpRequest("/especialidades/" + _cargoId, "GET",
            null,
            (_sucesso, _res) => {
                if (_sucesso) {
                    hideLoading();
                    for (var i = 0; i < _res.length; i++) {
                        jQuery('.perm_redis_form #especialidade')
                            .append(jQuery('<option>').attr('value',_res[i].Id).text(_res[i].Nome));
                    }                 
                    if (_pessoaEditada.cargo != _cargoId)   
                    {
                        _pessoaEditada.especialidade = null;
                    }
                    if (_pessoaEditada.especialidade){
                        jQuery('.perm_redis_form #especialidade').val(_pessoaEditada.especialidade);
                    }
                } else {
                    showFeedback(_res)
                }
            }
        )
    }    

    var _prepareTable = function()
    {       
        jQuery("#perm_redis_admin table tbody").empty(); 
        if(_pessoas.length)
        {
            for(var i = 0; i < _pessoas.length; i++)
            {
                var _id = _pessoas[i].id;
                var _row = jQuery('<tr>')
                .append(jQuery('<td>').text(_pessoas[i].id))
                .append(jQuery('<td>').text(_tipo_by_id(_pessoas[i].tipo)))
                .append(jQuery('<td>').text(_pessoas[i].nome))
                .append(jQuery('<td>').text(_pessoas[i].email))
                .append(jQuery('<td>').text(_regiao_by_id(_pessoas[i].orgaoOrigem).Nome))
                .append(jQuery('<td>').text(_regiao_by_id(_pessoas[i].orgaoDestino).Nome))
                .append(jQuery('<td>').text(_cargo_by_id(_pessoas[i].cargo).Nome))
                .append(jQuery('<td>').text(dateFormat(_pessoas[i].data)))
                .append(jQuery('<td>').append('<a class="editar" title="Editar" href="'+_id+'"><span class= "dashicons dashicons-edit" ></span></a> | ')
                .append('<a class="excluir" title="excluir" href="'+_id+'"><span class="dashicons dashicons-trash"></span></a>'));
                
                jQuery("#perm_redis_admin table tbody").append(_row);
            }
            
            jQuery("#perm_redis_admin table tbody a.editar").click(_editar);
            jQuery("#perm_redis_admin table tbody a.excluir").click(_excluir);
            
        }
        
        hideLoading();
    }

    var _editar = function(){
        var _id = jQuery(this).attr('href');
        _showForm(_pessoa_by_id(_id));
        return false;
    }

    var _showForm = function(_pessoaData){
        _pessoaEditada = JSON.parse(JSON.stringify(_pessoaData));;
        jQuery('.perm_redis_form #tipo').val(_pessoaData.tipo);
        jQuery('.perm_redis_form #nome').val(_pessoaData.nome);
        jQuery('.perm_redis_form #email').val(_pessoaData.email);
        jQuery('.perm_redis_form #cargo').val(_pessoaData.cargo);
        jQuery('.perm_redis_form #cpf').val(_pessoaData.cpf);
        jQuery('.perm_redis_form #matricula').val(_pessoaData.matricula);
        jQuery('.perm_redis_form #telefone').val(_pessoaData.telefone);
        jQuery('.perm_redis_form #mensagem').val(_pessoaData.mensagem);
        jQuery('.perm_redis_form #orgaoOrigem').val(_pessoaData.orgaoOrigem);
        jQuery('.perm_redis_form #orgaoDestino').val(_pessoaData.orgaoDestino);
        _loadEspecialidades();
        _loadEstadosOrigem();
        _loadEstadosDestino();
        jQuery('.perm_redis_form').fadeIn();
    }

    var _closeModalForm = function()
    {        
        _pessoaEditada = null;
        jQuery('.perm_redis_form').fadeOut();
        return false;        
    }

    var _excluir = function () {
        if( window.confirm("Deseja mesmo excluir este registro?") )
        {
            var _id = jQuery(this).attr('href');
            showLoading();
            httpRequest("/registro/"+_id, "DELETE",
                null,
                (_sucesso, _res) => {
                    if (_sucesso){
                        jQuery(this).parent().parent().remove();
                        hideLoading();
                    }else {
                        hideLoading();
                        showFeedback(_res)
                    }
                }
            )
        }
        return false;
    }

    var _alterarCadastro = function(event){
        event.preventDefault();
        var _data = jQuery(this).serializeJSON();        
        showLoading();
        httpRequest("/registro/"+_pessoaEditada.id, 
            "PUT",
            _data,
            (_sucesso, _res) => {
                if (_sucesso) {
                    _closeModalForm();
                    _loadRegistros();
                } else {
                    hideLoading();
                    showFeedback(_res)
                }
            }
        )
    }

    var _pessoa_by_id = function(_id){
        for (var i = 0; i < _pessoas.length; i++){
            if (_pessoas[i].id == _id) 
                return _pessoas[i];
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
    new Admin();
});