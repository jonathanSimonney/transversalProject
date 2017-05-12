window.onload = function () {
    $('.activate').click(function (e) {
        var id = $(e.target).siblings('.notForUser').val();
        jqueryAsynchronousTreatment('?action=activateAccount', {'id' : id}, defaultAnswer, function () {
            $(e.target).parent().remove();
        });
    });

    var logoutLink = document.getElementById('logout');//repetitive, sorry!

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], defaultAnswer);
    }
};
