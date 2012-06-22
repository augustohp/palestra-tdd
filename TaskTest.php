<?php
require 'SfCon/Task.php';

class TaskTest extends PHPUnit_Framework_TestCase
{
    public function testSetterGetterForTitle()
    {
        $task  = new SfCon\Task;
        $title = 'Teste';
        $task->setTitle($title);
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($title, (string) $task);
    }

    public function testSetterGetterForId()
    {
        $task = new SfCon\Task();
        $id   = 1;
        $task->setId($id);
        $this->assertEquals($id, $task->getId());
    }

    public function testSetterGetterForDone()
    {
        $task = new SfCon\Task();
        $this->assertFalse($task->isDone());
        $task->setDone(); // Default: true
        $this->assertTrue($task->isDone());
        $task->setDone(false);
        $this->assertFalse($task->isDone());
    }

    public function testInsert()
    {
        $pdo = new Pdo('sqlite::memory:');
        $pdo->exec('CREATE TABLE tasks (
            id INTEGER PRIMARY KEY,
            title TEXT,
            done INTEGER
        )');
        $task = new SfCon\Task($pdo);
        $expectId = 1;
        $task->setTitle('Test');
        $task->insert(); // Inserts must define ID into the object
        $this->assertEquals($expectId, $task->getId());
        $st = $pdo->prepare('SELECT id FROM tasks');
        $st->execute();
        $all = $st->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($all));
        $this->assertContains($expectId, array_shift($all));
    }
}