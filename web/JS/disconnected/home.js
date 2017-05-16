$('#signIn').click(function (e) {
    e.preventDefault();
    openModal('views/disconnected/modal/signIn.html.twig', $('#modalWaiter'));
});

$('#signUp').click(function (e) {
    e.preventDefault();
    openModal('views/disconnected/modal/signUp.html.twig', $('#modalWaiter'), function () {
        $('#victimSignUp').click(function () {
            openModal('views/disconnected/modal/victimSignUp.html.twig', $('#modalWaiter'));
        });
        $('#proSignUp').click(function () {
            openModal('views/disconnected/modal/proSignUp.html.twig', $('#modalWaiter'));
        });
        $('#signInModal').click(function (e) {
            e.preventDefault();
            openModal('views/disconnected/modal/signIn.html.twig', $('#modalWaiter'));
        })
    });
});