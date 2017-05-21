$('#dlPJ').click(function () {
    window.location.replace('?action=downloadPj&notForUser='+$('#mailId').val());
});