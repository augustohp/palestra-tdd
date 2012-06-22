<?php
namespace SfCon;

class Task
{
    protected $title;

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
}