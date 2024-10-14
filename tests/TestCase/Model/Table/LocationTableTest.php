<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LocationTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LocationTable Test Case
 */
class LocationTableTest extends TestCase
{

    /**
     * Test subject     *
     * @var \App\Model\Table\LocationTable     */
    public $Location;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.location',
        'app.area_atuacao',
        'app.acoes_relacionada',
        'app.duvida',
        'app.documentos',
        'app.area_atuacao_documentos',
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
        $config = TableRegistry::exists('Location') ? [] : ['className' => LocationTable::class];        $this->Location = TableRegistry::get('Location', $config);    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Location);

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
