function showHidePopup() {
    var popup = document.getElementById("accountPopup");

    if (popup.style.display === "block") {
        popup.style.display = "none";
    } else {
        popup.style.display = "block";
    }
}