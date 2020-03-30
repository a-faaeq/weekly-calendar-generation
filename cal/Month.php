<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class Month {

    private $months = [
        'janvier',
        'février',
        'mars',
        'avril',
        'mai',
        'juin',
        'juillet',
        'août',
        'septembre',
        'octobre',
        'novembre',
        'décembre'
    ];
    private $days = [
      'Lundi',
      'Mardi',
      'Mercredi',
      'Jeudi',
      'Vendredi',
      'Samedi',
      'Dimanche'
    ];
    private $month;
    private $year;
    private $week;

    /**
     * Month constructor.
     * @throws
     * @param int $month Le mois compris entre 1 et 12
     * @param int $year L'année
     * @param int $week le numéro de semaine
     */
    public function __construct(int $year, int $month, int $week = null)
    {
        if ($month < 0 || $month > 12) {
            throw new \Exception(" Le mois $month n'est pas valide");
        }

        if ($year < 1970) {
            throw new \Exception("L'année est inférieur à 1970");
        }

        if (!is_null($week)) {
            if ($week < 1 || $week > 54 ) {
                throw new \Exception("Le nombre de semaine indiqué est erroné");
            }
        }

        $this->setMonth($month);
        $this->setYear($year);

        if (!is_null($week)) {
            $this->setWeek($week);
        } else {
            $this->setWeek($this->getFirstWeek());
        }
    }

    /**
     * Retourne le mois en toute lettre
     * @return string
     */
    public function toString (): string {

        $months = $this->getMonths();

        return $months[$this->getMonth() - 1];
    }


    /**
     * Permet de récupérer le nombre de semaine sur 1 mois donné
     * @return int
     * @throws \Exception
     */
    public function getWeeks(): int
    {
        $start = $this->startMonthDay(); // Date du 1er jour du mois
        $end = $this->endMonthDay(); // Date du dernier jour du mois
        $cloneEnd = clone ($end);

        // Dans le cas ou la date de fin serait contenu dans la 1ère semaine de l'année
        if (intval($end->format('W')) === 1) {
            $cloneEnd->modify('- 1 week');
            $weekEnd = intval($cloneEnd->format('W')) + 1;
        } else {
            $weekEnd = intval($end->format('W'));
        }

        $weeks = $weekEnd - intval($start->format('W')) + 1;

        if ($weeks < 0) {
            $weeks = intval($end->format('W')) + 1;
        }

        return $weeks;
    }

    /**
     * Donne le 1er jour du mois
     * @return \DateTime
     * @throws \Exception
     */
    public function startMonthDay() {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

    /**
     * Permet d'obtenir la date du premier jour de l'année
     * @return \DateTime
     * @throws \Exception
     */
    public function startYearDay() {
        return new \DateTime("{$this->year}-01-01");
    }

    /**
     * @param \DateTime $date
     * @return \DateTime
     */
    public function lastMonday(\DateTime $date)
    {
        if ($date->format('w') !== "1") {
            $date = clone($date)->modify('last monday');
        }
        return $date;
    }

    /**
     * Permet de récupérer le lundi d'une semaine donnée
     * @param $week
     * @return \DateTime
     * @throws \Exception
     */
    public function mondayOfWeek($week)
    {
        $firstDateYear = $this->startYearDay();
        $monday = clone($firstDateYear);
        $day = intval($monday->format('w'));

        // Dans le cas ou le 1er jour n'est pas un lundi
        if ($day !== 1) {
            $monday->modify(' last monday');
        }

        // On récupère la semaine du premier lundi
        $firstWeek = intval($monday->format('W'));

        // Dans le cas ou la semaine n'est pas la première de l'année (Le jour 1 n'est pas un lundi)
        if ($firstWeek !== 1) {
            return $monday->modify(' +' . ($week - 1) . ' week');
        }

        return $monday->modify('+'. ($week - 2) . ' week');
    }

    /**
     * Donne le dernier jour du mois
     * @return \DateTime
     * @throws \Exception
     */
    public function endMonthDay() {
        return (clone $this->startMonthDay())->modify(' +1 month -1 day');
    }

    /**
     * Permet de récupérer le numéro de première semaine d'un mois
     * @return int
     * @throws \Exception
     */
    public function getFirstWeek() : int
    {
        return $this->startMonthDay()->format('W');
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function miniCal()
    {
        $string = '';
        $weeksNumber = $this->getWeeks();
        $days = $this->getDays();
        $firstWeek = $this->getFirstWeek();
        $monday = $this->lastMonday($this->startMonthDay());

        $string .='<tr><th>LUN</th><th>MAR</th><th>MER</th><th>JEU</th><th>VEN</th><th>SAM</th><th>DIM</th></tr>';

        $string .= '<tr id="week-' . $firstWeek . '">';
        for ($i = 0; $i < $weeksNumber; $i++) {
            foreach ($days as $k=>$day) {
                $date = (clone $monday)->modify("+" . ($k + $i * 7) . " day");

                if (intval($date->format('m')) !== $this->getMonth()) {
                    $string .= '<td style="color: gray;">' . $date->format('d') . '</td>';
                } else {
                    $string .= '<td id="day-' . $date->format('d') . '">' . $date->format('d') . '</td>';
                }

                if ($k === 6) {
                    $cloneDate = clone($date->modify('+ 1 week'));
                    $string .= '</tr><tr id="week-' . ($cloneDate->format('W')). '">';
                }
            }
        }
        return $string;
    }

    /**
     * @return array
     */
    public function getMonths(): array
    {
        return $this->months;
    }

    /**
     * @param array $months
     */
    public function setMonths(array $months): void
    {
        $this->months = $months;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = $month;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): void
    {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * @param mixed $week
     */
    public function setWeek($week): void
    {
        $this->week = $week;
    }

    /**
     * @return array
     */
    public function getDays(): array
    {
        return $this->days;
    }
}
