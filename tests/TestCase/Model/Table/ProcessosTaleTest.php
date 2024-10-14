<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProcessosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CidadesTable Test Case
 */
class ProcessosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProcessosTable
     */
    public $Processos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.cidades',
        'app.estados',
        'app.comarcas',
        'app.agendamentos',
        'app.assistidos',
        'app.pessoas',
        'app.contatos',
        'app.enderecos',
        'app.pessoa_fisicas',
        'app.historicos',
        'app.agendas',
        'app.escalas',
        'app.dias',
        'app.horas',
        'app.vw_agendamentos',
        'app.agendamentos_temp',
        'app.plantoes',
        'app.atuacoes',
        'app.escalas_atuacoes',
        'app.funcionarios',
        'app.escalas_funcionarios',
        'app.salas',
        'app.escalas_salas',
        'app.especializadas',
        'app.atendimento_atividades',
        'app.audiencias',
        'app.especializada_balanceamentos',
        'app.perfis',
        'app.periodos',
        'app.rc_especializadas_tipo_atividades',
        'app.rc_especializadas_tipo_atividades_copy',
        'app.relacionamentos',
        'app.atuacoes_especializadas',
        'app.comarcas_especializadas',
        'app.especializadas_funcionarios',
        'app.tipo_atividades',
        'app.especializadas_tipo_atividades',
        'app.unidade_defensoriais',
        'app.especializadas_unidade_defensoriais',
        'app.unidades',
        'app.atendimentos',
        'app.crimes',
        'app.curadorias',
        'app.execucao_penais',
        'app.feriados',
        'app.fila_guiches',
        'app.fila_paineis',
        'app.fila_senhas',
        'app.juizado_criminais',
        'app.juventudes',
        'app.vw_atendimento',
        'app.vw_assistidos',
        'app.vw_atendimentos',
        'app.vw_crc_atendimentos',
        'app.vw_fila_guiches',
        'app.vw_funcionarios',
        'app.vw_senhas',
        'app.estagiarios',
        'app.estagiarios_unidades',
        'app.bairros'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Processos') ? [] : ['className' => ProcessosTable::class];
        $this->Processos = TableRegistry::get('Processos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Processos);

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
