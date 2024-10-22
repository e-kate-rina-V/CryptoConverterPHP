<?php include_once __DIR__ . '/services/apiService.php' ?>
<?php include_once __DIR__ . '/head.php' ?>
<?php include_once __DIR__ . '/db/config.php' ?>

<?php
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $statement = $pdo->prepare("SELECT `name`, `symbol` FROM `cryptocurrencies`");
    $statement->execute();

    $cryptos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit;
}

$searchQuery = isset($_GET['search']) ? strtolower($_GET['search']) : '';
$errorMessage = '';
$totalPrices = [];
$averagePrice = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedCrypto = isset($_POST['selected_crypto']) ? strtoupper($_POST['selected_crypto']) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

    if (empty($selectedCrypto) || $amount <= 0) {
        $errorMessage = 'Please select a cryptocurrency and enter a valid amount.';
    } else {

        $binancePrice = getBinancePrice($selectedCrypto);
        $krakenPrice = getKrakenPrice($selectedCrypto);
        $okxPrice = getOkxPrice($selectedCrypto);

        $totalPrices['Binance'] = $binancePrice !== null ? $binancePrice * $amount : null;
        $totalPrices['Kraken'] = $krakenPrice !== null ? $krakenPrice * $amount : null;
        $totalPrices['OKX'] = $okxPrice !== null ? $okxPrice * $amount : null;

        $validPrices = array_filter($totalPrices);
        $averagePrice = count($validPrices) > 0 ? array_sum($validPrices) / count($validPrices) : null;
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Converter</title>

<div id="back-section">
    <a href="index.php">◀</a>
</div>

<body>
    <div id="converter-page">
        <section id="converter">
            <form method="POST">
                <div class="custom-select">
                    <div class="select-selected" onclick="toggleDropdown()">Select crypto</div>
                    <input type="search" id="search-input" placeholder="Search..." oninput="filterCryptos()" />
                    <select id="sort-select" onchange="sortCryptos()">
                        <option value="asc">Sort A to Z</option>
                        <option value="desc">Sort Z to A</option>
                    </select>
                </div>
                <div class="select-items" id="crypto-list">
                </div>

                <input type="hidden" id="selected_crypto" name="selected_crypto" value="">
                <p id="selected-crypto"></p>

                <div id="calculate">
                    <input min="0" type="number" id="amount" name="amount" placeholder="Number of coins..." required>
                    <button type="submit" id="calculate-btn">Calculate</button>
                </div>

                <?php if ($errorMessage): ?>
                    <p class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php endif; ?>
            </form>
        </section>
        <section class="converter-image">
            <img id="warning-img" src="../assets/img/warning.png" alt="Warning Image" />
            <div id="image-text">
                <div id="square">
                    <p>This cryptocurrency converter can</p>
                    <p>only convert the top 100 most</p>
                    <p>popular cryptocurrencies, according</p>
                    <p>to 2024 data.</p>
                    <p>---</p>
                    <p>The converter uses three popular</p>
                    <p>crypto exchanges. Not all exchanges</p>
                    <p>contain information about all top</p>
                    <p>cryptocurrencies.</p>
                </div>
            </div>
        </section>
    </div>

    <?php if (!empty($totalPrices)): ?>
        <div>
            <div class="crypto-img">
                <img id="binace-logo" src="../assets/img/binance_logo.png" alt="Binance logo" />
                <img id="kraken-logo" src="../assets/img/kraken_logo.png" alt="Kraken Logo" />
                <img id="okx-logo" src="../assets/img/okx_logo.png" alt="OKX Logo" />
            </div>
            <h2>Prices:</h2>
        </div>
        <div id="all-results" class="scrollable-results">
            <ul>
                <?php foreach ($totalPrices as $exchange => $price): ?>
                    <div id="result-list">
                        <li><?php echo htmlspecialchars($exchange); ?>: <?php echo $price !== null ? number_format($price, 5) . ' USD' : 'No data avaliable'; ?></li>
                    <?php endforeach; ?>
                    </div>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($averagePrice !== null): ?>
        <h2 id="average-price"> <img id="average-logo" src="../assets/img/average_logo.png" alt="average logo" /> Average Price: <?php echo number_format($averagePrice, 5) . ' USD'; ?></h2>
    <?php endif; ?>

    <?php if (count($totalPrices) === 0 && !empty($_POST)): ?>
        <p>No results found</p>
    <?php endif; ?>

    <script>
        const cryptos = <?php echo json_encode($cryptos); ?>;
    </script>

    <script src="../assets/scripts/crypto-selector.js"></script>

</body>

</html>