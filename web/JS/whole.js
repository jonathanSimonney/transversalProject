function asynchronousTreatment(path,params, responseFunction){
    var request = new XMLHttpRequest();
    request.open("POST", path, true);
    //request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //request.setRequestHeader('Content-Type', 'multipart/form-data');
    request.onload = function(e) {
        responseFunction(request);
    };
    request.send(params);
}

function jqueryAsynchronousTreatment(path, form, responseFunction, successFunc, errorFunc){//yes, I know, should keep code logical and coherent, but hey, I AM ALONE AND HAVE ONE WEEK TO DO EVERYTHING!
    $.ajax({
        url: path,
        type: 'post',
        dataType: 'json',
        data: form.serialize(),
        success: function(serverData, statut) {
            console.log(serverData);
        },
        error: function (result, status, error) {
            console.log(result);
        },
        complete: function (serverData) {
            responseFunction(serverData);
        }
    });
}

function linkFormEvent(form, action, responseFunction){
    form.onsubmit = function(){
        var formData = new FormData(form);//magic!
        asynchronousTreatment(action, formData, responseFunction);

        return false;
    };
}

function defaultAnswer(request){
    //document.write(request.responseText);//todo comment this before final commit! EXTREMELY IMPORTANT!!!
    if (request.responseText !== ''){
        document.getElementById('debug').innerHTML = request.responseText;
        console.log(JSON.parse(request.responseText));
        alert("Vous avez un message (dans la console)");
    }else{
        alert('no answer???');
    }
}

function linkAllFormEvent(objectForm){
    for (var i in objectForm){
        if (typeof objectForm[i][1] === 'undefined') {
            objectForm[i][1] = defaultAnswer;
        }
        linkFormEvent(document.forms[i], '?action='+objectForm[i][0], objectForm[i][1]);
    }
}