<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FuncionariosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FuncionariosTable Test Case
 */
class FuncionariosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\FuncionariosTable
     */
    public $Funcionarios;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.funcionarios',
        'app.situacoes',
        'app.pessoas',
        'app.contatos',
        'app.enderecos',
        'app.assistidos',
        'app.agendamentos',
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
        'app.comarcas',
        'app.unidade_defensoriais',
        'app.comarcas_especializadas',
        'app.especializadas_funcionarios',
        'app.tipo_atividades',
        'app.especializadas_tipo_atividades',
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
        'app.pessoa_fisicas',
        'app.nivels',
        'app.acao_historicos',
        'app.afastamentos',
        'app.anexos',
        'app.atividade_extras',
        'app.atuacoes_designacoes',
        'app.civeis',
        'app.condicoes',
        'app.designacoes',
        'app.direito_humanos',
        'app.documentos',
        'app.estagiarios_funcionarios',
        'app.execucao_penais_processos',
        'app.familias',
        'app.fila_historicos',
        'app.filas',
        'app.flagrantes',
        'app.fundiarios',
        'app.idosos',
        'app.instancia_superior_civeis',
        'app.juizado_especial_civeis',
        'app.juventude_civeis',
        'app.medidas',
        'app.nucleo_mulheres',
        'app.pa_historicos',
        'app.populacao_ruas',
        'app.sociais',
        'app.titularidades',
        'app.trabalhos',
        'app.vw_afastamentos',
        'app.vw_designacoes',
        'app.unidade_moveis',
        'app.unidade_moveis_funcionarios'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Funcionarios') ? [] : ['className' => FuncionariosTable::class];
        $this->Funcionarios = TableRegistry::get('Funcionarios', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Funcionarios);

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
