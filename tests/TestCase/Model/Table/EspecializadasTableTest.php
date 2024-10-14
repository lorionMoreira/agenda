<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EspecializadasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EspecializadasTable Test Case
 */
class EspecializadasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\EspecializadasTable
     */
    public $Especializadas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.especializadas',
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
        'app.agendas',
        'app.escalas',
        'app.vw_agendamentos',
        'app.funcionarios',
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
        'app.agendamentos_temp',
        'app.atendimento_atividades',
        'app.audiencias',
        'app.escalas_funcionarios',
        'app.especializada_balanceamentos',
        'app.perfis',
        'app.periodos',
        'app.rc_especializadas_tipo_atividades',
        'app.rc_especializadas_tipo_atividades_copy',
        'app.relacionamentos',
        'app.atuacoes',
        'app.atuacoes_especializadas',
        'app.comarcas_especializadas',
        'app.especializadas_funcionarios',
        'app.tipo_atividades',
        'app.especializadas_tipo_atividades',
        'app.unidade_defensoriais',
        'app.especializadas_unidade_defensoriais'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Especializadas') ? [] : ['className' => EspecializadasTable::class];
        $this->Especializadas = TableRegistry::get('Especializadas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Especializadas);

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
