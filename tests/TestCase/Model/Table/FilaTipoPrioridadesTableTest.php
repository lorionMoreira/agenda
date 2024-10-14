<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FilaTipoPrioridadesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FilaTipoPrioridadesTable Test Case
 */
class FilaTipoPrioridadesTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\FilaTipoPrioridadesTable     */
    public $FilaTipoPrioridades;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.fila_tipo_prioridades'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('FilaTipoPrioridades') ? [] : ['className' => FilaTipoPrioridadesTable::class];        $this->FilaTipoPrioridades = TableRegistry::get('FilaTipoPrioridades', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FilaTipoPrioridades);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
