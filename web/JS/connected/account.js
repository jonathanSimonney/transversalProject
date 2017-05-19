$('#enableModif').click(function (e) {
    openModal('?action=accountModif', $('#receptor'), function () {
        $('#cancelEdit').click(function (e) {
            e.preventDefault();
            location.reload();
        });

        $('#changeData').submit(function (e) {
            e.preventDefault();
            jqueryAsynchronousTreatment('?action=changeData', $(this).serialize(), debugAnswer);
        });
    })
});

$('#suppressAccount').click(function (e) {
    openModal('?action=confirmSuppressionModal', $('#modalWaiter'), function () {
        $('#validate').click(function () {
            jqueryAsynchronousTreatment('?action=suppressAccount', [], function(){
                window.location.replace('?action=home');
            })
        });
        $('#decline').click(function () {
            $('#close').click();
        })
    })
});
