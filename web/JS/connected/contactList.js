$('.contact').click(function (e) {
    var id = $(e.currentTarget).children('span').children('.notForUser').val();

    if ($(e.target).hasClass('mp'))
    {
        e.preventDefault();
        var dest = encodeURIComponent($(e.currentTarget).children('span').children('.contactName').text());
        openModal('?action=showSendingMessageForm&dest='+dest, $('#receptor'), function () {
            $('#sendForm').submit(function (event) {
                event.preventDefault();
                asynchronousTreatment('?action=sendMessage', new FormData(this), function(request){
                    var answer = JSON.parse(request.responseText);
                    if (answer['formOk']){
                        location.reload();
                    }else{
                        //todo treat and display error!
                    }
                });//didn't manage to make it work with jquery...
            })
        });
    }else if ($(e.target).hasClass('suppress')){
        e.preventDefault();
        var toSuppress = encodeURIComponent($(e.currentTarget).children('span').children('.contactName').text());
        openModal('?action=showAbandonForm&pseudo='+toSuppress, $('#receptor'));
    }else{
        window.location.replace('?action=getUserData&id='+id);
    }
    //window.location.replace('?action=getUserData&id='+id);
});