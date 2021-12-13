function encodeMsg(msg, targetID) {
    const base64data = window.btoa(msg);
    document.getElementById(targetID).value = base64data;
}

function decodeMsg(msg, targetID) {
    const input = atob(msg);
    document.getElementById(targetID).innerHTML = input;
}

function encodeMsgFromInput(idInput, targetId) {
    var msgEnetered = document.getElementById(idInput).value;
    encodeMsg(msgEnetered, targetId);
}

function decodeMsgFromInput(idInput, targetId) {
    decodeMsg(document.getElementById(idInput).value, targetId);
}