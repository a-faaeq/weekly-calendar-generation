<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class Day {

    private $startTimeDay; // start time of day in hour
    private $endTimeDay; // end time of day in hour
    private $htmlGenerator;
    private $sessionManager;

    public function __construct($startTimeDay, $endTimeDay, HtmlGenerator $htmlGenerator, SessionManager $sessionManager)
    {
        $this->setStartTimeDay($startTimeDay*60);
        $this->setEndTimeDay($endTimeDay*60);
        $this->htmlGenerator = $htmlGenerator;
        $this->sessionManager = $sessionManager;
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
        $day = $this->sessionManager->sortSessionByHour($day);
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
                      $str .= $this->htmlGenerator->generateString($sessionsNumber, $groupSeance, $options, 1);
                  }
            }
            $str .= '</div>';
            $i += 15;
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
}
