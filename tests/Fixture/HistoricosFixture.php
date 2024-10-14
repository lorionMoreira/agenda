<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * HistoricosFixture
 *
 */
class HistoricosFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'agendamento_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'situacao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'data' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'observacao' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'funcionario_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'agendamento_id' => ['type' => 'index', 'columns' => ['agendamento_id'], 'length' => []],
            'situacao_id' => ['type' => 'index', 'columns' => ['situacao_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'historicos_ibfk_1' => ['type' => 'foreign', 'columns' => ['agendamento_id'], 'references' => ['agendamentos', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'historicos_ibfk_2' => ['type' => 'foreign', 'columns' => ['situacao_id'], 'references' => ['situacoes', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
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
            'agendamento_id' => 1,
            'situacao_id' => 1,
            'data' => '2017-12-04 09:37:47',
            'observacao' => 'Lorem ipsum dolor sit amet',
            'funcionario_id' => 1
        ],
    ];
}
