window.onload = function(){
    var form = document.forms["connect"];
    var signUpForm = document.forms["signUp"];

    linkFormEvent(form, '?action=login', defaultAnswer);

    linkFormEvent(signUpForm, '?action=register', defaultAnswer);
};