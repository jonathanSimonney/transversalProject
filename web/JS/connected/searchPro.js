function searchProSuccessFunction(serverData){
    console.log(serverData['error']);
    if (serverData['error'] === undefined)
    {
        window.location.replace('?action=proSuccess&username='+encodeURIComponent(serverData['pseudo'])+'&gender='+
            encodeURIComponent(serverData['gender'])+'&location='+ encodeURIComponent(serverData['location']) )
    }
}

$('#searchPsy').click(function(){
    jqueryAsynchronousTreatment('?action=findAutoPro', {'professionalType' : 'psy'}, debugAnswer, searchProSuccessFunction);
});

$('#searchJurist').click(function(){
    jqueryAsynchronousTreatment('?action=findAutoPro', {'professionalType' : 'lawyer'}, debugAnswer, searchProSuccessFunction);
});

$('#researchButton').click(function(){
    jqueryAsynchronousTreatment('?action=findUsernamePro', {'username' : $('#research').val()}, debugAnswer, searchProSuccessFunction);
});

