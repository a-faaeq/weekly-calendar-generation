<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class CalendarBuilder
{
    private $startDate; // Date début du calendrier (Datetime)
    private $endDate; // Date fin du calendrier (DateTime)
    private $startTime; // Heure de début du calendrier en heures
    private $endTime; // Heure de fin du calendrier en heures
    private $data; // Tableau des résultats de filtres (nom du filtre, séances ...)
    private $orientation; // Vue du calendrier (0: horizontal, 1: vertical, 2: mensuel)
    private $cellSize; // Taille d'une cellule
    private $cssOptions;
    private $colors;
    private $sessionManager;
    private $day;
    private $period;

    /**
     * CalendarBuilder constructor.
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param $startTime
     * @param $endTime
     * @param array $data
     * @param $orientation
     * @param $cellSize
     * @param $cssOptions
     * @throws \Exception
     */
    public function __construct(
        \DateTime $startDate,
        \DateTime $endDate,
        $startTime,
        $endTime,
        array $data,
        $orientation,
        $cellSize,
        $cssOptions
    )
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->orientation = $orientation;
        $this->cellSize = $cellSize;
        $this->setCssOptions($cssOptions); // Tableau des class et id pour le css
        $this->sessionManager = New SessionManager();
        $this->day = new Day($startTime, $endTime, $orientation);
        $this->setPeriod($startDate, $endDate);
        $this->setColors();
        $this->data = $this->setData($data);
    }

    /**
     * @return string
     * Méthode permettant de générer la colonne des heures (pour le moment vue horizontale)
     */
    public function colHours() : string
    {
        $str = '';
        if ($this->orientation === 0) {
            $str = '<div id="'. $this->cssOptions['idHoursDiv'] .'">';
        } elseif ($this->orientation === 1) {
            $str = '<div>';
            $str .= '<span class="hourV"></span><span class="hourV"></span>';
        }

        $startTime = $this->startTime * 60;
        $endTime = $this->endTime * 60;
        $diff = $endTime - $startTime;

        // Sachant qu'une 1 heure = 4 x 15 min, le nombre de cell
        $cellNumber = $diff / 15;

        for ($i = 0; $i < ($cellNumber +1); $i++) {
            if ($this->orientation === 0) {
                $str.= '<div class="' . $this->cssOptions['cssHourClass'] . '">';
                $str .= '<span>';
            }

            if ($this->orientation === 1) {
                $str .= '<span class="hourV">';
            }

            if ($startTime % 60 === 0) {
                $str .= intdiv($startTime, 60) . 'h00';
            } elseif ($startTime % 60 === 30) {
                $str .= round($startTime/60, 0, PHP_ROUND_HALF_DOWN) .'h30';
            }
            if($this->orientation === 0) {
                $str .= '</span>';
                $str .= '</div>';
            }

            if ($this->orientation === 1) {
                $str .= '</span>';
            }
            $startTime += 15;
        }
        $str .= '</div>';
        return $str;
    }

    public function firstString() : string
    {
        if ($this->orientation === 1) {
            return '<div class="table-vertical">';
        }
        return '<div class="table-center">';
    }

    public function endString() : string
    {
        return '</div>';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function weekCalendar() : string
    {
        $period = $this->getPeriod();
        $str = $this->firstString();
        $str .= $this->colHours();
        foreach($period as $date)
        {
            $str .= $this->buildDay($date);
        }
        $str .= $this->endString();
        return $str;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function dayCalendar() : string
    {
        return $this->buildDay($this->startDate);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function monthCalendar() : string
    {
        $period = $this->getPeriod();
        $str = '';
        foreach ($period as $date) {
            $day = $date->format('w');

            if ($day === "1") {
                $str .= $this->firstString();
                $str .= $this->colHours();
                $str .= $this->buildDay($date);
            } else if ($day !== "0" && $day !== "6"){
                $str .= $this->buildDay($date);
            } else if ($day === "6") {
                $str .= '</div>';
            }
        }
        return $str;
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     * Méthode permettant d'hydrater les objets filtres avec leur séances avec les données reçues
     */
    public function setData($data): array
    {
        $filters = [];
        foreach ($data as $element) {
            $filter = $element['filter'];
            $tabFilter = explode('-', $filter);
            $category = $tabFilter[0];
            $code = $tabFilter[1];
            $name = $element['filterName'];
            $tabSessions = [];
            $sessions = $element['sessions'];

            foreach ($sessions as $session)
            {
                $sessionObject = new Session($session);
                $sessionObject->setColor($this->colors);
                $tabSessions[] = $sessionObject;
            }
            $filterObject = new \Sebius77\WeeklyCalendarGeneration\cal\Filter($category, $code, $name, $tabSessions);
            $filters[] = $filterObject;
        }
        return $filters;
    }

    /**
     * @param $date
     * @return string
     * @throws \Exception
     * Méthode permettant de générer un jour en fonction d'une date et des données récupérées par rapport à cette date
     */
    public function buildDay(\DateTime $date)
    {
        $str = '';
        $month = $date->format('m');
        $day = $date->format('d');

        if ($this->orientation === 0) {
            $str .= '<div class="day">';
            $str .= '<div class="title" style="width: 99%">' . $day . '/'. $month . '</div>';
            $str .= '<div class="filters">';
        }

        if ($this->orientation === 1) {
            $str .= '<div class="dayV">';
            $str .= '<div class="title-vertical">' . $day . '/'. $month . '</div>';
            $str .= '<div>';
        }

        $filterNumber = count($this->getData());
        foreach ($this->getData() as $filter) {

            if ($this->orientation === 0) {
                $str .= '<div style="width: '. 100/$filterNumber.'%">';
                $str .= '<div class="title">'. $filter->getName() .'</div>';
            }
            if ($this->orientation === 1) {
                $str.= '<div class="filter-vertical">';
                $str .= '<div class="title-vertical">'. $filter->getName() .'</div>';
            }
            // Récupération de toutes les séances
            $sessions = $filter->getSessions();

            $daySessions = $this->sessionManager->sessionTable($sessions, $date);
            $groupSession = $this->sessionManager->bundleSession($daySessions);
            if ($this->orientation === 0) {
                $str .= $this->day->buildSessions($groupSession,  $options = [
                    'cellClass' => 'cell',
                    'seanceClass' => 'seance',
                    'seanceTitleClass' => 'seance-title'
                ]);
            }
            if ($this->orientation === 1) {
                $str .= $this->day->buildSessions($groupSession,  $options = [
                    'cellClass' => 'cellV',
                    'seanceClass' => 'seanceV',
                    'seanceTitleClass' => 'seance-title'
                ]);
            }
                $str .= '</div>';
        }
        $str .= '</div></div>';
        return $str;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     * permet d'obtenir un tableau des jours
     */
    public function setPeriod(\DateTime $startDate, \DateTime $endDate)
    {

        $interval = intval($startDate->diff($endDate)->format('%a'));
        $this->period = [$startDate];

        for ($i=1; $i <= $interval; $i++) {
            $newDate = (clone $startDate)->modify('+' . $i . ' days');
            $this->period[] = $newDate;
        }
        return $this->period;
    }

    /**
     * @return mixed
     */
    public function getColors()
    {
        return $this->colors;
    }

    public function setColors(): void
    {
        $this->colors = (isset($this->getCssOptions()['colors'])) ? $this->getCssOptions()['colors'] : null;
    }

    public function setCssOptions($cssOptions): void
    {
        $this->cssOptions = $cssOptions;
    }

    /**
     * @return mixed
     */
    public function getCssOptions()
    {
        return $this->cssOptions;
    }
}
