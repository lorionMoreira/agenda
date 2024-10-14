<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AgendasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AgendasTable Test Case
 */
class AgendasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AgendasTable
     */
    public $Agendas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.agendas',
        'app.escalas',
        'app.agendamentos',
        'app.assistidos',
        'app.pessoas',
        'app.contatos',
        'app.enderecos',
        'app.pessoa_fisicas',
        'app.tipo_acoes',
        'app.tipo_documentos',
        'app.tipo_acoes_tipo_documentos',
        'app.unidade_moveis',
        'app.unidade_moveis_tipo_acoes',
        'app.funcionarios',
        'app.especializadas',
        'app.acoes',
        'app.processos',
        'app.atuacaos',
        'app.tipo_agendamentos',
        'app.comarcas',
        'app.unidades',
        'app.salas',
        'app.conciliacoes',
        'app.fila_senhas',
        'app.historicos',
        'app.vw_agendamentos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Agendas') ? [] : ['className' => AgendasTable::class];
        $this->Agendas = TableRegistry::get('Agendas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Agendas);

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
