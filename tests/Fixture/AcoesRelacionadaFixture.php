<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AcoesRelacionadaFixture
 *
 */
class AcoesRelacionadaFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'acoes_relacionada';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'area_atuacao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'fk_area_atuacao_idx_acoes_relacionadas' => ['type' => 'index', 'columns' => ['area_atuacao_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_area_atuacao_acoes_relacionadas' => ['type' => 'foreign', 'columns' => ['area_atuacao_id'], 'references' => ['area_atuacao', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'area_atuacao_id' => 1
        ],
    ];
}
