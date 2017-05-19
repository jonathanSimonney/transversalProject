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

    openModal('?action=seeUserList', $('#actionReceptor'), function () {
        document.getElementById('actionReceptor').className = 'containerUsers';

        jqueryAsynchronousTreatment('?action=getUserList', [], function (serverData) {
            arrayUsers = JSON.parse(serverData.responseText);
            console.log(arrayUsers);

            for (i in arrayUsers['unregistered_user']){
                $('.accountInactive').append('<p>'+i+'</p>');
            }

            for (i in arrayUsers['registered_user']){
                $('.accountActive').append('<p>'+i+'</p>');
            }

            $('.accountActive p').click(function (e) {
                $('.usersSelected').removeClass('usersSelected');

                $(e.currentTarget).addClass('usersSelected');


            });

            $('.accountInactive p').click(function (e) {
                $('.usersSelected').removeClass('usersSelected');

                $(e.currentTarget).addClass('usersSelected');


            });
        });
    });
});