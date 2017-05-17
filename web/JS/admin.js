window.onload = function () {
    $('.activate').click(function (e) {
        var id = $(e.target).siblings('.notForUser').val();
        jqueryAsynchronousTreatment('?action=activateAccount', {'id' : id}, debugAnswer, function () {
            $(e.target).parent().remove();
        });
    });

    $('.suppress').click(function (e) {
        var id = $(e.target).siblings('.notForUser').val();
        var message = $(e.target).siblings('.message').val();

        jqueryAsynchronousTreatment('?action=suppressAccount', {'id' : id, 'message' : message}, debugAnswer, function () {
            $(e.target).parent().remove();
        });
    });

    $('.reject').click(function (e) {//todo refactor (far too much copied pasted!
        var id = $(e.target).siblings('.notForUser').val();
        var message = $(e.target).siblings('.message').val();

        jqueryAsynchronousTreatment('?action=rejectPro', {'id' : id, 'message' : message}, debugAnswer, function () {
            $(e.target).parent().remove();
        });
    });

    var logoutLink = document.getElementById('logout');//repetitive, sorry!

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], debugAnswer);
    }
};
