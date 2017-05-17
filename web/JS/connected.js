window.onload = function(){
    var arrayAction = {
        'changeData'             : ['changeData'],
        'sendMessage'            : ['sendMessage'],
        'changeFreeSlot'         : ['changeFreeSlot'],
        'abandonPro'             : ['abandonPro'],
        'suppress'               : ['suppressAccount']
    };

    linkAllFormEvent(arrayAction);

    var logoutLink = document.getElementById('logout');
    var findProForm = document.forms['findPro'];

    logoutLink.onclick = function (e) {
        e.preventDefault();
        asynchronousTreatment('?action=logout', [], debugAnswer);
    };

    if (findProForm !== undefined){
        findProForm.onsubmit = function () {
            var formData = new FormData(findProForm);//magic!
            if (formData.get('autoMatch') === null){
                formData.set('autoMatch', false);
            }
            asynchronousTreatment('?action=findPro', formData, debugAnswer);

            return false;
        }
    }
};
