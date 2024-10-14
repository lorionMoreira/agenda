<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AreaAtuacaoTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AreaAtuacaoTable Test Case
 */
class AreaAtuacaoTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\AreaAtuacaoTable     */
    public $AreaAtuacao;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.area_atuacao',
        'app.acoes_relacionada',
        'app.documentos',
        'app.area_atuacao_documentos',
        'app.location',
        'app.area_atuacao_location'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AreaAtuacao') ? [] : ['className' => AreaAtuacaoTable::class];        $this->AreaAtuacao = TableRegistry::get('AreaAtuacao', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AreaAtuacao);

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
}
