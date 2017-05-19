$('#seeLogs').click(function(e){
    if (document.querySelector('.submitLogs') !== null){
        document.querySelector('.submitLogs').className = 'submitUtilisateur';
    }
    document.querySelector('#seeLogs').className = 'submitLogs';

    openModal('?action=seeLogTwig', $('#actionReceptor'), function(){
        document.getElementById('actionReceptor').className = 'containerConsole';
        jqueryAsynchronousTreatment('?action=getLogs', [], function (serverData) {
            arrayLogs = JSON.parse(serverData.responseText);
            console.log(arrayLogs);
            $('#logContent').html(arrayLogs['access']);

            $('.typeConsole p').click(function(e){
                if ($(e.currentTarget).hasClass('selectedLog')){
                    return;
                }

                $('.selectedLog').removeClass('selectedLog');
                $(e.currentTarget).addClass('selectedLog');
                console.log($(e.currentTarget).text());
                $('#logContent').html(arrayLogs[$(e.currentTarget).text()]);
            })
        });
    })
});

$('#seeUsers').click(function(e){
    if (document.querySelector('.submitLogs') !== null){
        document.querySelector('.submitLogs').className = 'submitUtilisateur';
    }
    document.querySelector('#seeUsers').className = 'submitLogs';
});