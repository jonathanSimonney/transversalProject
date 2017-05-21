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
            $('#logContent').html(arrayLogs['access']);//.replace('&', '&amp;').replace('<', '&lt;')

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
                $('.accountInactive').append('<p>'+encodeURIComponent(i)+'</p>');
            }

            for (i in arrayUsers['registered_user']){
                $('.accountActive').append('<p>'+encodeURIComponent(i)+'</p>');
            }

            $('.accountActive').on("click", "p", function (e) {//todo refactorise!!! Really sorry for ugliness of this code!
                $('.usersSelected').removeClass('usersSelected');

                $(e.currentTarget).addClass('usersSelected');

                $('#dataActiveUser').removeClass('notForUser');
                $('#dataInactiveUser').addClass('notForUser');

                var data = arrayUsers['registered_user'][decodeURIComponent($(e.currentTarget).text())];

                $('#activeEmail').text(data['email']);
                $('#activeType').text(data['type']);
                $('#activePseudo').text(data['pseudo']);

                $('#suppressAccount').click(function () {
                    openModal('?action=suppressionMessageModal', $('#modalWaiter'), function () {
                        $('#confirm').click(function (e) {
                            jqueryAsynchronousTreatment('?action=suppressAccount', {'id': data['id'], 'message': $('#message').val()}, debugAnswer);
                            $('#dataActiveUser').addClass('notForUser');
                            $('.accountActive .usersSelected').remove();
                            $('#close').click();
                        })
                    });
                })
            });

            $('.accountInactive').on("click", "p", function (e) {
                $('.usersSelected').removeClass('usersSelected');

                $(e.currentTarget).addClass('usersSelected');

                $('#dataActiveUser').addClass('notForUser');
                $('#dataInactiveUser').removeClass('notForUser');

                var data = arrayUsers['unregistered_user'][decodeURIComponent($(e.currentTarget).text())];
                console.log(arrayUsers['unregistered_user'], decodeURIComponent($(e.currentTarget).text()));

                $('#inactiveEmail').text(data['email']);
                $('#inactiveType').text(data['type']);
                $('#inactivePseudo').text(data['pseudo']);

                $('#rejectPro').click(function () {
                    openModal('?action=suppressionMessageModal', $('#modalWaiter'), function () {
                        $('#confirm').click(function (e) {
                            jqueryAsynchronousTreatment('?action=rejectPro', {'id': data['id'], 'message': $('#message').val()}, debugAnswer);
                            $('#dataInactiveUser').addClass('notForUser');
                            $('.accountInactive .usersSelected').remove();
                            $('#close').click();
                        })
                    });
                });

                $('#activateAccount').click(function () {
                    jqueryAsynchronousTreatment('?action=activateAccount', {'id': data['id']}, debugAnswer, function () {
                        var added = $('<p>'+encodeURIComponent(data['pseudo'])+'</p>');
                        added.appendTo('.accountActive');
                        arrayUsers['registered_user'][data['pseudo']] = data;
                        $('.accountInactive .usersSelected').remove();
                        added.click();
                    })
                })
            });
        });
    });
});