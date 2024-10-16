<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContatosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContatosTable Test Case
 */
class ContatosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ContatosTable
     */
    public $Contatos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.contatos',
        'app.pessoas',
        'app.unidades',
        'app.vw_assistidos',
        'app.vw_funcionarios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Contatos') ? [] : ['className' => ContatosTable::class];
        $this->Contatos = TableRegistry::get('Contatos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Contatos);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test defaultConnectionName method
     *
     * @return void
     */
    public function testDefaultConnectionName()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
