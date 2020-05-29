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

// Traitement via le filtre en fonction de la catégorie (prof), du code (12345) et de la ou des dates.
// ------------- Première requête --------------------
// On récupère le nom du filtre (nom de l'enseignant si catégorie prof, nom du groupe si groupe, nom de la salle si salle)
$data['nameFilter'] = 'toto';   // Dans notre exemple
// ------------- deuxième requête ---------------------
// On récupère toutes les séances (sessions) correspondant au filtre
$data = [
    [
        'filter' => 'prof-12345',
        'filterName' => 'toto',
        'sessions' => [
            [
                0 => 12345, // code Ressource
                1 => '2020-03-24', // Date du jour
                2 => '800', // Heure de début
                3 => '60', // 130 signifie 1h30 ; Durée séance
                4 => 'TD', // Type de séance
                5 => 'Mathematiques', // nom de matière
                6 => 'Toto', // nom de l'enseignant
                7 => 'R10', // nom de salle
                8 => [],
                9 => 'salle-test', // alias de salle
            ],
            [
                0 => 123345,
                1 => '2020-03-24',
                2 => '800',
                3 => '130', // 130 signifie 1h30
                4 => 'TD',
                5 => 'Mathematiques',
                6 => 'Toto',
                7 => 'R12',
                8 => [],
                9 => 'salle-test',
            ],
            [
                0 => 123345,
                1 => '2020-03-24',
                2 => '830',
                3 => '100', // 130 signifie 1h30
                4 => 'TD',
                5 => 'Mathematiques',
                6 => 'Toto',
                7 => 'R12',
                8 => [],
                9 => 'salle-test',
            ],
            [
                0 => 12345,
                1 => '2020-03-24',
                2 => '1000',
                3 => '100', // 130 signifie 1h30
                4 => 'CM',
                5 => 'Français',
                6 => 'Toto',
                7 => 'R12',
                8 => [],
                9 => 'salle-2'
            ]
        ]
    ]
];

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
    0,
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