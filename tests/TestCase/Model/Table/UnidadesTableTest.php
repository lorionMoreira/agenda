<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UnidadesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UnidadesTable Test Case
 */
class UnidadesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UnidadesTable
     */
    public $Unidades;

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
        'app.especializadas',
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
        'app.especializadas_unidade_defensoriais',
        'app.acoes',
        'app.processos',
        'app.atuacaos',
        'app.tipo_agendamentos',
        'app.salas',
        'app.conciliacoes',
        'app.fila_senhas',
        'app.historicos',
        'app.atendimentos',
        'app.cidades',
        'app.civeis',
        'app.crimes',
        'app.curadorias',
        'app.delegacias',
        'app.direito_humanos',
        'app.familias',
        'app.feriados',
        'app.filas',
        'app.flagrantes',
        'app.fundiarios',
        'app.idosos',
        'app.instancia_superior_civeis',
        'app.instituicoes',
        'app.juizado_criminais',
        'app.juizado_especial_civeis',
        'app.juventude_civeis',
        'app.juventudes',
        'app.nucleo_mulheres',
        'app.populacao_ruas',
        'app.rc_envios',
        'app.saudes',
        'app.vw_atendimento',
        'app.vw_assistidos',
        'app.vw_atendimentos',
        'app.vw_designacoes',
        'app.vw_funcionarios',
        'app.execucao_penais',
        'app.fila_guiches',
        'app.fila_paineis',
        'app.vw_crc_atendimentos',
        'app.vw_fila_guiches',
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
        $config = TableRegistry::exists('Unidades') ? [] : ['className' => UnidadesTable::class];
        $this->Unidades = TableRegistry::get('Unidades', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Unidades);

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
