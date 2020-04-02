<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class Session {

    private $sessionCode; // CodeSeance
    private $sessionDate; // DateSeance
    private $sessionStartTime; // HeureSeance
    private $sessionEndTime;
    private $sessionLength; // DureeSeance
    private $sessionType; // TypeSeance
    private $sessionSubject; // MatiereSeance
    private $sessionTeacher; // Prof
    private $sessionRoom; // Salle
    private $sessionGroups;
    private $previousSession;
    private $color;
    private $startTimeFormatHour;
    private $endTimeFormatHour;

    public function __construct($data, $colors)
    {
        $this->setSessionCode($data[0]);
        $this->setSessionDate($data[1]);
        $this->setSessionStartTime($data[2]);
        $this->setSessionLength($data[3]);
        $this->setSessionType($data[4]);
        $this->setSessionSubject($data[5]);
        $this->setSessionTeacher($data[6]);
        $this->setSessionRoom(($data[7]));
        $this->setSessionEndTime($this->sessionStartTime + $this->sessionLength);
        $this->setColor($colors);
        $this->setStartTimeFormatHour($this->sessionStartTime);
        $this->setEndTimeFormatHour($this->endTimeFormatHour);
    }

    /**
     * @return mixed
     */
    public function getSessionStartTime()
    {
        return $this->sessionStartTime;
    }

    /**
     * @param mixed $sessionStartTime
     */
    public function setSessionStartTime($sessionStartTime)
    {
        $sessionStartTime = intval($sessionStartTime);
        $nb = strlen($sessionStartTime);

        if ($nb <= 2) {
            $this->sessionStartTime = $sessionStartTime;
        } elseif ($nb === 3) {
            $tmp = str_split($sessionStartTime);
            $hours = intval($tmp[0]);
            $min = intval($tmp[1] . $tmp[2]);
            $this->sessionStartTime = ($hours * 60) + $min;
        } elseif ($nb === 4) {
            $tmp = str_split($sessionStartTime);
            $hours = intval($tmp[0] . $tmp[1]);
            $min = intval($tmp[2] . $tmp[3]);
            $this->sessionStartTime = ($hours * 60) + $min;
        }
    }

    /**
     * @return mixed
     */
    public function getSessionEndTime()
    {
        return $this->sessionEndTime;
    }

    /**
     * @param mixed $sessionEndTime
     */
    public function setSessionEndTime($sessionEndTime)
    {
        $this->sessionEndTime = $sessionEndTime;
    }

    /**
     * @return mixed
     */
    public function getSessionLength()
    {
        return $this->sessionLength;
    }

    /**
     * @param $minTime
     * @return string
     */
    public function formatHour(int $minTime): string
    {
        $minTime = intval($minTime);
        $modulo = $minTime%60;

        if ($modulo === 0) {
            return intval($minTime/60) . 'h00';
        }

        return intval($minTime/60) . 'h' . $modulo;
    }

    /**
     * @param mixed $sessionLength
     */
    public function setSessionLength($sessionLength)
    {
        $sessionLength = intval($sessionLength);
        $nb = strlen($sessionLength);

        if ($nb <= 2) {
            $this->sessionLength = $sessionLength;
        } elseif ($nb === 3) {
            $tmp = str_split($sessionLength);
            $hours = intval($tmp[0]);
            $min = intval($tmp[1] . $tmp[2]);
            $this->sessionLength = ($hours * 60) + $min;
        } elseif ($nb === 4) {
            $tmp = str_split($sessionLength);
            $hours = intval($tmp[0] . $tmp[1]);
            $min = intval($tmp[2] . $tmp[3]);
            $this->sessionLength = ($hours * 60) + $min;
        }
    }

    /**
     * @return mixed
     */
    public function getSessionType()
    {
        return $this->sessionType;
    }

    /**
     * @param mixed $sessionType
     */
    public function setSessionType($sessionType)
    {
        $this->sessionType = $sessionType;
    }

    /**
     * @return mixed
     */
    public function getPreviousSession()
    {
        return $this->previousSession;
    }

    /**
     * @param mixed $previousSession
     */
    public function setPreviousSession($previousSession)
    {
        $this->previousSession[] = $previousSession;
    }

    /**
     * @return mixed
     */
    public function getSessionCode()
    {
        return $this->sessionCode;
    }

    /**
     * @param mixed $sessionCode
     */
    public function setSessionCode($sessionCode): void
    {
        $this->sessionCode = $sessionCode;
    }

    /**
     * @return mixed
     */
    public function getSessionDate()
    {
        return $this->sessionDate;
    }

    /**
     * @param mixed $sessionDate
     */
    public function setSessionDate($sessionDate): void
    {
        $tmp = explode('-', $sessionDate);
        $sessionDate = intval($tmp[0] . '-' . intval($tmp[1]) . '-' . intval($tmp[2]));
        $this->sessionDate = $sessionDate;
    }

    /**
     * @return mixed
     */
    public function getSessionSubject()
    {
        return $this->sessionSubject;
    }

    /**
     * @param mixed $sessionSubject
     */
    public function setSessionSubject($sessionSubject): void
    {
        $this->sessionSubject = $sessionSubject;
    }

    /**
     * @return mixed
     */
    public function getSessionTeacher()
    {
        return $this->sessionTeacher;
    }

    /**
     * @param mixed $sessionTeacher
     */
    public function setSessionTeacher($sessionTeacher): void
    {
        $this->sessionTeacher = $sessionTeacher;
    }

    /**
     * @return mixed
     */
    public function getSessionRoom()
    {
        return $this->sessionRoom;
    }

    /**
     * @param mixed $sessionRoom
     */
    public function setSessionRoom($sessionRoom): void
    {
        $this->sessionRoom = $sessionRoom;
    }

    /**
     * @return mixed
     */
    public function getSessionGroups()
    {
        return $this->sessionGroups;
    }

    /**
     * @param mixed $sessionGroups
     */
    public function setSessionGroups($sessionGroups): void
    {
        $this->sessionGroups[] = $sessionGroups;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $colors
     */
    public function setColor(array $colors): void
    {
        $type = $this->getSessionType();

        foreach ($colors as $key=>$color)
        {
            if ($key === $type) {
                $this->color = $color;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getStartTimeFormatHour()
    {
        return $this->startTimeFormatHour;
    }

    /**
     * @param mixed $startTimeFormatHour
     */
    public function setStartTimeFormatHour($startTimeFormatHour): void
    {
        $this->startTimeFormatHour = $this->formatHour(intval($startTimeFormatHour));
    }

    /**
     * @return mixed
     */
    public function getEndTimeFormatHour()
    {
        return $this->endTimeFormatHour;
    }

    /**
     * @param mixed $endTimeFormatHour
     */
    public function setEndTimeFormatHour($endTimeFormatHour): void
    {
        $this->endTimeFormatHour = $this->formatHour(intval($endTimeFormatHour));
    }


}
