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

use Sebius77\WeeklyCalendarGeneration\cal\Day;
use Sebius77\WeeklyCalendarGeneration\cal\HtmlGenerator;

require '../cal/Day.php';
require '../cal/Session.php';
require '../cal/CalendarBuilder.php';
require '../cal/SessionManager.php';
require '../cal/HtmlGenerator.php';
require '../cal/Filter.php';

include ('data.php');


/**
 * Nouvelle façon de faire
 */

$edt = new \Sebius77\WeeklyCalendarGeneration\cal\CalendarBuilder(
    new DateTime('2020-03-24'),
    new DateTime('2020-03-28'),
    8,
    19,
    $data,
    1,
    15,
    [
        'idHoursDiv' => 'hours',
        'cssHourClass' => 'hour',
        'colors' => $colors
    ]
);

echo $edt->weekCalendar();
?>


</body>
</html>
