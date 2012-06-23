<?php
require 'SfCon/Task.php';

class TaskTest extends PHPUnit_Framework_TestCase
{
    protected $fixture;
    protected $pdo;

    public function setUp()
    {
        $this->pdo     = new Pdo('sqlite::memory:');
        $this->fixture = new SfCon\Task($this->pdo);
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY,
            title TEXT,
            done INTEGER
        )');
    }

    public function testSetterGetterForTitle()
    {
        $title = 'Teste';
        $this->fixture->setTitle($title);
        $this->assertEquals($title, $this->fixture->getTitle());
        $this->assertEquals($title, (string) $this->fixture);
    }

    public function testSetterGetterForId()
    {
        $id   = 1;
        $this->fixture->setId($id);
        $this->assertEquals($id, $this->fixture->getId());
    }

    public function testSetterGetterForDone()
    {
        $this->assertFalse($this->fixture->isDone());
        $this->fixture->setDone(); // Default: true
        $this->assertTrue($this->fixture->isDone());
        $this->fixture->setDone(false);
        $this->assertFalse($this->fixture->isDone());
    }

    public function testInsert()
    {
        $expectId = 1;
        $this->fixture->setTitle('Test');
        $this->fixture->insert(); // Inserts must define ID into the object
        $this->assertEquals($expectId, $this->fixture->getId());
        $st = $this->pdo->prepare('SELECT id FROM tasks');
        $st->execute();
        $all = $st->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($all));
        $this->assertContains($expectId, array_shift($all));
    }
}