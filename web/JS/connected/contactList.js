$('.contact').click(function (e) {
    var id = $(e.currentTarget).children('span').children('.notForUser').val();
    alert('uncertain future!');
    //window.location.replace('?action=getUserData&id='+id);
});