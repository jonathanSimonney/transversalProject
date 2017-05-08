window.onload = function(){
    var arrayAction = {
        'changePassword'         : ['changePassword'],
        'changeUsername'         : ['changeUsername'],
        'sendMessage'            : ['sendMessage'],
        'changeFreeSlot'         : ['changeFreeSlot']
    };

    linkAllFormEvent(arrayAction);

    var logoutLink = document.getElementById('logout');

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], defaultAnswer);
    }
};
