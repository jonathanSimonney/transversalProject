sessionStorage.clear();

function savePseudo(serverData){
    if (serverData['formOk'] === true){
        sessionStorage.setItem('?action=getSignInModal', JSON.stringify({'username' : $('#username').val()}));
        $('#signIn').click();
    }
}

$('#signIn').click(function (e) {
    e.preventDefault();
    openModal('?action=getSignInModal', $('#modalWaiter'), function(){
        $('#signUpModal').click(function () {
            $('#signUp').click();
        });
        $('#modalForm').submit(function (e) {
            e.preventDefault();
            jqueryAsynchronousTreatment('?action=login', $(this).serialize(), debugAnswer, function(data){
                if (data.state === true){
                    location.reload();
                }
                if (data.state === false && data.reason === 'password'){
                    console.log(data.data, $('#userIndic'));
                    $('#userIndic').text(data.data);
                    ($('#userIndic')).parent().removeClass('notForUser');
                }
                console.log(data);
            });
        })
    });
});

$('#signUp').click(function (e) {
    e.preventDefault();
    openModal('?action=getSignUpModal', $('#modalWaiter'), function () {
        $('#victimSignUp').click(function () {
            openModal('?action=getVictimSignUpModal', $('#modalWaiter'), function(){
                $('#modalForm').submit(function (e) {
                    e.preventDefault();
                    jqueryAsynchronousTreatment('?action=victimInscription', $(this).serialize(), debugAnswer, savePseudo);
                })
            });
        });
        $('#proSignUp').click(function () {
            openModal('?action=getProSignUpModal', $('#modalWaiter'), function(){
                $('#modalForm').submit(function (e) {//refactorise!
                    e.preventDefault();
                    jqueryAsynchronousTreatment('?action=proInscription', $(this).serialize(), debugAnswer, savePseudo);
                })
            });
        });
        $('#signInModal').click(function (e) {
            e.preventDefault();
            $('#signIn').click();
        })
    });
});