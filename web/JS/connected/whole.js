$('#logout').click(function (e) {
    e.preventDefault();
    jqueryAsynchronousTreatment('?action=logout', [], function(){
        window.location.replace('?action=home');
    })
});