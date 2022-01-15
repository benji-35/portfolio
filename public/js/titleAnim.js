var textDisplay = null;
const phrases = ['Hello, my name is Ania.', 'I love to code.', 'I love to teach.', ''];
let i = 0;
let j = 0;
let currentPhrase = [];
let isDeleting = false;
let isEnd = false;

window.onload = function () {
    phrases[0] = document.getElementById("phraseTitle1").value;
    phrases[1] = document.getElementById("phraseTitle2").value;
    phrases[2] = document.getElementById("phraseTitle3").value;
    phrases[3] = document.getElementById("phraseTitle4").value;
    typeWritter();
};

function currentPhraseToStr() {
    var res = "";

    for (var i = 0; i < currentPhrase.length; i++) {
        res += currentPhrase[i];
    }
    return res;
}

function typeWritter() {
    var currPhrase = currentPhraseToStr();
    isEnd = false;

    if (i < phrases.length) {
        if (!isDeleting && j <= phrases[i].length) {
            currentPhrase.push(phrases[i][j]);
            j++;
        }

        if(isDeleting && j <= phrases[i].length) {
            currentPhrase.pop(phrases[i][j]);
            j--;
        }

        if (j == phrases[i].length) {
            isEnd = true;
            isDeleting = true;
        }

        if (isDeleting && j === 0) {
            currentPhrase = [];
            isDeleting = false;
            i++;
            if (i === phrases.length) {
                i = 0;
            }
        }
    }
    textDisplay = document.getElementById('titleAnimated');
    textDisplay.innerHTML =  currPhrase;
    const spedUp = Math.random() * (80 -50) + 50;
    const normalSpeed = Math.random() * (300 -200) + 200;
    const time = isEnd ? 2000 : isDeleting ? spedUp : normalSpeed;
    setTimeout(typeWritter, time);
}