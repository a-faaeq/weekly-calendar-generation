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

    require 'src/EdtDay.php';
    require 'src/Session.php';

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
      $user1 = [
          '2020-2-24-08-60-A',
          '2020-2-24-10-60-B',
          '2020-2-24-08-30-C',
          '2020-2-24-09-30-D',
          '2020-2-24-14-240-E',
          '2020-2-24-18-60-F',
          '2020-2-24-13-60-G',
          '2020-2-24-08-45-H',
          '2020-2-24-16-30-I',
      ];

    $jour2 = [
    '2020-2-25-08-60-A',
    '2020-2-25-11-60-B',
    '2020-2-25-09-60-C',
    '2020-2-25-13-60-D',
    '2020-2-25-14-60-E',
    '2020-2-25-15-60-F',
    '2020-2-25-16-60-G',
    '2020-2-25-17-60-H',
    ];

   $edt = New \Sebius77\WeeklyCalendarGeneration\App\EdtDay(8, 19);

   // On créé le tableau des sessions pour un jour et un filtre donné (utilisateur, groupe, salle...) donné.
   $userSessions = $edt->sessionTable($user1, '2020-2-24');

   // ensuite on regroupe les sessions liées
    $result = $edt->bundleSession($userSessions);


    /*
    $userSession2 = $edt->sessionTable($jour2, '2020-2-25');
    $result2 = $edt->bundleSession($userSession2);
    */
    include('examples/weekCal.php');
?>




</body>
</html>
