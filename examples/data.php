<?php


$colors = [
    'COURS' => '#B5A9FB',
    'TD' => '#F9FDA8',
    'TP' => '#A9FCAE',
    'DS' => '#FDA9A9',
    'Projet' => '#FFC800',
    'Autre' => '#1EFF1D',
    'Reservation' => '#FFFF01'
];


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
            1 => '2020-06-01', // Date du jour
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
            1 => '2020-06-01',
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
            1 => '2020-06-02',
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
            1 => '2020-06-03',
            2 => '1000',
            3 => '100', // 130 signifie 1h30
            4 => 'CM',
            5 => 'Français',
            6 => 'Toto',
            7 => 'R12',
            8 => [],
            9 => 'salle-2'
            ],
            [
                0 => 12345,
                1 => '2020-06-05',
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
