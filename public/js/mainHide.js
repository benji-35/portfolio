function hidePopup() {
    var popup = document.getElementById("popupSure");
    var mainContent = document.getElementById("mainContent");

    popup.remove();
    mainContent.style.filter = "none";
    mainContent.style.pointerEvents = "all";
}