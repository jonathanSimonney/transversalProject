window.onload = function(){
    var logoutLink = document.getElementById('logout');

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], defaultAnswer);
    }


};