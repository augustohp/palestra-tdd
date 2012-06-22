<?php
namespace SfCon;

class Task
{
    protected $id;
    protected $title;
    protected $done = false;

    public function setId($int)
    {
        $this->id = $int;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($string)
    {
        $this->title = $string;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        return (string) $this->getTitle();
    }

    public function setDone($bool=true)
    {
        $this->done = (boolean) $bool;
        return $this;
    }

    public function isDone()
    {
        return $this->done;
    }
}