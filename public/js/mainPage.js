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