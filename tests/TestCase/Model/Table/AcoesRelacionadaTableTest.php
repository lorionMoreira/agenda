<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AcoesRelacionadaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AcoesRelacionadaTable Test Case
 */
class AcoesRelacionadaTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\AcoesRelacionadaTable     */
    public $AcoesRelacionada;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.acoes_relacionada',
        'app.area_atuacao',
        'app.documentos',
        'app.area_atuacao_documentos',
        'app.location',
        'app.area_atuacao_location',
        'app.duvida'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AcoesRelacionada') ? [] : ['className' => AcoesRelacionadaTable::class];        $this->AcoesRelacionada = TableRegistry::get('AcoesRelacionada', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AcoesRelacionada);

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
}
