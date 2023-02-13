<?php
    require_once 'vendor/autoload.php';
    $file = fopen('test.txt', 'w');
    fwrite($file, 'user name: ' . $_POST['name'] . PHP_EOL . 'user Role: ' . $_POST['role']);
    fclose($file);
    $file2 = fopen('test.txt', 'r');
    $text = fread($file2, 10);
    fclose($file2);
    echo 'we have read from file: ' . $text;
    unlink('test.txt');
    $csvFile = fopen('file.csv', 'r');
    while ($data = fgetcsv($csvFile, 1000, '-')) {
        foreach ($data as $item) {
                echo $item . "</br>";
        }
    }

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>PHP</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sign-in/">



    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin">
    <div class="form-floating">
        <?php echo time() ?>
    </div>
    <div class="form-floating">
        <?php $time =  mktime(12, 43, 59, 1, 31, 2022) ?>
        <?php echo $time ?>
    </div>
    <div class="form-floating">
        <?php echo date('l dS \o\f F Y h:i:s A', $time) ?>
    </div>
    <div class="form-floating">
        <?php $time = strtotime('now') ?>
        <?php echo date('l dS \o\f F Y h:i:s A', $time) ?>
    </div>
    <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">You are</h1>

    <div class="form-floating">
        <?php echo $_POST['name'] ?>
    </div>
    <div class="form-floating">
        <?php echo $_POST['surname'] ?>
    </div>
    <div class="form-floating">
        <?php echo $_POST['role'] ?>
    </div>
    <div class="form-floating">

    </div>
    <div class="form-floating">
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit"><a href="index.php">Back</a></button>
    <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
</main>



</body>
</html>
