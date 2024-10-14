<?php
namespace App\Test\TestCase\Controller\Component;

use App\Controller\Component\NumeroTriagemComponent;
use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\Component\NumeroTriagemComponent Test Case
 */
class NumeroTriagemComponentTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Controller\Component\NumeroTriagemComponent     */
    public $NumeroTriagem;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();        $this->NumeroTriagem = new NumeroTriagemComponent($registry);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NumeroTriagem);

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
