<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\ErrorMsgComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\ErrorMsgComponent Test Case
 */
class ErrorMsgComponentTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Controller\Component\ErrorMsgComponent     */
    public $ErrorMsg;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();        $this->ErrorMsg = new ErrorMsgComponent($registry);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ErrorMsg);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
