window.onload = function(){
    $('.suppressMessage').each(function () {
        $(this).submit(function (event) {
            event.preventDefault();
            jqueryAsynchronousTreatment('?action=suppressMessage', $(this).serialize(), defaultAnswer);
        })
    });

    var logoutLink = document.getElementById('logout');//repetitive, sorry!

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], defaultAnswer);
    }


};