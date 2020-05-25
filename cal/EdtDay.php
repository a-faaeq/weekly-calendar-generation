<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class EdtDay {

    private $startTimeDay;
    private $endTimeDay;
    private $cellnumber;
    private $sessionTable;

    public function __construct($startTimeDay, $endTimeDay)
    {
        $this->setStartTimeDay($startTimeDay*60);
        $this->setEndTimeDay($endTimeDay*60);
        $this->setCellnumber(($endTimeDay-$startTimeDay)*4);
    }

    /**
     * permet de comparer 2 session et de savoir si elle se chevauche
     * @param Session $sessionA
     * @param Session $sessionB
     * @return bool
     */
    public function sessionCompare(Session $sessionA, Session $sessionB)
    {
        if (
            ($sessionB->getSessionEndTime() > $sessionA->getSessionStartTime() &&
            $sessionB->getSessionStartTime() <= $sessionA->getSessionStartTime())
            ||
            ($sessionB->getSessionStartTime() < $sessionA->getSessionEndTime() &&
            $sessionB->getSessionEndTime() >= $sessionA->getSessionEndTime())
            ||
            ($sessionB->getSessionStartTime() >= $sessionA->getSessionStartTime() &&
            $sessionB->getSessionEndTime() <= $sessionA->getSessionEndTime())
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @param array $colors
     * @return array
     */
    public function sessions(array $data, array $colors) {
        $sessions = [];
        foreach ($data as $session) {
            $object = new Session($session, $colors);
            $sessions[] = $object;
        }
        return $sessions;
    }

    /**
     * Permet de regrouper les séances du jour demandé dans un tableau
     * @param $sessions
     * @param $day
     * @return array
     */
    public function sessionTable(array $sessions, $day)
    {
        $dayTab = explode('-', $day);
        $day = intval($dayTab[0] . '-' . intval($dayTab[1]) . '-' . intval($dayTab[2]));

        $sessionTable = [];
        // On parcourt le tableau utilisateur pour récupérer uniquement les séances correspondant au jour donné
        foreach ($sessions as $session) {
            if ($day === $session->getSessionDate()) {
                $sessionTable[] = $session;
            }
        }
        return $sessionTable;
    }

    /**
     * Permet de générer un tableau des sessions liées
     * @param $sessionTable
     * @return array
     */
    public function bundleSession($sessionTable)
    {
        // Tableau final avec les résultats définitifs
        $result = [];

        // index du tableau de résultat
        $i = 0;

        while (!empty($sessionTable)) {
            // On récupère la première valeur du tableau à parser
            $sessionToCompare = array_shift($sessionTable);
            $tmp = [];

            // Dans un premier temps, on compare la session récupérée ($sessionToCompare)
            // et on la compare à toutes les sessions restantes dans le premier tableau
            // Si les sessions sont liées on les regroupe dans un tableau
            foreach ($sessionTable as $session) {
                if ($this->sessionCompare($sessionToCompare, $session)) {
                    $tmp[] = $session;
                    // On récupère l'index de la session
                    $key = array_search($session, $sessionTable);
                    // On supprime la session du tableau
                    array_splice($sessionTable, $key, 1);
                    $sessionToCompare->setPreviousSession($session);
                }
            }

            // 2ème vérification : On compare de nouveau sauf dans le cas ou nous avons déjà effectué une comparaison
            if (isset($result[$i])) {
                foreach ($result[$i] as $tab) {
                    foreach ($tab as $object) {
                        if (!is_null($sessionToCompare->getPreviousSession()) && !in_array($object, $sessionToCompare->getPreviousSession() )) {
                            if ($this->sessionCompare($sessionToCompare, $object)) {
                                $sessionToCompare->setPreviousSession($object);
                            }
                        }
                    }
                }
            }

            $tmp[] = $sessionToCompare;
            $result[$i]['sessions'] = $tmp;

            // Pour chaque groupe de séance, on détermine la première heure et la dernière heure pour déterminer
            // la taille de la cellule
            $result[$i]['firstHour'] = $this->firstHour($tmp);
            $result[$i]['lastHour'] = $this->lastHour($tmp);
            $i++;
        }
        return $result;
    }

    /**
     * permet de générer une journée type en html
     * @param array $day Ce tableau correspond
     * @param array $options
     * @return string
     */
    public function buildDay(
        array $day,
        array $options = [
            'cellClass' => null,
            'seanceClass' => null,
            'seanceTitleClass' => null,
            'cellWidth' => 100,
        ])
    {
        // On tri les sessions dans l'ordre chronologique
        $day = $this->sortSessionByHour($day);
        $str = '';
        // $i est l'heure de début d'une journée
        $i = $this->getStartTimeDay();

        // Tant que l'heure de fin n'est pas atteinte on continue le traitement des sessions
        while ($i < $this->getEndTimeDay()) {
            // On initialise la cellule
            $str .= '<div class="'. $options['cellClass'] .'">';

            foreach ($day as $groupSeance) {
                // Dans le cas ou l'heure de debut correspond à l'heure de début de la cellule
                  if ($i === $groupSeance['firstHour'] ) {
                      // On compte le nombre de séance qui se chevauche
                      $sessionsNumber = count($groupSeance['sessions']);

                      $str .= $this->generateStringHTML($sessionsNumber, $groupSeance, $options, 'H');

                  }
            }
            $str .= '</div>';
            $i += 15;
        }
        return $str;
    }

    /**
     * permet de générer une journée type en html de manière vertical (jour de la semaine en ligne, heure colonne)
     * @param array $day Ce tableau correspond
     * @param array $options
     * @return string
     */
    public function buildDayV(
        array $day, // sessions d'une journée donnée
        array $options = [
            'cellClass' => null,
            'seanceClass' => null,
            'seanceTitleClass' => null,
            'cellHeight' => 100,
        ])
    {
        // On tri les sessions dans l'ordre chronologique
        $day = $this->sortSessionByHour($day);
        $str = '';
        // $i est l'heure de début d'une journée
        $i = $this->getStartTimeDay();

        // Tant que l'heure de fin n'est pas atteinte on continue le traitement des sessions
        while ($i < $this->getEndTimeDay()) {
            // On initialise la cellule
            $str .= '<div class="'. $options['cellClass'] .'">';

            foreach ($day as $groupSeance) {
                // Dans le cas ou l'heure de debut correspond à l'heure de début de la cellule
                if ($i === $groupSeance['firstHour'] ) {
                    // On compte le nombre de séance qui se chevauche
                    $sessionsNumber = count($groupSeance['sessions']);

                   $str .= $this->generateStringHTML($sessionsNumber, $groupSeance, $options, 'V');

                }
            }
            $str .= '</div>';
            $i += 15;
        }
        return $str;
    }

    private function generateStringHTML($sessionsNumber, $groupSeance, $options, $orientation){
        $str = '';
        $firstHour = $groupSeance['firstHour'];
        $indexSession = 0;
        // Dans le cas ou il y aurait plus d'une session sur le même créneau
        if ($sessionsNumber > 1) {
            $percent = 100 / $sessionsNumber; // Pour déterminer la taille (hauteur ou largeur selon l'orientation) d'une séance
            if ($orientation === 'V') {
                $str .='<div class="groupSeanceV">';
            } else if ($orientation === 'H') {
                $str .='<div class="groupSeance">';
            }

            foreach($groupSeance['sessions'] as $seance) {
                $indexSession += 1;
                $coefficient = $seance->getSessionLength() / 15;
                $length = $seance->getSessionLength() + (($coefficient - 1)*3);

                $top = $distance = $seance->getSessionStartTime() - $firstHour;
                if (($seance->getSessionStartTime() - $firstHour) > 0) {
                    $distance = $seance->getSessionStartTime() - $firstHour;
                    $newCoefficient = $distance / 15;
                    $top = $distance + ($newCoefficient*3);
                }

                if ($orientation === 'V') {
                    $str .= '<div class="'. $options['seanceClass'] .'" style="width: '. $length . 'px; height:
                               '. $percent .'%; position: relative; left: ' . $top . 'px;">';
                } else if ($orientation === 'H') {
                    $str .= '<div class="'. $options['seanceClass'] .'" style="width: '. $percent . '%; height:
                               '. $length .'px; position: relative; top: ' . $top . 'px;">';
                }


                $str .= '<div class="'. $options['seanceTitleClass'] .'" style="position: relative; background-color: '. $seance->getColor().';">'
                    . '<span>' . $seance->getSessionType() . '</span><br/>'
                    . '<span>' . $seance->getStartTimeFormatHour() .'</span>'
                    . '<span>' . $seance->getEndTimeFormatHour() .'</span>'
                    . '</div>'

                    . '<div class="seance-content">'
                    . '<div>' . $seance->getSubjectAlias() . '</div>'
                    . '<div>' . $seance->getSessionTeacher() . '</div>'
                    . '<div>' . $seance->getRoomAlias() . '</div>'
                    . '</div>'
                ;
                $str .= '</div>';
            }
            $str .='</div>';
            // Cas ou il n' y a pas de sessions regroupées
        } else {
            foreach($groupSeance['sessions'] as $seance) {
                $coefficient = $seance->getSessionLength() / 15;
                // Longueur d'une séance en comptant les bordures (nbre de cell 15 px + 1 px)
                $length = $seance->getSessionLength() + (($coefficient - 1)*3);

                if ($orientation === 'V') {
                    $str .= '<div class="'.$options['seanceClass'].'" style="width: '. $length. 'px; height: 100%; position: relative;">';
                } else if ($orientation === 'H') {
                    $str .= '<div class="'.$options['seanceClass'].'" style="width: 100%; height: '.$length.'px; position: relative;">';
                }

                $str .= '<div class="'. $options['seanceTitleClass'] .'" style="position: relative; background-color: '. $seance->getColor() .';">'
                    . '<span>' .$seance->getSessionType() . '</span>'
                    . '<span>' . $seance->getStartTimeFormatHour() .'</span>-'
                    . '<span>' . $seance->getEndTimeFormatHour() .'</span>'
                    . '</div>'
                    . '<div class="seance-content">'
                    . '<div>' . $seance->getSubjectAlias() . '</div>'
                    . '<div>' . $seance->getSessionTeacher() . '</div>'
                    . '<div>' . $seance->getRoomAlias() . '</div>'
                    . '</div>'
                ;
                $str .= '</div>';
            }
        }
        return $str;
    }


    /**
     * Permet de trier les groupes de séances et les mettre dans l'ordre chronologique
     * @param $tab
     * @return array
     */
    public function sortSessionByHour($tab) {

        $eltNumber = count($tab);

        for ($i = 0; ($i < $eltNumber - 1); $i++) {
            for ($j = 0; $j <($eltNumber -1 - $i); $j++) {
                if ($tab[$j]['firstHour'] > $tab[$j+1]['firstHour']) {
                    $tmp = $tab[$j+1];
                    $tab[$j+1] = $tab[$j];
                    $tab[$j] = $tmp;
                }
            }
        }
        return $tab;
    }

    /**
     * @param array $tab
     * @return mixed
     */
    private function firstHour(array $tab)
    {
        $tmp = $tab;
        $firstElt = array_shift($tmp);
        $firstHour = $firstElt->getSessionStartTime();

        foreach ($tmp as $elt) {
            if ($elt->getSessionStartTime() < $firstHour) {
                $firstHour = $elt->getSessionStartTime();
            }
        }
        return $firstHour;
    }

    /**
     * @param array $tab
     * @return mixed
     */
    private function lastHour(array $tab)
    {
        $tmp = $tab;
        $firstElt = array_shift($tmp);
        $lastHour = $firstElt->getSessionEndTime();

        foreach ($tmp as $elt) {
            if ($elt->getSessionEndTime() > $lastHour) {
                $lastHour = $elt->getSessionEndTime();
            }
        }
        return $lastHour;
    }

    /**
     * Méthode permettant de générer une colonne heure
     * @param $start // heure de début de jounnée en minutes
     * @param $end // heure de fin de journée en minutes
     * @return string
     */
    public function colHours($start, $end)
    {
        $startTime = $start;
        // Nombre d'heures dans la journée
        $nbHours = $end - $start;

        // Nombre de ligne (1/4 heure = 1ligne)
        $nbRows = $nbHours / 15;

        $str = '<div id="hours">';
        for ($i = 0; $i < ($nbRows + 1); $i++) {
            $str .= '<div class="hour">';
            $str .= '<span>';
            if ($startTime%60 === 0) {
                $str .= $startTime/60 . 'h00';
            } elseif ($startTime%60 === 30) {
                $str .= round($startTime/60, 0, PHP_ROUND_HALF_DOWN) .'h30';
            }
            $str .= '</span></div>';
            $startTime += 15;
        }
        $str .= '</div>';
        return $str;
    }

    /**
     * @param $start
     * @param $end
     * @return string
     */
    public function rowHours($start, $end)
    {
        $startTime = $start;
        // Nombre d'heures dans la journée
        $nbHours = $end - $start;

        // Nombre de colonnes (1/4 heure = 1 colonne)
        $nbRows = $nbHours / 15;
        $str = '<span class="hourV"></span><span class="hourV"></span>';

        for ($i = 0; $i < ($nbRows + 1); $i++)
        {
            $str .= '<span class="hourV">';
            if ($start % 60 === 0) {
                $str .= intdiv($start, 60) . 'h00';
            } elseif ($start % 60 === 30) {
                $str .= intdiv($start, 60) . 'h30';
            }
            $str .= '</span>';
            $start += 15;
        }
        return $str;
    }

    /**
     * @return float|int
     */
    public function getStartTimeDay()
    {
        return $this->startTimeDay;
    }

    /**
     * @param float|int $startTimeDay
     */
    public function setStartTimeDay($startTimeDay)
    {
        $this->startTimeDay = $startTimeDay;
    }

    /**
     * @return float|int
     */
    public function getEndTimeDay()
    {
        return $this->endTimeDay;
    }

    /**
     * @param float|int $endTimeDay
     */
    public function setEndTimeDay($endTimeDay)
    {
        $this->endTimeDay = $endTimeDay;
    }

    /**
     * @return mixed
     */
    public function getCellnumber()
    {
        return $this->cellnumber;
    }

    /**
     * @param mixed $cellnumber
     */
    public function setCellnumber($cellnumber)
    {
        $this->cellnumber = $cellnumber;
    }

    /**
     * @return mixed
     */
    public function getSessionTable()
    {
        return $this->sessionTable;
    }

    /**
     * @param mixed $sessionTable
     */
    public function setSessionTable($sessionTable)
    {
        $this->sessionTable = $sessionTable;
    }
}
