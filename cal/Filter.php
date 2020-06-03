<?php

namespace Sebius77\WeeklyCalendarGeneration\cal;

class Filter
{
    private $category; // teacher, group, room
    private $code; // resource code
    private $name;
    private $sessions;

    public function __construct($category, $code, $name, $sessions)
    {
        $this->category = $category;
        $this->code = $code;
        $this->name = $name;
        $this->sessions = $sessions;
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
    public function setCategory($category): void
    {
        $this->category = $category;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSessions()
    {
        $dateSession = [];


        foreach ($this->sessions as $session) {
            var_dump($session->getDate());
            die('test de fonctionnement');

        }


        return $this->sessions;
    }

    /**
     * @param mixed $sessions
     */
    public function setSessions($sessions): void
    {
        $this->sessions = $sessions;
    }
}
