sessionStorage.clear();

$('#signIn').click(function (e) {
    e.preventDefault();
    openModal('?action=getSignInModal', $('#modalWaiter'), function(){
        $('#signUpModal').click(function () {
            $('#signUp').click();
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
                    jqueryAsynchronousTreatment('?action=victimInscription', $(this).serialize(), debugAnswer);
                })
            });
        });
        $('#proSignUp').click(function () {
            openModal('?action=getProSignUpModal', $('#modalWaiter'), function(){
                $('#modalForm').submit(function (e) {
                    e.preventDefault();
                    jqueryAsynchronousTreatment('?action=proInscription', $(this).serialize(), debugAnswer);
                })
            });
        });
        $('#signInModal').click(function (e) {
            e.preventDefault();
            $('#signIn').click();
        })
    });
});