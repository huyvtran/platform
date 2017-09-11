// Get online by server
$('#box-online-by-server').boxRefresh({
    source: 'http://gmtool.dev/online-by-server.json',
    responseType: 'json',
    loadInContent: false,
    onLoadStart: function() {},
    onLoadDone: function(response) {
        loadOnlineByServer(response);
    }
});

function loadOnlineByServer(response) {
    var text = '';
    var total = 0;

    for (i = 0; i < response.length; i++) {
        text += '<div class="progress" style="background-color: #B8B8B8">' +
            '<div class="progress-bar" role="progressbar" style="width:' + (response[i]['Online'] / 300 * 100) + '%">' +
            '<span style="min-width: 200px; display: block; text-align: left; padding-left: 5px; font-weight: bold;">' +
            response[i]['ServerName'] +
            ' - ' +
            response[i]['Online'] +
            '</span>' +
            '</div>' +
            '</div>';

        total += response[i]['Online'];
    }
    $('#list-online-by-server').html(text);
    $('#total-online-by-server').text(total);
}