window.onload = function(){
    $('.suppressMessage').each(function () {
        $(this).submit(function (event) {
            event.preventDefault();
            jqueryAsynchronousTreatment('?action=suppressMessage', $(this), defaultAnswer);
        })
    });

    var logoutLink = document.getElementById('logout');

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], defaultAnswer);
    }


};