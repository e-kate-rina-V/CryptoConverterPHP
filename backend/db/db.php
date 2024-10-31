<?php

require_once __DIR__ . '/../vendor/autoload.php';
$faker = Faker\Factory::create();

require_once __DIR__ . '/config.php';

try {
    $connection = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8;dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $connection->exec("USE users");

    // $sql = "CREATE TABLE `users`(
    //           `id` INT PRIMARY KEY AUTO_INCREMENT,
    //           `name` VARCHAR(255) UNIQUE,
    //           `email` VARCHAR(255) UNIQUE,
    //           `avatar` VARCHAR(255),
    //           `date` DATE NOT NULL,
    //           `sex` VARCHAR(255) NOT NULL,
    //           `remember_token` VARCHAR(255) DEFAULT NULL,
    //           `hash_password` VARCHAR(255) NOT NULL
    //           )";

    // $sql = "CREATE TABLE `cryptocurrencies`(
    //               `id` INT PRIMARY KEY AUTO_INCREMENT,
    //               `name` VARCHAR(255) NOT NULL UNIQUE,
    //               `symbol` VARCHAR(255) NOT NULL,
    //               `category` VARCHAR(255) NOT NULL 
    //               )";

    // $sql = "CREATE TABLE `favorites`(
    //               `user_id` INT,
    //               `crypto_id` INT,
    //               FOREIGN KEY (`user_id`) REFERENCES users(id),
    //               FOREIGN KEY (`crypto_id`) REFERENCES cryptocurrencies(id)
    //               )";

    // $connection->exec($sql);


    # Заполнение таблицы `users`

    // for ($i = 0; $i <= 1000; $i++) {

    //     $name = $faker->userName;

    //     $email_name_part = strtolower(str_replace(' ', '.', $name));
    //     $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
    //     $random_domain = $domains[array_rand($domains)];
    //     $email = $email_name_part . '@' . $random_domain;

    //     $avatar = 'https://robohash.org/' . urlencode($name) . '?size=300x300';

    //     $date = $faker->date;

    //     $sex = $faker->randomElement(['male', 'female']);;

    //     $password = $faker->password;
    //     $hash_password = password_hash($password, PASSWORD_DEFAULT);

    //     $sql = "INSERT INTO `users`(`name`, `email`, `avatar`, `date`, `sex`, `hash_password`) VALUES (:name, :email, :avatar, :date, :sex, :hash_password)";
    //     $statement = $connection->prepare($sql);
    //     $statement->bindParam(':name', $name);
    //     $statement->bindParam(':email', $email);
    //     $statement->bindParam(':avatar', $avatar);
    //     $statement->bindParam(':date', $date);
    //     $statement->bindParam(':sex', $sex);
    //     $statement->bindParam(':hash_password', $hash_password);
    //     $statement->execute();
    //     set_time_limit(300);
    // }


    # Заполнение таблицы `cryptocurrencies`

    // $cryptocurrencies = [
    //     ['name' => 'Bitcoin', 'symbol' => 'BTC', 'category' => 'Major Cryptocurrencies'],
    //     ['name' => 'Ethereum', 'symbol' => 'ETH', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Binance Coin', 'symbol' => 'BNB', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'XRP', 'symbol' => 'XRP', 'category' => 'Value Storage and Transfer'],
    //     ['name' => 'Solana', 'symbol' => 'SOL', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Dogecoin', 'symbol' => 'DOGE', 'category' => 'Meme Coins'],
    //     ['name' => 'Cardano', 'symbol' => 'ADA', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Polygon', 'symbol' => 'MATIC', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Polkadot', 'symbol' => 'DOT', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Litecoin', 'symbol' => 'LTC', 'category' => 'Value Storage and Transfer'],
    //     ['name' => 'Shiba Inu', 'symbol' => 'SHIB', 'category' => 'Meme Coins'],
    //     ['name' => 'Avalanche', 'symbol' => 'AVAX', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Chainlink', 'symbol' => 'LINK', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Stellar', 'symbol' => 'XLM', 'category' => 'Value Storage and Transfer'],
    //     ['name' => 'Uniswap', 'symbol' => 'UNI', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Bitcoin Cash', 'symbol' => 'BCH', 'category' => 'Value Storage and Transfer'],
    //     ['name' => 'TRON', 'symbol' => 'TRX', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Cosmos', 'symbol' => 'ATOM', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Filecoin', 'symbol' => 'FIL', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Algorand', 'symbol' => 'ALGO', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'VeChain', 'symbol' => 'VET', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Tezos', 'symbol' => 'XTZ', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Aave', 'symbol' => 'AAVE', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'The Graph', 'symbol' => 'GRT', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'EOS', 'symbol' => 'EOS', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'PancakeSwap', 'symbol' => 'CAKE', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Synthetix', 'symbol' => 'SNX', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Compound', 'symbol' => 'COMP', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'SushiSwap', 'symbol' => 'SUSHI', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Maker', 'symbol' => 'MKR', 'category' => 'Governance Tokens'],
    //     ['name' => 'Fantom', 'symbol' => 'FTM', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Zcash', 'symbol' => 'ZEC', 'category' => 'Anonymous Cryptocurrencies'],
    //     ['name' => 'Monero', 'symbol' => 'XMR', 'category' => 'Anonymous Cryptocurrencies'],
    //     ['name' => 'Terra Classic', 'symbol' => 'LUNC', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Ethereum Classic', 'symbol' => 'ETC', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Near Protocol', 'symbol' => 'NEAR', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'Internet Computer', 'symbol' => 'ICP', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Elrond', 'symbol' => 'EGLD', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Theta', 'symbol' => 'THETA', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Klaytn', 'symbol' => 'KLAY', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Chiliz', 'symbol' => 'CHZ', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Enjin Coin', 'symbol' => 'ENJ', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Axie Infinity', 'symbol' => 'AXS', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Hedera', 'symbol' => 'HBAR', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Gala', 'symbol' => 'GALA', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Decentraland', 'symbol' => 'MANA', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Sandbox', 'symbol' => 'SAND', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => '1inch', 'symbol' => '1INCH', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Lido DAO', 'symbol' => 'LDO', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Quant', 'symbol' => 'QNT', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Harmony', 'symbol' => 'ONE', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Thorchain', 'symbol' => 'RUNE', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Loopring', 'symbol' => 'LRC', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Zilliqa', 'symbol' => 'ZIL', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Kusama', 'symbol' => 'KSM', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Arweave', 'symbol' => 'AR', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Helium', 'symbol' => 'HNT', 'category' => 'Internet of Things (IoT)'],
    //     ['name' => 'Curve DAO', 'symbol' => 'CRV', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Balancer', 'symbol' => 'BAL', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Yearn.Finance', 'symbol' => 'YFI', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Ren', 'symbol' => 'REN', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Injective', 'symbol' => 'INJ', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Ocean Protocol', 'symbol' => 'OCEAN', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Keep Network', 'symbol' => 'KEEP', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Horizen', 'symbol' => 'ZEN', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Storj', 'symbol' => 'STORJ', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Origin Protocol', 'symbol' => 'OGN', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Celo', 'symbol' => 'CELO', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'RenBTC', 'symbol' => 'RENBTC', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Immutable X', 'symbol' => 'IMX', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'iExec RLC', 'symbol' => 'RLC', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Serum', 'symbol' => 'SRM', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Terra', 'symbol' => 'LUNA', 'category' => 'Smart Contract Platforms'],
    //     ['name' => 'NEM', 'symbol' => 'XEM', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Status', 'symbol' => 'SNT', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Metal', 'symbol' => 'MTL', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Gnosis', 'symbol' => 'GNO', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Badger DAO', 'symbol' => 'BADGER', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Fetch.ai', 'symbol' => 'FET', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Power Ledger', 'symbol' => 'POWR', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Civic', 'symbol' => 'CVC', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Augur', 'symbol' => 'REP', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Rarible', 'symbol' => 'RARI', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Amp', 'symbol' => 'AMP', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Flow', 'symbol' => 'FLOW', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Jasmy', 'symbol' => 'JASMY', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Gala Games', 'symbol' => 'GALA', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Keep3rV1', 'symbol' => 'KP3R', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Ankr', 'symbol' => 'ANKR', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Illuvium', 'symbol' => 'ILV', 'category' => 'Gaming Tokens and NFTs'],
    //     ['name' => 'Oasis Network', 'symbol' => 'ROSE', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Syscoin', 'symbol' => 'SYS', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Utrust', 'symbol' => 'UTK', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Everipedia', 'symbol' => 'IQ', 'category' => 'Tools and Infrastructure'],
    //     ['name' => 'Moonriver', 'symbol' => 'MOVR', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Woo Network', 'symbol' => 'WOO', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'Perpetual Protocol', 'symbol' => 'PERP', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'BarnBridge', 'symbol' => 'BOND', 'category' => 'Decentralized Finance (DeFi)'],
    //     ['name' => 'MultiversX', 'symbol' => 'EGLD', 'category' => 'Alternative Blockchains'],
    //     ['name' => 'Rocket Pool', 'symbol' => 'RPL', 'category' => 'Decentralized Finance (DeFi)']
    // ];

    // $statement = $connection->prepare("INSERT INTO `cryptocurrencies` (`name`, `symbol`, `category`) VALUES (:name, :symbol, :category)");

    // foreach ($cryptocurrencies as $crypto) {
    //     $statement->bindParam(':name', $crypto['name']);
    //     $statement->bindParam(':symbol', $crypto['symbol']);
    //     $statement->bindParam(':category', $crypto['category']);
    //     try {
    //         $statement->execute();
    //     } catch (PDOException $e) {
    //         if ($e->getCode() != 23000) {
    //             echo "Ошибка: " . $e->getMessage() . PHP_EOL;
    //         }
    //     }
    // }
    // set_time_limit(300);

   
    # Заполнение таблицы `favorites`

    // $userCount = 1001;
    // $cryptoCount = 100;
    // $favoritesToInsert = 5000;

    // $insertedFavorites = 0;

    // while ($insertedFavorites < $favoritesToInsert) {

    //     $userId = rand(1, $userCount);
    //     $cryptoId = rand(1, $cryptoCount);

    //     $checkQuery = $connection->prepare("SELECT COUNT(*) FROM `favorites` WHERE `user_id` = :user_id AND `crypto_id` = :crypto_id");
    //     $checkQuery->bindParam(':user_id', $userId, PDO::PARAM_INT);
    //     $checkQuery->bindParam(':crypto_id', $cryptoId, PDO::PARAM_INT);
    //     $checkQuery->execute();

    //     if ($checkQuery->fetchColumn() == 0) {
    //         $insertQuery = $connection->prepare("INSERT INTO `favorites` (`user_id`, `crypto_id`) VALUES (:user_id, :crypto_id)");
    //         $insertQuery->bindParam(':user_id', $userId, PDO::PARAM_INT);
    //         $insertQuery->bindParam(':crypto_id', $cryptoId, PDO::PARAM_INT);
    //         $insertQuery->execute();
    //         $insertedFavorites++;
    //     }
    //     set_time_limit(300);
    // }


    echo "Successfully";
} catch (PDOException $ex) {
    echo "Connection failed: " . $ex->getMessage();
}
