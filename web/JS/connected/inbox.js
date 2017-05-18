$('#writeNewMessage').click(function (e) {
    e.preventDefault();
    openModal('?action=showSendingMessageForm', $('#receptor'), function () {
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
});
