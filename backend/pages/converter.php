<?php include_once __DIR__ . '/../services/apiService.php' ?>
<?php include_once __DIR__ . '/../../assets/layout/head.php'; ?>
<?php include_once __DIR__ . '/../db/config.php' ?>
<?php require_once __DIR__ . '/../src/helpers.php'; ?>

<?php
$cryptos = [];
$errorMessage = '';
$userFavorites = [];
$userId = $_SESSION['user_id'] ?? 0;

$successMessage = '';
if (isset($_GET['message'])) {
    $successMessage = $_GET['message'];
}

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($userId > 0) {
        $statement = $pdo->prepare("SELECT `crypto_id` FROM `favorites` WHERE `user_id` = :user_id");
        $statement->execute(['user_id' => $userId]);
        $userFavorites = $statement->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($userFavorites)) {
            $placeholders = implode(',', array_fill(0, count($userFavorites), '?'));
            $statement = $pdo->prepare("SELECT id, name, symbol FROM cryptocurrencies WHERE id IN ($placeholders)");
            $statement->execute($userFavorites);
            $favoriteCryptos = $statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $favoriteCryptos = [];
        }
    }

    $statement = $pdo->prepare("SELECT DISTINCT category FROM cryptocurrencies ORDER BY category");
    $statement->execute();
    $categories = $statement->fetchAll(PDO::FETCH_COLUMN);

    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';


    $sql = "SELECT id, name, symbol FROM cryptocurrencies WHERE 1";
    if ($selectedCategory) {
        $sql .= " AND category = :category";
    }
    if ($searchTerm) {
        $sql .= " AND (name LIKE :search OR symbol LIKE :search)";
    }
    $sql .= " ORDER BY name";

    $statement = $pdo->prepare($sql);

    if ($selectedCategory) {
        $statement->bindParam(':category', $selectedCategory);
    }
    if ($searchTerm) {
        $searchLike = '%' . $searchTerm . '%';
        $statement->bindParam(':search', $searchLike);
    }

    $statement->execute();
    $cryptos = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit;
}

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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converter</title>
</head>

<body>

    <div id="back-section">
        <a href="../index.php">◀</a>
    </div>

    <div id="converter-page">
        <section id="converter">

            <div id="crypto-category">
                <div class="favorites">
                    <div class="favorite-select">
                        <div class="select-selected-favorites" onclick="toggleDropdownFavoritesCryptoList()">Cryptolist to add favorites</div>

                        <?php if ($successMessage): ?>
                            <p id="success-message" class="success"><?php echo htmlspecialchars($successMessage); ?></p>
                        <?php endif; ?>

                        <div class="crypto-list" id="favoritesCryptoList">
                            <?php if (empty($cryptos)): ?>
                                <p>No cryptocurrencies found.</p>
                            <?php else: ?>
                                <?php foreach ($cryptos as $crypto): ?>
                                    <form method="POST" style="display:inline;" action="./../db/add_favorites.php">
                                        <div class="favorites-add">
                                            <?php echo htmlspecialchars($crypto['name']); ?> (<?php echo htmlspecialchars($crypto['symbol']); ?>)
                                            <input type="hidden" name="crypto_id" value="<?php echo htmlspecialchars($crypto['id']); ?>">
                                            <div class="favorites-btn">
                                                <button id="add-btn" type="submit" name="action" value="add" <?php echo in_array($crypto['id'], $userFavorites) ? 'disabled' : ''; ?>>
                                                    ♥️
                                                </button>

                                                <button id="remove-btn" type="submit" name="action" value="remove" <?php echo !in_array($crypto['id'], $userFavorites) ? 'disabled' : ''; ?>>
                                                    🗑
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <form method="GET" action="">
                        <select class="select-selected-favorites" id="favorite-crypto-selector" name="favorite_crypto" onchange="selectFavoriteCrypto(this.value)">
                            <option value="">Select Favorite Crypto</option>
                            <?php foreach ($favoriteCryptos as $favorite): ?>
                                <option value="<?php echo htmlspecialchars($favorite['symbol']); ?>">
                                    <?php echo htmlspecialchars($favorite['name']); ?> (<?php echo htmlspecialchars($favorite['symbol']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <form method="GET">
                    <label for="category">Select Category:</label>
                    <select id="category" name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category); ?>"
                                <?php echo $selectedCategory === $category ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" name="search" placeholder="Search crypto..." value="<?php echo htmlspecialchars($searchTerm); ?>" oninput="this.form.submit()">
                </form>
            </div>

            <form method="POST">
                <div class="custom-select">
                    <div class="select-selected" onclick="toggleDropdownCryptoList()">Select crypto</div>

                    <div class="crypto-list" id="cryptoList">
                        <?php if (empty($cryptos)): ?>
                            <p>No cryptocurrencies found.</p>
                        <?php else: ?>
                            <?php foreach ($cryptos as $crypto): ?>
                                <div class="crypto-item" onclick="selectCrypto('<?php echo $crypto['symbol']; ?>')">
                                    <?php echo htmlspecialchars($crypto['name']); ?> (<?php echo htmlspecialchars($crypto['symbol']); ?>)
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <input type="hidden" id="selected_crypto" name="selected_crypto" value="">
                <p id="selected-crypto"></p>

                <div id="calculate">
                    <input min="0" type="number" id="amount" name="amount" placeholder="Number of coins..." required>
                    <?php if ($errorMessage): ?>
                        <p id="error-message" class="error"><?php echo htmlspecialchars($errorMessage); ?></p>
                    <?php endif; ?>
                    <button type="submit" id="calculate-btn">Calculate</button>
                </div>
            </form>
        </section>
        <section class="converter-image">
            <img id="warning-img" src="../../assets/img/warning.png" alt="Warning Image" />
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
                <img id="binace-logo" src="../../assets/img/binance_logo.png" alt="Binance logo" />
                <img id="kraken-logo" src="../../assets/img/kraken_logo.png" alt="Kraken Logo" />
                <img id="okx-logo" src="../../assets/img/okx_logo.png" alt="OKX Logo" />
            </div>
            <h2>Prices:</h2>
        </div>
        <div id="all-results" class="scrollable-results">
            <ul>
                <?php foreach ($totalPrices as $exchange => $price): ?>
                    <div id="result-list">
                        <li><?php echo htmlspecialchars($exchange); ?>: <?php echo $price !== null ? number_format($price, 5) . ' USD' : 'No data available'; ?></li>
                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($averagePrice !== null): ?>
        <h2 id="average-price"> <img id="average-logo" src="../../assets/img/average_logo.png" alt="average logo" /> Average Price: <?php echo number_format($averagePrice, 5) . ' USD'; ?></h2>
    <?php endif; ?>

    <script src="../../assets/scripts/crypto-selector.js"></script>

</body>

</html>