<?php

require_once __DIR__ . '/../vendor/autoload.php';
$faker = Faker\Factory::create();

require_once __DIR__ . '/config.php';


// try {
//     $conn = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     $cryptocurrencies = [
//         ['name' => 'Bitcoin', 'symbol' => 'BTC'],
//         ['name' => 'Ethereum', 'symbol' => 'ETH'],
//         ['name' => 'Binance Coin', 'symbol' => 'BNB'],
//         ['name' => 'XRP', 'symbol' => 'XRP'],
//         ['name' => 'Solana', 'symbol' => 'SOL'],
//         ['name' => 'Dogecoin', 'symbol' => 'DOGE'],
//         ['name' => 'Cardano', 'symbol' => 'ADA'],
//         ['name' => 'Polygon', 'symbol' => 'MATIC'],
//         ['name' => 'Polkadot', 'symbol' => 'DOT'],
//         ['name' => 'Litecoin', 'symbol' => 'LTC'],
//         ['name' => 'Shiba Inu', 'symbol' => 'SHIB'],
//         ['name' => 'Avalanche', 'symbol' => 'AVAX'],
//         ['name' => 'Chainlink', 'symbol' => 'LINK'],
//         ['name' => 'Stellar', 'symbol' => 'XLM'],
//         ['name' => 'Uniswap', 'symbol' => 'UNI'],
//         ['name' => 'Bitcoin Cash', 'symbol' => 'BCH'],
//         ['name' => 'TRON', 'symbol' => 'TRX'],
//         ['name' => 'Cosmos', 'symbol' => 'ATOM'],
//         ['name' => 'Filecoin', 'symbol' => 'FIL'],
//         ['name' => 'Algorand', 'symbol' => 'ALGO'],
//         ['name' => 'VeChain', 'symbol' => 'VET'],
//         ['name' => 'Tezos', 'symbol' => 'XTZ'],
//         ['name' => 'Aave', 'symbol' => 'AAVE'],
//         ['name' => 'The Graph', 'symbol' => 'GRT'],
//         ['name' => 'EOS', 'symbol' => 'EOS'],
//         ['name' => 'PancakeSwap', 'symbol' => 'CAKE'],
//         ['name' => 'Synthetix', 'symbol' => 'SNX'],
//         ['name' => 'Compound', 'symbol' => 'COMP'],
//         ['name' => 'SushiSwap', 'symbol' => 'SUSHI'],
//         ['name' => 'Maker', 'symbol' => 'MKR'],
//         ['name' => 'Fantom', 'symbol' => 'FTM'],
//         ['name' => 'Zcash', 'symbol' => 'ZEC'],
//         ['name' => 'Monero', 'symbol' => 'XMR'],
//         ['name' => 'Terra Classic', 'symbol' => 'LUNC'],
//         ['name' => 'Ethereum Classic', 'symbol' => 'ETC'],
//         ['name' => 'Near Protocol', 'symbol' => 'NEAR'],
//         ['name' => 'Internet Computer', 'symbol' => 'ICP'],
//         ['name' => 'Elrond', 'symbol' => 'EGLD'],
//         ['name' => 'Theta', 'symbol' => 'THETA'],
//         ['name' => 'Klaytn', 'symbol' => 'KLAY'],
//         ['name' => 'Chiliz', 'symbol' => 'CHZ'],
//         ['name' => 'Enjin Coin', 'symbol' => 'ENJ'],
//         ['name' => 'Axie Infinity', 'symbol' => 'AXS'],
//         ['name' => 'Hedera', 'symbol' => 'HBAR'],
//         ['name' => 'Gala', 'symbol' => 'GALA'],
//         ['name' => 'Decentraland', 'symbol' => 'MANA'],
//         ['name' => 'Sandbox', 'symbol' => 'SAND'],
//         ['name' => '1inch', 'symbol' => '1INCH'],
//         ['name' => 'Lido DAO', 'symbol' => 'LDO'],
//         ['name' => 'Quant', 'symbol' => 'QNT'],
//         ['name' => 'Harmony', 'symbol' => 'ONE'],
//         ['name' => 'Thorchain', 'symbol' => 'RUNE'],
//         ['name' => 'Loopring', 'symbol' => 'LRC'],
//         ['name' => 'Zilliqa', 'symbol' => 'ZIL'],
//         ['name' => 'Kusama', 'symbol' => 'KSM'],
//         ['name' => 'Arweave', 'symbol' => 'AR'],
//         ['name' => 'Helium', 'symbol' => 'HNT'],
//         ['name' => 'Curve DAO', 'symbol' => 'CRV'],
//         ['name' => 'Balancer', 'symbol' => 'BAL'],
//         ['name' => 'Yearn.Finance', 'symbol' => 'YFI'],
//         ['name' => 'Ren', 'symbol' => 'REN'],
//         ['name' => 'Injective', 'symbol' => 'INJ'],
//         ['name' => 'Ocean Protocol', 'symbol' => 'OCEAN'],
//         ['name' => 'Keep Network', 'symbol' => 'KEEP'],
//         ['name' => 'Horizen', 'symbol' => 'ZEN'],
//         ['name' => 'Storj', 'symbol' => 'STORJ'],
//         ['name' => 'Origin Protocol', 'symbol' => 'OGN'],
//         ['name' => 'Celo', 'symbol' => 'CELO'],
//         ['name' => 'RenBTC', 'symbol' => 'RENBTC'],
//         ['name' => 'Immutable X', 'symbol' => 'IMX'],
//         ['name' => 'iExec RLC', 'symbol' => 'RLC'],
//         ['name' => 'Serum', 'symbol' => 'SRM'],
//         ['name' => 'Terra', 'symbol' => 'LUNA'],
//         ['name' => 'NEM', 'symbol' => 'XEM'],
//         ['name' => 'Status', 'symbol' => 'SNT'],
//         ['name' => 'Metal', 'symbol' => 'MTL'],
//         ['name' => 'Gnosis', 'symbol' => 'GNO'],
//         ['name' => 'Badger DAO', 'symbol' => 'BADGER'],
//         ['name' => 'Fetch.ai', 'symbol' => 'FET'],
//         ['name' => 'Power Ledger', 'symbol' => 'POWR'],
//         ['name' => 'Civic', 'symbol' => 'CVC'],
//         ['name' => 'Augur', 'symbol' => 'REP'],
//         ['name' => 'Rarible', 'symbol' => 'RARI'],
//         ['name' => 'Amp', 'symbol' => 'AMP'],
//         ['name' => 'Flow', 'symbol' => 'FLOW'],
//         ['name' => 'Jasmy', 'symbol' => 'JASMY'],
//         ['name' => 'Gala Games', 'symbol' => 'GALA'],
//         ['name' => 'Keep3rV1', 'symbol' => 'KP3R'],
//         ['name' => 'Ankr', 'symbol' => 'ANKR'],
//         ['name' => 'Illuvium', 'symbol' => 'ILV'],
//         ['name' => 'Serum', 'symbol' => 'SRM'],
//         ['name' => 'Oasis Network', 'symbol' => 'ROSE'],
//         ['name' => 'Syscoin', 'symbol' => 'SYS'],
//         ['name' => 'Utrust', 'symbol' => 'UTK'],
//         ['name' => 'Everipedia', 'symbol' => 'IQ'],
//         ['name' => 'Moonriver', 'symbol' => 'MOVR'],
//         ['name' => 'Woo Network', 'symbol' => 'WOO'],
//         ['name' => 'Perpetual Protocol', 'symbol' => 'PERP'],
//         ['name' => 'BarnBridge', 'symbol' => 'BOND']
//     ];

//     $stmt = $conn->prepare("INSERT INTO `cryptocurrencies` (`name`, `symbol`) VALUES (:name, :symbol)");

//     foreach ($cryptocurrencies as $crypto) {
//         $stmt->bindParam(':name', $crypto['name']);
//         $stmt->bindParam(':symbol', $crypto['symbol']);
//         try {
//             $stmt->execute();
//         } catch (PDOException $e) {
//             if ($e->getCode() != 23000) {
//                 echo "Ошибка: " . $e->getMessage() . PHP_EOL;
//             }
//         }
//     }
//     set_time_limit(300);

//     echo "Successfully";
// } catch (PDOException $ex) {
//     echo "Connection failed: " . $ex->getMessage();
// }

// try {
//     $conn = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=utf8;dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//     #echo "Connection successful";

//     $conn->exec("USE users");

//     // #Создание таблицы

//     // $sql = "DROP TABLE `cryptocurrencies`";

//     // $sql = "CREATE TABLE `users`(
//     //           `id` INT PRIMARY KEY AUTO_INCREMENT,
//     //           `name` VARCHAR(255) UNIQUE,
//     //           `email` VARCHAR(255) UNIQUE,
//     //           `avatar` VARCHAR(255),
//     //           `date` DATE NOT NULL,
//     //           `sex` VARCHAR(255) NOT NULL,
//     //           `remember_token` VARCHAR(255) DEFAULT NULL,
//     //           `hash_password` VARCHAR(255) NOT NULL
//     //           )";

//     // $sql = "ALTER TABLE users ADD COLUMN `remember_token` VARCHAR(255) DEFAULT NULL";

//     // $sql = "CREATE TABLE `cryptocurrencies`(
//     //               `id` INT PRIMARY KEY AUTO_INCREMENT,
//     //               `name` VARCHAR(255) NOT NULL UNIQUE,
//     //               `symbol` VARCHAR(255) NOT NULL UNIQUE
//     //               )";


//     // $conn->exec($sql);


//     // # Заполнение таблицы

//     // for ($i = 0; $i <= 1000; $i++) {

//     //     $name = $faker->userName;

//     //     $email_name_part = strtolower(str_replace(' ', '.', $name));
//     //     $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
//     //     $random_domain = $domains[array_rand($domains)];
//     //     $email = $email_name_part . '@' . $random_domain;

//     //     $avatar = 'https://robohash.org/' . urlencode($name) . '?size=300x300';

//     //     $date = $faker->date;

//     //     $sex = $faker->randomElement(['male', 'female']);;

//     //     $password = $faker->password;
//     //     $hash_password = password_hash($password, PASSWORD_DEFAULT);

//     //     $sql = "INSERT INTO `users`(`name`, `email`, `avatar`, `date`, `sex`, `hash_password`) VALUES (:name, :email, :avatar, :date, :sex, :hash_password)";
//     //     $stmt = $conn->prepare($sql);
//     //     $stmt->bindParam(':name', $name);
//     //     $stmt->bindParam(':email', $email);
//     //     $stmt->bindParam(':avatar', $avatar);
//     //     $stmt->bindParam(':date', $date);
//     //     $stmt->bindParam(':sex', $sex);
//     //     $stmt->bindParam(':hash_password', $hash_password);
//     //     $stmt->execute();
//     //     set_time_limit(300);
//     // }

//     echo "Successfully";
// } catch (PDOException $ex) {
//     echo "Connection failed: " . $ex->getMessage();
// }
