function httpRequest(_url, _type, _data, _callback){
    _data = _data ? JSON.stringify(_data) : '';
    jQuery.ajax(
        {
            url: window.BASE_URL + _url,
            type: _type,
            contentType: "application/json; charset=utf-8",
            dataType: 'json',
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

function dateFormat(_d)
{
    if(!_d) return 'não informado';
    
    var _arrD = _d.split(' ');
    var _arrDate = _arrD[0].split('-');

    return _arrDate[2] + '/' + _arrDate[1] + '/' + _arrDate[0] +' '+ _arrD[1];
}

function showFeedback(_message)
{
    window.alert(_message);
}

function showLoading()
{
    jQuery('.perm_redisLoading').fadeIn();
}

function hideLoading() {
    jQuery('.perm_redisLoading').fadeOut();
}

jQuery.fn.serializeJSON = function () {
    var json = {};
    jQuery.map(jQuery(this).serializeArray(), function (n, i) {
        var _ = n.name.indexOf('[');
        if (_ > -1) {
            var o = json;
            _name = n.name.replace(/\]/gi, '').split('[');
            for (var i = 0, len = _name.length; i < len; i++) {
                if (i == len - 1) {
                    if (o[_name[i]]) {
                        if (typeof o[_name[i]] == 'string') {
                            o[_name[i]] = [o[_name[i]]];
                        }
                        o[_name[i]].push(n.value);
                    }
                    else o[_name[i]] = n.value || '';
                }
                else o = o[_name[i]] = o[_name[i]] || {};
            }
        }
        else {
            if (json[n.name] !== undefined) {
                if (!json[n.name].push) {
                    json[n.name] = [json[n.name]];
                }
                json[n.name].push(n.value || '');
            }
            else json[n.name] = n.value || '';
        }
    });
    return json;
};