<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>

<?php

require '../cal/Month.php';


$month = new \Sebius77\WeeklyCalendarGeneration\cal\Month(12, 2019);

//var_dump($month->getMonth());
//var_dump($month->startMonthDay());
$month->getWeeks();




$minical = $month->miniCal();

?>

<table>
    <tr>
        <th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th><th>Dim</th>
    </tr>
    <?php
        echo $minical;
    ?>

</table>

