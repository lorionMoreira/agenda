<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\FormatDateTimeComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\FormatDateTimeComponent Test Case
 */
class FormatDateTimeComponentTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Controller\Component\FormatDateTimeComponent     */
    public $FormatDateTime;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();        $this->FormatDateTime = new FormatDateTimeComponent($registry);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FormatDateTime);

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
