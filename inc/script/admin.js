function Admin(){

    var _self = this;

    var _init = function(){
        _loadRegistros();
    }

    var _loadRegistros = function()
    {
        showLoading();
        httpRequest("/registros","GET", 
            {}, 
            (_sucesso, _res) => {
                if(_sucesso)
                    _prepareTable(_res);
                else{
                    hideLoading();
                    showFeedback(_res)
                }
            }
        )
    }

    var _prepareTable = function(_data)
    {        
        for(var i = 0; i < _data.length; i++)
        {
            var _id = _data[i].id;
            var _row = jQuery('<tr>')
            .append(jQuery('<td>').text(_data[i].id))
            .append(jQuery('<td>').text(_data[i].nome))
            .append(jQuery('<td>').text(_data[i].email))
            .append(jQuery('<td>').text(_data[i].orgaoDestino))
            .append(jQuery('<td>').text(_data[i].cargo))
            .append(jQuery('<td>').text(dateFormat(_data[i].data)))
            .append(jQuery('<td>').append('<a class="editar" title="Editar" href="'+_id+'"><span class= "dashicons dashicons-edit" ></span></a> | ')
            .append('<a class="excluir" title="excluir" href="'+_id+'"><span class="dashicons dashicons-trash"></span></a>'));
            
            jQuery("#perm_redis_admin table tbody").append(_row);
        }
        
        jQuery("#perm_redis_admin table tbody a.editar").click(_editar);
        jQuery("#perm_redis_admin table tbody a.excluir").click(_excluir);
        
        hideLoading();
    }

    var _editar = function(){
        var _id = jQuery(this).attr('href');
        showLoading();
        httpRequest("/registro/" + _id, "GET",
            {},
            (_sucesso, _res) => {
                if (_sucesso) {
                    console.log(_res);
                    hideLoading();
                } else {
                    hideLoading();
                    showFeedback(_res);                    
                }
            }
        )
        return false;
    }

    var _excluir = function () {
        if( window.confirm("Deseja mesmo excluir este registro?") )
        {
            var _id = jQuery(this).attr('href');
            showLoading();
            httpRequest("/registro/"+_id, "DELETE",
                {},
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

    _init();
}
jQuery( document ).ready( () => {
    new Admin();
});