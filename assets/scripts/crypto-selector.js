
function displayCryptos(cryptos) {
    const cryptoList = document.getElementById('crypto-list');
    cryptoList.innerHTML = '';

    cryptos.forEach(crypto => {
        const div = document.createElement('div');
        div.textContent = `${crypto.name} (${crypto.symbol})`;
        div.onclick = () => selectCrypto(crypto.symbol);
        cryptoList.appendChild(div);
    });
}

function toggleDropdown() {
    const cryptoList = document.getElementById('crypto-list');
    cryptoList.style.display = cryptoList.style.display === 'block' ? 'none' : 'block';
}

function filterCryptos() {
    const input = document.getElementById('search-input').value.toLowerCase();
    const cryptoList = document.getElementById('crypto-list');
    const options = cryptoList.children;

    for (let i = 0; i < options.length; i++) {
        const option = options[i];
        const text = option.textContent.toLowerCase();
        option.style.display = text.includes(input) ? '' : 'none';
    }
}

function sortCryptos() {
    const sortOrder = document.getElementById('sort-select').value;
    const sortedCryptos = [...cryptos];

    sortedCryptos.sort((a, b) => {
        return sortOrder === 'asc' ?
            a.name.localeCompare(b.name) :
            b.name.localeCompare(a.name);
    });

    displayCryptos(sortedCryptos);
}

function selectCrypto(crypto) {
    document.querySelector('.select-selected').textContent = crypto;
    document.getElementById('selected-crypto').textContent = 'You selected: ' + crypto;
    document.getElementById('selected_crypto').value = crypto;
    document.getElementById('crypto-list').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    displayCryptos(cryptos);
});
