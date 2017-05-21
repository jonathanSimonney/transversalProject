$('#doSuppression').click(function () {
    var strType = $('#type').text();
    var type = 'invalid';

    if (strType === 'psychologue'){
        type = 'psy';
    }else if (strType === 'juriste'){
        type = 'lawyer';
    }
    jqueryAsynchronousTreatment('?action=abandonPro', {'professionalType' : type, 'reason': $('#reason').val()}, debugAnswer, function(serverData){
        window.location.replace('?action=showSuccessAbandonForm');
    });
});

$('#cancelSuppression').click(function () {
    location.reload();
});