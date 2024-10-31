
function toggleDropdownCryptoList() {
    var cryptoList = document.getElementById("cryptoList");
    cryptoList.style.display = cryptoList.style.display === "none" ? "block" : "none";
}

function toggleDropdownFavoritesCryptoList() {
    var favoritesCryptoList = document.getElementById("favoritesCryptoList");
    favoritesCryptoList.style.display = favoritesCryptoList.style.display === "none" ? "block" : "none";
}

function selectCrypto(cryptoSymbol) {
    document.querySelector('.select-selected').textContent = cryptoSymbol;
    document.getElementById('selected-crypto').textContent = 'You selected: ' + cryptoSymbol;
    document.getElementById('selected_crypto').value = cryptoSymbol;
    document.getElementById("cryptoList").style.display = "none";
}

function selectFavoriteCrypto(cryptoSymbol) {
    if (cryptoSymbol) {
        document.querySelector('.select-selected').textContent = cryptoSymbol;
        document.getElementById('selected-crypto').textContent = 'You selected: ' + cryptoSymbol;
        document.getElementById('selected_crypto').value = cryptoSymbol;
    }
}

window.onclick = function (event) {
    if (!event.target.matches('.select-selected')) {
        var cryptoList = document.getElementById("cryptoList");
        if (cryptoList.style.display === "block") {
            cryptoList.style.display = "none";
        }
    }
    if (!event.target.matches('.select-selected-favorites')) {
        var favoritesCryptoList = document.getElementById("favoritesCryptoList");
        if (favoritesCryptoList.style.display === "block") {
            favoritesCryptoList.style.display = "none";
        }
    }
    if (document.getElementById("success-message")) {
        document.getElementById("success-message").style.display = "none";
    }
    if (document.getElementById("error-message")) {
        document.getElementById("error-message").style.display = "none";
    }

}