<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SolicitacoesHorariosFixture
 *
 */
class SolicitacoesHorariosFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'solicitacao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'horario_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'solicitacoes_horarios__id1_idx' => ['type' => 'index', 'columns' => ['solicitacao_id'], 'length' => []],
            'solicitacoes_horarios__id2_idx' => ['type' => 'index', 'columns' => ['horario_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'solicitacoes_horarios__id1' => ['type' => 'foreign', 'columns' => ['solicitacao_id'], 'references' => ['solicitacoes', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'solicitacoes_horarios__id2' => ['type' => 'foreign', 'columns' => ['horario_id'], 'references' => ['horarios', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'solicitacao_id' => 1,
            'horario_id' => 1
        ],
    ];
}
