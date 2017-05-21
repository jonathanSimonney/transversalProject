$('#dlPJ').click(function () {
    //alert($('#mailId').val());
    window.location.replace('?action=downloadPj&notForUser='+$('#mailId').val());
    //jqueryAsynchronousTreatment('?action=downloadPj', {'notForUser' : $('#mailId').val()}, debugAnswer)
});