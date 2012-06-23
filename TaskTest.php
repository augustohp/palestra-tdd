<?php
require 'SfCon/Task.php';

class TaskTest extends PHPUnit_Framework_TestCase
{
    protected $fixture;
    protected $pdo;

    public function setUp()
    {
        $this->fixture = new SfCon\Task();
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
        // Mocking / Stubing
        $expectId = 1;
        $mockIns = $this->getMock('PdoStatement', array('bindValue', 'execute'));
        $mockIns->expects($this->exactly(3))
                ->method('bindValue')
                ->with($this->greaterThan(0), $this->anything());

        $mockIns->expects($this->once())
                ->method('execute')
                ->will($this->returnValue(true));

        $this->pdo = $this->getMock('Pdo', array('prepare', 'lastInsertId'), array('sqlite::memory:'));
        $this->pdo->expects($this->once())
                  ->method('prepare')
                  ->with($this->equalTo(SfCon\Task::SQL_INSERT))
                  ->will($this->returnValue($mockIns));

        $this->pdo->expects($this->once())
                  ->method('lastInsertId')
                  ->will($this->returnValue(1));

        $this->fixture = new SfCon\Task($this->pdo);
        $this->fixture->setTitle($title);
        $this->fixture->insert(); // Inserts must define ID into the object
        $this->assertEquals($expectId, $this->fixture->getId());
        
    }

    public function testSelectWithoutWhere()
    {
        $this->markTestIncomplete('Implementar método SfCon\Task::fetchAll()');
        $expectId   = 1;
        $expecTitle = 'Uha!';
        $stubTask = new SfCon\Task();
        $stubTask->setId($expectId)->setTitle($expecTitle);

        $mockSel = $this->getMock('PdoStatement', array('fetchAll'));
        $mockSel->expects($this->once())
                ->method('fetchAll')
                ->will($this->returnValue(array($stubTask)));

        $this->pdo = $this->getMock('Pdo', array('prepare'), array('sqlite::memory:'));
        $this->pdo->expects($this->once())
                  ->method('prepare')
                  ->with($this->equalTo(SfCon\Task::SQL_FETCHALL))
                  ->will($this->returnValue($mockSel));

        $this->pdo->expects($this->any())
                  ->method('prepare')
                  ->with($this->equalTo(SfCon\Task::SQL_FETCHALL))
                  ->will($this->returnValue($mockSel));

        $this->fixture = new SfCon\Task($this->pdo);
        $all = $this->fixture->fetchAll();
        $this->assertEquals(1, count($all));
        $one = array_shift($all);
        $this->assertContains($expectId, $one);
        $this->assertContains($expecTitle, $one);
    }
}