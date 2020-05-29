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

    require '../cal/Day.php';
    require '../cal/Session.php';

    // Heure début
    $start = 8*60;
    // Heure fin
    $end = 19*60;

    // Avec l'heure de début et l'heure de fin, nous pouvons déterminer le nombre de cellule d'1/4 heures dans 1 journée
    $cellNumber = (19-8)*4;

    // xxxx -> Année [0]
    // xx -> Mois [1]
    // XX -> Jour [2]
    // XX -> Heure de début [3]
    // xxxx -> Durée de la séance en minute [4]
    // xxxxxxxx -> Type du cours [5]

// Filtre 1 : codeProf
$filtre1 = [
    [
        0 => 1233443,
        1 => '2020-03-24',
        2 => '800',
        3 => '60', // 130 signifie 1h30
        4 => 'TD',
        5 => 'Mathematiques',
        6 => 'Toto',
        7 => 'R10',
        8 => [],
        9 => 'salle-1'
    ],
    [
        0 => 1233443,
        1 => '2020-03-24',
        2 => '800',
        3 => '130', // 130 signifie 1h30
        4 => 'TD',
        5 => 'Mathematiques',
        6 => 'Donald',
        7 => 'R12',
        8 => [],
        9 => 'salle-1'
    ],
    [
        0 => 1233443,
        1 => '2020-03-24',
        2 => '830',
        3 => '100', // 130 signifie 1h30
        4 => 'TD',
        5 => 'Mathematiques',
        6 => 'Donald',
        7 => 'R12',
        8 => [],
        9 => 'salle-1'
    ],
    [
        0 => 1233443,
        1 => '2020-03-24',
        2 => '1000',
        3 => '100', // 130 signifie 1h30
        4 => 'CM',
        5 => 'Français',
        6 => 'Donald',
        7 => 'R12',
        8 => [],
        9 => 'salle-2'
    ],

];

$colors = [
    'COURS' => '#B5A9FB',
    'TD' => '#F9FDA8',
    'TP' => '#A9FCAE',
    'DS' => '#FDA9A9',
    'Projet' => '#FFC800',
    'Autre' => '#1EFF1D',
    'Reservation' => '#FFFF01'
];

   $edt = New \Sebius77\WeeklyCalendarGeneration\cal\Day(8, 19);

   // On créé le tableau des sessions pour un jour et un filtre donné (utilisateur, groupe, salle...) donné.

    $sessions = $edt->sessions($filtre1, $colors);

   $userSessions = $edt->sessionTable($sessions, '2020-03-24');

   // ensuite on regroupe les sessions liées
    $result = $edt->bundleSession($userSessions);
    include('weekCalVertical.php');
?>

</body>
</html>
