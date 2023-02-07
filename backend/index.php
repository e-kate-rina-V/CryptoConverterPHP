<?php
    require_once 'vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Course</title>
</head>
<body>
<?php
    $arr = [
        'first' => 'A',
        'Second' => 'B',
        'Third' => 'C',
    ];
    dd($arr);
    echo "</br>";
    foreach ($arr as $key => $element) {
        echo "$key => $element </br>";
    }
?>
</body>
</html>