<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocumentosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocumentosTable Test Case
 */
class DocumentosTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\DocumentosTable     */
    public $Documentos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.documentos',
        'app.area_atuacao',
        'app.acoes_relacionada',
        'app.duvida',
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
        $config = TableRegistry::exists('Documentos') ? [] : ['className' => DocumentosTable::class];        $this->Documentos = TableRegistry::get('Documentos', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Documentos);

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
