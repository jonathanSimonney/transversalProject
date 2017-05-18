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

function jqueryAsynchronousTreatment(path, data, responseFunction, successFunc, errorFunc){//yes, I know, should keep code logical and coherent, but hey, I AM ALONE AND HAVE ONE WEEK TO DO EVERYTHING!
    console.log(data);
    $.ajax({
        url: path,
        type: 'post',
        processData:false,
        dataType: 'json',
        data: data,
        success: function(serverData, statut) {
            console.log(successFunc, errorFunc);
            if (successFunc !== undefined){
                successFunc(serverData);
            }
            if (errorFunc !== undefined){
                errorFunc();
            }
        },
        error: function (result, status, error) {
            console.log(result, status, error);
        },
        complete: function (serverData) {
            responseFunction(serverData);
        }
    });
}

function linkFormEvent(form, action, responseFunction){
    if (form !== undefined){
        form.onsubmit = function(){
            var formData = new FormData(form);//magic!
            asynchronousTreatment(action, formData, responseFunction);

            return false;
        };
    }
}

function debugAnswer(request){
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
            objectForm[i][1] = debugAnswer;
        }
        linkFormEvent(document.forms[i], '?action='+objectForm[i][0], objectForm[i][1]);
    }
}

function saveFormData(form, key){
    sessionStorage.removeItem(key);
    var stockedArray = {};
    $.each($(form).serializeArray(), function(i, field){
        stockedArray[field.name] = field.value;
    });
    sessionStorage.setItem(key, JSON.stringify(stockedArray));
}

function putFormData(form, key){
    $.each(JSON.parse(sessionStorage.getItem(key)), function(i, field){
        $("[name= '"+i+"']").val(field);
    })

}

function openModal(htmlPath, receptor, additionalAction){
    $.ajax({
        url: htmlPath,
        type: 'get',
        dataType: 'html',
        success: function(serverData, statut) {
            receptor.html(serverData);
            $('#close').click(function (e) {
                e.preventDefault();
               if ($('#modalForm').length === 1){
                   saveFormData($('#modalForm'), htmlPath);
               }
                receptor.html('');
            });

            $(document).click(function (e) {
                if ($(e.target).closest('#modal').length === 0){
                    $('#close').click();
                }
            });

            if ($('#modalForm').length === 1){
                putFormData($('#modalForm'), htmlPath);
            }

            if (additionalAction !== undefined){
                additionalAction();
            }
        },
        error: function (result, status, error) {
            console.log(result, status, error);
        },
        complete: function (serverData) {
        }
    });
}