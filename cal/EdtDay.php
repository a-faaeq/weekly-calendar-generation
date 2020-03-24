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
     * @return array
     */
    public function sessions(array $data) {
        $sessions = [];
        foreach ($data as $session) {
            $object = new Session($session);
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
        $sessionTable = [];
        // On parcourt le tableau utilisateur pour récupérer uniquement les séances correspondant au jour donné
        foreach ($sessions as $session) {
            if ($day === $session->getSessionDate()) {
                $sessionTable[] = $session;
            }
        }
        return $sessionTable;
    }

    public function getHours()
    {
        $str = '<div style="height: 40px;"></div>';
        $start = 480;
        for ($i = 0; $i < 22; $i++) {
            $str .= '<div class="hour">' . $start . '</div>';
            $start += 30;
        }

        return $str;
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
    public function buildDay(array $day, array $options = null)
    {
        // On tri les sessions dans l'ordre chronologique
        $day = $this->sortSessionByHour($day);
        $str = '';
        // $i est l'heure de début d'une journée
        $i = $this->getStartTimeDay();

        // Tant que l'heure de fin n'est pas atteinte on continue le traitement des sessions
        while ($i < $this->getEndTimeDay()) {
            $str .= '<div class="cell">';
            foreach ($day as $groupSeance) {

                  if ($i === $groupSeance['firstHour'] ) {
                     // $diff = $groupSeance['lastHour'] - $groupSeance['firstHour'];

                      $sessionsNumber = count($groupSeance['sessions']);
                      $firstHour = $groupSeance['firstHour'];

                      $indexSession = 0;

                      // Dans le cas ou il y aurait plus d'une session sur le même créneau
                      if ($sessionsNumber > 1) {
                          $percent = 100 / $sessionsNumber;
                          $str .='<div class="groupSeance">';
                          foreach($groupSeance['sessions'] as $seance) {
                              $indexSession += 1;
                              $coefficient = $seance->getSessionLength() / 15;
                              $length = $seance->getSessionLength() + ($coefficient * 3);

                              $top = $distance = $seance->getSessionStartTime() - $firstHour;;
                              if (($seance->getSessionStartTime() - $firstHour) > 0) {
                                  $distance = $seance->getSessionStartTime() - $firstHour;
                                  $newCoefficient = $distance / 15;
                                  $top = $distance + ($newCoefficient*3);
                              }

                              $str .= '<div class="seance" style="width: '. $percent . '%; height: '
                                  . ($length) . 'px; position: relative; top: ' . $top . 'px;">';

                              $str .= '<div class="seance-title" style="position: relative; background-color: #1fff25;">' . $seance->getSessionType() . '</div>';
                              $str .= '</div>';
                          }
                          $str .='</div>"';
                      } else {
                          foreach($groupSeance['sessions'] as $seance) {
                              $coefficient = $seance->getSessionLength() / 15;
                              $length = $seance->getSessionLength() + ($coefficient * 2);
                              $str .= '<div class="seance" style="width: 100%; height: ' . ($length) . 'px; position: relative;">';
                              $str .= '<div class="seance-title" style="position: relative; background-color: #b9aafb;">' . $seance->getSessionType() . '</div>';
                              $str .= '</div>';
                          }
                      }
                  }
            }
            $str .= '</div>';
            $i += 15;
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
