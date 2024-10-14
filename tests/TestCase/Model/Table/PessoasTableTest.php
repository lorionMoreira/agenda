<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PessoasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PessoasTable Test Case
 */
class PessoasTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PessoasTable
     */
    public $Pessoas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        'app.pessoa_fisicas',
        'app.pessoa_juridicas',
        'app.plantao_atendimentos_pessoas',
        'app.rc_trabalhos',
        'app.registros',
        'app.testemunhas',
        'app.trabalhos',
        'app.contas',
        'app.contas_pessoas'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Pessoas') ? [] : ['className' => PessoasTable::class];
        $this->Pessoas = TableRegistry::get('Pessoas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pessoas);

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
