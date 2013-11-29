$(document).ready(function(){
    setInterval(function(){
        $.ajax({
            url: $("#infos").data('rel')
        }).done(function(response) {
            $('#content').html(response);
        });
    }, $('#infos').data('timer') * 1000);
});