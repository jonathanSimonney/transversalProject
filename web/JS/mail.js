window.onload = function(){
    var arrayAction = {
        'suppressMessage'         : ['suppressMessage']
    };

    console.log(document.forms);//todo change handler so that actions are usabe!!! (currently only first one is usable.)

    linkAllFormEvent(arrayAction);

    var logoutLink = document.getElementById('logout');

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], defaultAnswer);
    }


};