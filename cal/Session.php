<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class Session {

    private $sessionYear;
    private $sessionMonth;
    private $sessionDay;
    private $sessionStartTime;
    private $sessionEndTime;
    private $sessionLength;
    private $sessionType;
    private $day;
    private $stringSession;
    private $previousSession;


    public function __construct($stringSession, $day)
    {
        $this->setDay($day);
        $this->setStringSession($stringSession);
        $this->sessionProperties($this->getStringSession());
    }

    /**
     * Permet d'hydrader les attributs
     * @param $field
     */
    public function sessionProperties($field)
    {
        $tab = explode('-', $field);

        $this->setSessionYear(intval($tab['0']));
        $this->setSessionMonth(intval($tab[1]));
        $this->setSessionDay($this->getSessionYear() . '-' . $this->getSessionMonth() . '-' . $tab[2]);
        $this->setSessionStartTime(intval($tab[3] * 60));
        $this->setSessionEndTime($this->getSessionStartTime() + intval($tab[4]));
        $this->setSessionLength($this->getSessionEndTime() - $this->getSessionStartTime());
        $this->setSessionType($tab[5]);
    }


    /**
     * @return mixed
     */
    public function getSessionYear()
    {
        return $this->sessionYear;
    }

    /**
     * @param mixed $sessionYear
     */
    public function setSessionYear($sessionYear)
    {
        $this->sessionYear = $sessionYear;
    }

    /**
     * @return mixed
     */
    public function getSessionMonth()
    {
        return $this->sessionMonth;
    }

    /**
     * @param mixed $sessionMonth
     */
    public function setSessionMonth($sessionMonth)
    {
        $this->sessionMonth = $sessionMonth;
    }

    /**
     * @return mixed
     */
    public function getSessionDay()
    {
        return $this->sessionDay;
    }

    /**
     * @param mixed $sessionDay
     */
    public function setSessionDay($sessionDay)
    {
        $this->sessionDay = $sessionDay;
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
        $this->sessionStartTime = $sessionStartTime;
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
     * @param mixed $sessionLength
     */
    public function setSessionLength($sessionLength)
    {
        $this->sessionLength = $sessionLength;
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
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     */
    public function setDay($day)
    {
        $this->day = $day;
    }

    /**
     * @return string
     */
    public function getStringSession()
    {
        return $this->stringSession;
    }

    /**
     * @param string $stringSession
     */
    public function setStringSession($stringSession)
    {
        $this->stringSession = $stringSession;
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
}