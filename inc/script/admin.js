function Admin(){

    var _self = this;

    var _init = function(){
        _loadRegistros();
    }

    var _loadRegistros = function()
    {
        httpRequest("mock/categoria.json","GET", 
            {}, 
            (_sucesso, _res) => {
                if(_sucesso)
                    window.alert(_res.data)
                else
                    window.alert(_res)
            }
        )
    }

    _init();
}

new Admin();