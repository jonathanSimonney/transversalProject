$('#signIn').click(function (e) {
    e.preventDefault();
    openModal('?action=getSignInModal', $('#modalWaiter'));
});

$('#signUp').click(function (e) {
    e.preventDefault();
    openModal('?action=getSignUpModal', $('#modalWaiter'), function () {
        $('#victimSignUp').click(function () {
            openModal('?action=getVictimSignUpModal', $('#modalWaiter'));
        });
        $('#proSignUp').click(function () {
            openModal('?action=getProSignUpModal', $('#modalWaiter'));
        });
        $('#signInModal').click(function (e) {
            e.preventDefault();
            openModal('?action=getSignInModal', $('#modalWaiter'));
        })
    });
});