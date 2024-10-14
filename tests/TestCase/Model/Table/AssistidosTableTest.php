<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AssistidosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AssistidosTable Test Case
 */
class AssistidosTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AssistidosTable
     */
    public $Assistidos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.assistidos',
        'app.pessoas',
        'app.contatos',
        'app.unidades',
        'app.vw_assistidos',
        'app.vw_funcionarios',
        'app.enderecos',
        'app.cidades',
        'app.plantao_atendimentos',
        'app.usuarios',
        'app.coletivos',
        'app.coletivos_pessoas',
        'app.dependentes',
        'app.familiares',
        'app.funcionarios',
        'app.partes',
        'app.pessoa_fisicas',
        'app.escolaridades',
        'app.racas',
        'app.profissoes',
        'app.religioes',
        'app.tipo_documentos',
        'app.rendas',
        'app.quantidade_filhos',
        'app.estado_civis',
        'app.tipo_residencias',
        'app.tipo_deficiencias',
        'app.situacoes',
        'app.pessoa_juridicas',
        'app.plantao_atendimentos_pessoas',
        'app.rc_trabalhos',
        'app.registros',
        'app.testemunhas',
        'app.trabalhos',
        'app.contas',
        'app.contas_pessoas',
        'app.acoes',
        'app.agendamentos',
        'app.assistencia_presos',
        'app.atendimentos',
        'app.audiencias',
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
        'app.relacionamentos',
        'app.saudes',
        'app.sociais',
        'app.unidade_moveis',
        'app.vw_atendimento',
        'app.vw_agendamentos',
        'app.vw_atendimentos',
        'app.vw_crc_atendimentos',
        'app.vw_senhas',
        'app.vwextrato',
        'app.assistidos_dependentes',
        'app.assistidos_partes',
        'app.assistidos_plantao_atendimentos',
        'app.tipo_doencas',
        'app.assistidos_tipo_doencas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Assistidos') ? [] : ['className' => AssistidosTable::class];
        $this->Assistidos = TableRegistry::get('Assistidos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Assistidos);

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
