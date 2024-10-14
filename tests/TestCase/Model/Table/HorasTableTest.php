<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\HorasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\HorasTable Test Case
 */
class HorasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\HorasTable
     */
    public $Horas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.horas',
        'app.escalas',
        'app.dias',
        'app.agendamentos_temp',
        'app.agendas',
        'app.agendamentos',
        'app.especializadas',
        'app.atendimento_atividades',
        'app.audiencias',
        'app.escalas_funcionarios',
        'app.especializada_balanceamentos',
        'app.perfis',
        'app.periodos',
        'app.rc_especializadas_tipo_atividades',
        'app.rc_especializadas_tipo_atividades_copy',
        'app.relacionamentos',
        'app.vw_agendamentos',
        'app.atuacoes',
        'app.atuacoes_especializadas',
        'app.comarcas',
        'app.unidade_defensoriais',
        'app.comarcas_especializadas',
        'app.funcionarios',
        'app.especializadas_funcionarios',
        'app.tipo_atividades',
        'app.especializadas_tipo_atividades',
        'app.especializadas_unidade_defensoriais',
        'app.unidades',
        'app.contatos',
        'app.pessoas',
        'app.enderecos',
        'app.assistidos',
        'app.acoes',
        'app.assistencia_presos',
        'app.atendimentos',
        'app.civeis',
        'app.conciliacoes',
        'app.crimes',
        'app.curadorias',
        'app.direito_humanos',
        'app.documentos',
        'app.execucao_penais',
        'app.familias',
        'app.fila_senhas',
        'app.filas',
        'app.flagrantes',
        'app.fundiarios',
        'app.assistidos_fundiarios',
        'app.idosos',
        'app.instancia_superior_civeis',
        'app.juizado_criminais',
        'app.juizado_especial_civeis',
        'app.juventude_civeis',
        'app.juventudes',
        'app.nucleo_mulheres',
        'app.pa_acolhidos',
        'app.pa_acolhimentos',
        'app.pa_historicos',
        'app.populacao_ruas',
        'app.processo_administrativos',
        'app.processos',
        'app.saudes',
        'app.sociais',
        'app.unidade_moveis',
        'app.vw_atendimento',
        'app.vw_atendimentos',
        'app.vw_crc_atendimentos',
        'app.vw_senhas',
        'app.vwextrato',
        'app.dependentes',
        'app.assistidos_dependentes',
        'app.partes',
        'app.assistidos_partes',
        'app.plantao_atendimentos',
        'app.assistidos_plantao_atendimentos',
        'app.tipo_doencas',
        'app.assistidos_tipo_doencas',
        'app.pessoa_fisicas',
        'app.feriados',
        'app.fila_guiches',
        'app.fila_paineis',
        'app.salas',
        'app.vw_assistidos',
        'app.vw_fila_guiches',
        'app.vw_funcionarios',
        'app.estagiarios',
        'app.estagiarios_unidades',
        'app.plantoes',
        'app.escalas_atuacoes',
        'app.escalas_salas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Horas') ? [] : ['className' => HorasTable::class];
        $this->Horas = TableRegistry::get('Horas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Horas);

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
