<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ComarcasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ComarcasTable Test Case
 */
class ComarcasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ComarcasTable
     */
    public $Comarcas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        'app.unidades',
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
        $config = TableRegistry::exists('Comarcas') ? [] : ['className' => ComarcasTable::class];
        $this->Comarcas = TableRegistry::get('Comarcas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Comarcas);

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
     * Test defaultConnectionName method
     *
     * @return void
     */
    public function testDefaultConnectionName()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
