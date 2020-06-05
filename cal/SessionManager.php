<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class SessionManager
{

    public function __construct()
    {
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
            ($sessionB->getEndTime() > $sessionA->getStartTime() &&
                $sessionB->getStartTime() <= $sessionA->getStartTime())
            ||
            ($sessionB->getStartTime() < $sessionA->getEndTime() &&
                $sessionB->getEndTime() >= $sessionA->getEndTime())
            ||
            ($sessionB->getStartTime() >= $sessionA->getStartTime() &&
                $sessionB->getEndTime() <= $sessionA->getEndTime())
        ) {
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
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
            if ($day == $session->getDate()) {
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
        $firstHour = $firstElt->getStartTime();

        foreach ($tmp as $elt) {
            if ($elt->getStartTime() < $firstHour) {
                $firstHour = $elt->getStartTime();
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
        $lastHour = $firstElt->getEndTime();

        foreach ($tmp as $elt) {
            if ($elt->getEndTime() > $lastHour) {
                $lastHour = $elt->getEndTime();
            }
        }
        return $lastHour;
    }
}
