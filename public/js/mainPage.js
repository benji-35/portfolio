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

function deleteLangAddExp(idLang, idListing) {
    document.getElementById(idLang).remove();
    var nbLangs = document.getElementById("cuurLangInListAddExp").value;
    nbLangs = (nbLangs * 1) - 1;
    document.getElementById("cuurLangInListAddExp").value = nbLangs;
    document.getElementById(idListing).checked = false;
}

function addExpLang() {
    var nbLangs = document.getElementById("cuurLangInListAddExp").value * 1;
    var maxLangs = document.getElementById("nbMaxLangAddExp").value * 1;
    var listingIds = document.getElementById("langsListingIds");
    var editMenuLangs = document.getElementById("langExpadd");
    var cId = maxLangs;

    var idChecker = cId + "-listingCheckedLangAddExp";
    var idMenu = cId + "-langMenuAddExp";

    var listingLangHtml = "<input type=\"checkbox\" id=\"" + idChecker + "\" name=\"" + idChecker + "\" checked hidden>";
    var editLangHtml = "<div class=\"langExpAddDiv\" id=\"" + idMenu + "\">"
        + "\t<h3>New Language</h3>"
        + "\t<select name=\"selectedLangue-" + cId + "\">"
        + "\t\t<option value=\"no\" selected hidden> -- Select your language -- </option>"
        + "\t\t<option value=\"fr\">French</option>"
        + "\t\t<option value=\"de\">Deutsh</option>"
        + "\t\t<option value=\"ar\">Arabe</option>"
        + "\t</select>"
        + "\t<input type=\"text\" placeholder=\"Name...\" name=\"nameAddExp-" + cId + "\">"
        + "\t<textarea placeholder=\"Description...\" name=\"descriptionAddExp-" + cId + "\"></textarea>"
        + "\t<button type=\"button\" onclick=\"deleteLangAddExp('" + idMenu + "', '" + idChecker + "')\">Delete this language</button>"
        + "</div>";

    maxLangs++;
    nbLangs++;

    listingIds.insertAdjacentHTML('afterend', listingLangHtml);
    editMenuLangs.insertAdjacentHTML('afterend', editLangHtml);

    document.getElementById("nbMaxLangAddExp").value = maxLangs;
    document.getElementById("cuurLangInListAddExp").value = nbLangs;
}