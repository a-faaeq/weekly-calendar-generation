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

/*

   $edt = New \Sebius77\WeeklyCalendarGeneration\cal\Day(8, 19);

   // On créé le tableau des sessions pour un jour et un filtre donné (utilisateur, groupe, salle...) donné.

    $sessions = $edt->sessions($filtre1, $colors);

   $userSessions = $edt->sessionTable($sessions, '2020-03-24');

   // ensuite on regroupe les sessions liées
    $result = $edt->bundleSession($userSessions);
    include('weekCalVertical.php');
*/


/**
 * Nouvelle façon de faire
 */

$sessionManager = new \Sebius77\WeeklyCalendarGeneration\cal\SessionManager();
$day = new Day(8, 19, new HtmlGenerator(), $sessionManager);
$edt = new \Sebius77\WeeklyCalendarGeneration\cal\CalendarBuilder(
    '2020-03-24',
    '2020-03-24',
    8,
    19,
    $data,
    1,
    15,
    [
        'idHoursDiv' => 'hours',
        'cssHourClass' => 'hour',
    ],
    $sessionManager,
    $day
);

echo $edt->calendar();
?>


</body>
</html>
