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

    public function tearDown()
    {
        $this->pdo->exec('DROP TABLE tasks');
    }

    public function provideValidTitles()
    {
        return array(
            array('This is a valid title'),
            array('This is also a valid title ...'),
            array('Hello World'),
            array('Hakuna Matata'),
            array('Do some more tests')
        );
    }

    /**
     * @dataProvider provideValidTitles
     */
    public function testSetterGetterForTitle($title)
    {
        $instance = $this->fixture->setTitle($title);   // Returns the object
        $this->assertEquals($this->fixture, $instance); // Test object instance
        $this->assertEquals($title, $this->fixture->getTitle());
        $this->assertEquals($title, (string) $this->fixture);
    }

    public function provideValidIds()
    {
        return array(
            array(1),
            array(9),
            array(\PHP_INT_MAX),
        );
    }

    /**
     * @dataProvider provideValidIds
     */
    public function testSetterGetterForId($id)
    {
        $instance = $this->fixture->setId($id);         // Returns the object
        $this->assertEquals($this->fixture, $instance); // Test object instance
        $this->assertEquals($id, $this->fixture->getId());
    }

    public function testSetterGetterForDone()
    {
        $this->assertFalse($this->fixture->isDone());
        $instance = $this->fixture->setDone();          // Default: true, returns the object
        $this->assertEquals($this->fixture, $instance); // Test object instance
        $this->assertTrue($this->fixture->isDone());
        $this->fixture->setDone(false);
        $this->assertFalse($this->fixture->isDone());
    }

    /**
     * @dataProvider provideValidTitles
     */
    public function testInsert($title)
    {
        $expectId = 1;
        $this->fixture->setTitle($title);
        $this->fixture->insert(); // Inserts must define ID into the object
        $this->assertEquals($expectId, $this->fixture->getId());
        $st = $this->pdo->prepare('SELECT id, title FROM tasks');
        $st->execute();
        $all = $st->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals(1, count($all));
        $one = array_shift($all);
        $this->assertContains($expectId, $one);
        $this->assertContains($title, $one);
    }
}