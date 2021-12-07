function showHideNavBar() {
    var nav = document.getElementById("navMar");
    var content = document.getElementById("boxContent");

    if (nav.style.display == "none") {
        nav.style.display = "block";
        content.style.width = "85%";
        content.style.left = "15%";
    } else {
        nav.style.display = "none";
        content.style.width = "100%";
        content.style.left = "0";
    }
}

function displayCreateServer() {
    var cr = document.getElementById("createServer");
    var jn = document.getElementById("joinServer");

    if (cr.style.display == "none") {
        cr.style.display = "block";
        jn.style.display = "none";
    } else {
        cr.style.display = "none";
    }
}

function addTeamCreateSrever() {
    var valNbTeams = document.getElementById("nbTeamsCreateServer").value;
    var valMaxIdTeam = document.getElementById("maxIdteamCreateServer").value;
    var idteamDiv = "Team" + valMaxIdTeam;
    console.log(idteamDiv);
    var htmlDiv = '<div id="' + idteamDiv + '" class="teamSettingAddGlobal"><p>Team ' + valMaxIdTeam + '</p>'
        + '<div class="alignMainSettingsteamAdd"><input type="text" placeholder="Team name" name="nameAddServerDispatch-' + idteamDiv + '" onkeyup="restrictLetter(this)" required><button type="button" class="btnChooseTeam" onclick="deleteTeamCreateServer(\'' + idteamDiv + '\')">Delete</button></div>'
        + '<div class="teamSettingAdd"><div class="toggle"><input type="checkbox" name="cCall-' + idteamDiv + '"><label>Can call</label></div><div class="toggle"><input type="checkbox" name="cDisp-' + idteamDiv + '"><label>Can dispatch</label></div><div class="toggle"><input type="checkbox" name="iResp-' + idteamDiv + '"><label>Is Responder</label></div><div class="toggle"><input type="checkbox" name="ndClS-' + idteamDiv + '"><label>Need a call sign ?</label></div></div>'
        + '</div>';

    document.getElementById("teamsList").insertAdjacentHTML('beforeend', htmlDiv);
    valMaxIdTeam = (valMaxIdTeam * 1) + 1;
    valNbTeams = (valNbTeams * 1) + 1;
    document.getElementById("nbTeamsCreateServer").value = valNbTeams;
    document.getElementById("maxIdteamCreateServer").value = valMaxIdTeam;
}

function joinServer() {
    var jn = document.getElementById("joinServer");
    var cr = document.getElementById("createServer");

    if (jn.style.display == "none") {
        jn.style.display = "block";
        cr.style.display = "none";
    } else {
        jn.style.display = "none";
    }
}

function deleteTeamCreateServer(idTeam) {
    console.log("try delete " + idTeam);
    document.getElementById(idTeam).remove();
    var valNbTeams = document.getElementById("nbTeamsCreateServer").value;
    valNbTeams = (valNbTeams * 1) - 1;
    document.getElementById("nbTeamsCreateServer").value = valNbTeams;

}

function restrictLetter(input) {
    var regex = /[^a-z | | 1-9]/gi;
    input.value = input.value.replace(regex, "");
}