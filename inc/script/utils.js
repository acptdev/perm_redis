function httpRequest(_url, _type, _data, _callback){
    $.ajax(
        {
            url: "http://localhost:8080/" + _url,
            type: _type,
            contentType: 'json',
            data: _data,
            success: function(res){
                _callback(true, res)
            },
            error(xhr, status, error){
                _callback(false, error);
            }
        }
    )
}