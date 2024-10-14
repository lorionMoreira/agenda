<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\FormatDataComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\FormatDataComponent Test Case
 */
class FormatDataComponentTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Controller\Component\FormatDataComponent     */
    public $FormatData;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();        $this->FormatData = new FormatDataComponent($registry);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FormatData);

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
