<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FilaPaineisTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FilaPaineisTable Test Case
 */
class FilaPaineisTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\FilaPaineisTable     */
    public $FilaPaineis;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.unidades',
        'app.comarcas',
        'app.agendamentos',
        'app.assistidos',
        'app.pessoas',
        'app.contatos',
        'app.enderecos',
        'app.cidades',
        'app.estados',
        'app.bairros',
        'app.vw_assistidos',
        'app.pessoa_fisicas',
        'app.historicos',
        'app.situacoes',
        'app.vw_historicos',
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
        'app.atendimentos',
        'app.crimes',
        'app.curadorias',
        'app.execucao_penais',
        'app.feriados',
        'app.fila_guiches',
        'app.fila_senhas',
        'app.fila_tipo_prioridades',
        'app.fila_categorias',
        'app.juizado_criminais',
        'app.juventudes',
        'app.vw_atendimento',
        'app.vw_atendimentos',
        'app.vw_crc_atendimentos',
        'app.vw_fila_guiches',
        'app.vw_funcionarios',
        'app.vw_senhas',
        'app.estagiarios',
        'app.estagiarios_unidades'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('FilaPaineis') ? [] : ['className' => FilaPaineisTable::class];        $this->FilaPaineis = TableRegistry::get('FilaPaineis', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FilaPaineis);

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
