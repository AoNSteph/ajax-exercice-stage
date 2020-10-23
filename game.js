/**
 * Affiche un texte résultat dans l'encart "Résultats bruts"
 * @param {String} rawResult
 * @returns {void}
 */
function showRawResults(rawResult) {
    document.getElementById('rawResults').innerText = rawResult;
}

/**
 * Affiche un texte résultat et change la couleur selon si succès ou erreur.
 * @param {String} text
 * @param {Boolean} error default as false
 * @returns {void}
 */
function showNiceResults(text, error) {
    document.getElementById('niceResults').innerText = text;
    if(error) {
        document.getElementById('niceResults').classList.remove('success');
        document.getElementById('niceResults').classList.add('error');
    } else {
        document.getElementById('niceResults').classList.remove('error');
        document.getElementById('niceResults').classList.add('success');
    }
}

/**
 * Active un bouton
 * @param {String} id
 * @returns {void}
 */
function enableButton(id) {
    document.getElementById(id).disabled = false;
}

/**
 * Désactive un bouton
 * @param {String} id
 * @returns {void}
 */
function disableButton(id) {
    document.getElementById(id).disabled = true;
}

/**
 * Décode un résultat obtenu par requête AJAX
 * @param {String} encodedResult
 * @returns {Array|Object}
 */
function decodeResult(encodedResult) {
    return JSON.parse(encodedResult);
}

/**
 * Affiche le statut de jeu (démarré ou non, essais...)
 * @param {String} rawTextResponse
 * @returns {void}
 */
function displayGameStatus(rawTextResponse) {
    let niceResponse = decodeResult(rawTextResponse);
    if(niceResponse['started']) {
        showNiceResults('Jeu non-démarré', true);
    } else {
        showNiceResults('Jeu démarré, essais réalisés : '+niceResponse['tries']);
    }
}

/**
 * Fonction automatiquement lancée au démarrage de l'application
 * @returns {void}
 */
function loaded() {
    let xhr = new XMLHttpRequest();
    xhr.onload = function(){
        displayGameStatus(this.responseText);
    };
    xhr.open('get', 'api.php', true);
    xhr.send();
}

function startGame(event) {
    /*
     * @TODO
     */
    event.preventDefault();
    return false;
}

function endGame(event) {
    /*
     * @TODO
     */
    event.preventDefault();
    return false;
}

function tryNb(event) {
    let nbToTry = document.getElementById('tryNb').value;
    /*
     * @TODO
     */
    event.preventDefault();
    return false;
}

(function(){
  document.getElementById('startGameButton').addEventListener('click', startGame, true);
  document.getElementById('endGameButton').addEventListener('click', endGame, true);
  document.getElementById('gameTryForm').addEventListener('submit', tryNb, true);
  loaded();
})();