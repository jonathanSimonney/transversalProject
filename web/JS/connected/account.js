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
