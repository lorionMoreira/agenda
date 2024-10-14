<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PessoaFisicasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PessoaFisicasTable Test Case
 */
class PessoaFisicasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PessoaFisicasTable
     */
    public $PessoaFisicas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pessoa_fisicas',
        'app.escolaridades',
        'app.racas',
        'app.profissoes',
        'app.religioes',
        'app.pessoas',
        'app.contatos',
        'app.unidades',
        'app.vw_assistidos',
        'app.vw_funcionarios',
        'app.enderecos',
        'app.cidades',
        'app.plantao_atendimentos',
        'app.usuarios',
        'app.assistidos',
        'app.coletivos',
        'app.coletivos_pessoas',
        'app.dependentes',
        'app.familiares',
        'app.funcionarios',
        'app.partes',
        'app.pessoa_juridicas',
        'app.plantao_atendimentos_pessoas',
        'app.rc_trabalhos',
        'app.registros',
        'app.testemunhas',
        'app.trabalhos',
        'app.contas',
        'app.contas_pessoas',
        'app.tipo_documentos',
        'app.rendas',
        'app.quantidade_filhos',
        'app.estado_civis',
        'app.tipo_residencias',
        'app.tipo_deficiencias',
        'app.situacoes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PessoaFisicas') ? [] : ['className' => PessoaFisicasTable::class];
        $this->PessoaFisicas = TableRegistry::get('PessoaFisicas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PessoaFisicas);

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
