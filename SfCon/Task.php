<?php
namespace SfCon;

class Task
{
    const SQL_INSERT = 'INSERT INTO tasks (id, title, done) VALUES (?, ?, ?)';
    const SQL_FETCHALL = 'SELECT id, title FROM tasks'; 
    protected $id;
    protected $title;
    protected $done = false;
    protected $pdo;

    public function __construct(\Pdo $pdo=null)
    {
        if (!is_null($pdo))
            $this->pdo = $pdo;
    }

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

    public function insert()
    {
        $st = $this->pdo->prepare(self::SQL_INSERT);
        $st->bindValue(1, $this->getId());
        $st->bindValue(2, $this->getTitle());
        $st->bindValue(3, $this->isDone());
        $result = $st->execute();
        $this->setId($this->pdo->lastInsertId());
        return $result;
    }
}