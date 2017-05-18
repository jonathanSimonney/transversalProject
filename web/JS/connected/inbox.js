sessionStorage.clear();

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

$('#suppressEmail').click(function () {
    $.each($('.checkboxMailId'), function (key, value) {
        if (value.checked){
            jqueryAsynchronousTreatment('?action=suppressMessage', {'notForUser': $(value).val()}, debugAnswer, function (serverData) {
                $(value).parent().remove();
            });
        }
    })
});