<?php
require 'SfCon/Task.php';

class TaskTest extends PHPUnit_Framework_TestCase
{
    public function testTitle()
    {
        $task  = new SfCon\Task;
        $title = 'Teste';
        $task->setTitle($title);
        $this->assertEquals($title, $task->getTitle());
        $this->assertEquals($title, (string) $task);
    }

    public function testId()
    {
        $task = new SfCon\Task();
        $id   = 1;
        $task->setId($id);
        $this->assertEquals($id, $task->getId());
    }

    public function testDone()
    {
        $task = new SfCon\Task();
        $this->assertFalse($task->isDone());
        $task->setDone(); // Default: true
        $this->assertTrue($task->isDone());
        $task->setDone(false);
        $this->assertFalse($task->isDone());
    }
}