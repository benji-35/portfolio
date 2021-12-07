function create() {
    document.getElementById("connectAccount").style.display = "none";
    document.getElementById("createAccount").style.display = "block";
}

function connect() {
    document.getElementById("createAccount").style.display = "none";
    document.getElementById("connectAccount").style.display = "block";
}

function closeError() {
    var nb_errors = 0;
    do {
        var errors = document.getElementsByClassName("errorPopup");
        nb_errors = errors.length - 1;
        errors[0].remove();
    } while (nb_errors > 0);
}