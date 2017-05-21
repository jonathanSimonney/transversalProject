$('#searchPsy').click(function(){
    jqueryAsynchronousTreatment('?action=findAutoPro', {'professionalType' : 'psy'}, debugAnswer);
});

$('#searchJurist').click(function(){
    jqueryAsynchronousTreatment('?action=findAutoPro', {'professionalType' : 'lawyer'}, debugAnswer);
});

$('#researchButton').click(function(){
    jqueryAsynchronousTreatment('?action=findUsernamePro', {'username' : $('#research').val()}, debugAnswer);
});

