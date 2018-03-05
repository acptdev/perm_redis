function httpRequest(_url, _type, _data, _callback){
    jQuery.ajax(
        {
            url: window.BASE_URL + _url,
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

function dateFormat(_d)
{
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