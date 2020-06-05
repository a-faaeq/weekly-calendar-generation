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

$colors = [
    'COURS' => '#B5A9FB',
    'TD' => '#F9FDA8',
    'TP' => '#A9FCAE',
    'DS' => '#FDA9A9',
    'Projet' => '#FFC800',
    'Autre' => '#1EFF1D',
    'Reservation' => '#FFFF01'
];

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
    ],
    new \Sebius77\WeeklyCalendarGeneration\cal\SessionManager(),
    new Day(8, 19, 1)
);

echo $edt->weekCalendar();
?>


</body>
</html>
