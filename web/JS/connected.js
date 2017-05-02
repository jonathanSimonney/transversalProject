window.onload = function(){
    var arrayAction = {
        'changePassword'         : ['changePassword'],
        'changeUsername'         : ['changeUsername'],
        'sendMessage'            : ['sendMessage']
    };

    linkAllFormEvent(arrayAction);

    var logoutLink = document.getElementById('logout');

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], defaultAnswer);
    }
};
