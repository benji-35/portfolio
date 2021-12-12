function filterExp() {
    var val = document.getElementById("selectExp").value;
    var children = document.getElementById("listExp").children;

    if (val == 1) {
        for (var i = 0; i < children.length; i++) {
            if (children[i].classList.contains("progExp")) {
                children[i].style.display = "block";
            } else {
                children[i].style.display = "none";
            }
        }
    } else if (val == 2) {
        for (var i = 0; i < children.length; i++) {
            if (children[i].classList.contains("rescueExp")) {
                children[i].style.display = "block";
            } else {
                children[i].style.display = "none";
            }
        }
    } else if (val == 3) {
        for (var i = 0; i < children.length; i++) {
            if (children[i].classList.contains("volExp")) {
                children[i].style.display = "block";
            } else {
                children[i].style.display = "none";
            }
        }
    } else if (val == 4) {
        for (var i = 0; i < children.length; i++) {
            if (children[i].classList.contains("proExp")) {
                children[i].style.display = "block";
            } else {
                children[i].style.display = "none";
            }
        }
    } else {
        for (var i = 0; i < children.length; i++) {
            if (children[i].style.display != "block") {
                children[i].style.display = "block";
            }
        }
    }
}

function addProjLang() {
    var allLang = document.getElementById("langsProjAdd");
    var cNbLang = document.getElementById("cNbProjLang").value * 1;
    var cIdLang = document.getElementById("cIdProjLang").value * 1;
    var htmlToAdd = "<div class=\"langProjEditAdd\" id=\"lngProjAdd-" + cIdLang + "\">" +
    "<input type=\"text\" name=\"lngShortProjAdd-" + cIdLang + "\" placeholder=\"Lang short (en)...\">"+
    "<textarea name=\"lngDescShortProjAdd-" + cIdLang + "\" placeholder=\"\"></textarea>" +
    "<button type=\"button\" onclick=\"removeLangProjAdd('lngProjAdd-" + cIdLang + "')\">Supprimer</button>" +
    "</div>";

    allLang.insertAdjacentHTML('afterend', htmlToAdd);
    cNbLang = cNbLang + 1;
    document.getElementById("cNbProjLang").value = cNbLang;
    cIdLang = cIdLang + 1;
    document.getElementById("cIdProjLang").value = cIdLang;
}

function removeLangProjAdd(idProjAddLang) {
    document.getElementById(idProjAddLang).remove();
    var cNbLang = document.getElementById("cNbProjLang").value * 1;
    cNbLang--;
    document.getElementById("cNbProjLang").value = cNbLang;
}