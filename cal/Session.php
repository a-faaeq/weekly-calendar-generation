<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class Session {

    private $code; // CodeSeance
    private $date; // DateSeance
    private $startTime; // HeureSeance
    private $endTime;
    private $length; // DureeSeance
    private $category; // TypeSeance
    private $subject; // MatiereSeance
    private $teacher; // Prof
    private $room; // Salle
    private $groups;
    private $previousSession;
    private $color;
    private $startTimeFormatHour;
    private $endTimeFormatHour;
    private $subjectAlias;
    private $roomAlias;

    /**
     * Session constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->setCode($data[0]);
        $this->setDate(new \DateTime($data[1]));
        $this->setStartTime($data[2]);
        $this->setLength($data[3]);
        $this->setCategory($data[4]);
        $this->setSubject($data[5]);
        $this->setSubjectAlias($data[6]);
        $this->setTeacher($data[7]);
        $this->setRoom(($data[8]));
        $this->setRoomAlias($data[9]);
        $this->setEndTime($this->startTime + $this->length);
        $this->setStartTimeFormatHour($this->startTime);
        $this->setEndTimeFormatHour($this->endTime);
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setstartTime($startTime)
    {
        $startTime = intval($startTime);
        $nb = strlen($startTime);

        if ($nb <= 2) {
            $this->startTime = $startTime;
        } elseif ($nb === 3) {
            $tmp = str_split($startTime);
            $hours = intval($tmp[0]);
            $min = intval($tmp[1] . $tmp[2]);
            $this->startTime = ($hours * 60) + $min;
        } elseif ($nb === 4) {
            $tmp = str_split($startTime);
            $hours = intval($tmp[0] . $tmp[1]);
            $min = intval($tmp[2] . $tmp[3]);
            $this->startTime = ($hours * 60) + $min;
        }
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
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
     * @param mixed $length
     */
    public function setLength($length)
    {
        $length = intval($length);
        $nb = strlen($length);

        if ($nb <= 2) {
            $this->length = $length;
        } elseif ($nb === 3) {
            $tmp = str_split($length);
            $hours = intval($tmp[0]);
            $min = intval($tmp[1] . $tmp[2]);
            $this->length = ($hours * 60) + $min;
        } elseif ($nb === 4) {
            $tmp = str_split($length);
            $hours = intval($tmp[0] . $tmp[1]);
            $min = intval($tmp[2] . $tmp[3]);
            $this->length = ($hours * 60) + $min;
        }
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param $date
     * @throws \Exception
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * @param mixed $teacher
     */
    public function setTeacher($teacher): void
    {
        $this->teacher = $teacher;
    }

    /**
     * @return mixed
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * @param mixed $room
     */
    public function setRoom($room): void
    {
        $this->room = $room;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $groups
     */
    public function setGroups($groups): void
    {
        $this->groups[] = $groups;
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
        $type = $this->getCategory();

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

    /**
     * @return mixed
     */
    public function getSubjectAlias()
    {
        return $this->subjectAlias;
    }

    /**
     * @param mixed $alias
     */
    public function setSubjectAlias($alias): void
    {
        $this->subjectAlias = $alias;
    }

    /**
     * @return mixed
     */
    public function getRoomAlias()
    {
        return $this->roomAlias;
    }

    /**
     * @param mixed $roomAlias
     */
    public function setRoomAlias($roomAlias): void
    {
        $this->roomAlias = $roomAlias;
    }
}
