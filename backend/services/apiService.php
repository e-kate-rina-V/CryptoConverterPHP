<?php
const API_URLS = [
    'binance' => 'https://api.binance.com/api/v3/ticker/price?symbol=',
    'kraken' => 'https://api.kraken.com/0/public/Ticker?pair=',
    'okx' => 'https://www.okx.com/api/v5/market/ticker?instId=',
];

const MAIN_COIN_TOKEN = 'USDT';

function getBinancePrice($symbol)
{
    $url = API_URLS['binance'] . $symbol . MAIN_COIN_TOKEN;
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }
    $data = json_decode($response, true);

    return isset($data['price']) ? (float)$data['price'] : null;
}

function getKrakenPrice($symbol)
{
    $url = API_URLS['kraken'] . $symbol . MAIN_COIN_TOKEN;
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }
    $data = json_decode($response, true);

    return isset($data['result']) ? (float)$data['result'][key($data['result'])]['c'][0] : null;
}

function getOkxPrice($symbol)
{
    $url = API_URLS['okx'] . $symbol . "-" . MAIN_COIN_TOKEN;
    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }
    $data = json_decode($response, true);

    return isset($data['data'][0]['last']) ? (float)$data['data'][0]['last'] : null;
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

        $totalPrices['Binance'] = !is_null($binancePrice) ? $binancePrice * $amount : null;
        $totalPrices['Kraken'] = !is_null($krakenPrice) ? $krakenPrice * $amount : null;
        $totalPrices['OKX'] = !is_null($okxPrice) ? $okxPrice * $amount : null;

        $validPrices = array_filter($totalPrices);
        $averagePrice = count($validPrices) > 0 ? array_sum($validPrices) / count($validPrices) : null;
    }
}
