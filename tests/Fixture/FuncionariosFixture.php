<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FuncionariosFixture
 *
 */
class FuncionariosFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'registro' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'data_inicio' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'data_fim' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'tipo_funcionario' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'situacao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pessoa_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'comarca_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nivel_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'unidade_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'situacao_id' => ['type' => 'index', 'columns' => ['situacao_id'], 'length' => []],
            'pessoa_id' => ['type' => 'index', 'columns' => ['pessoa_id'], 'length' => []],
            'comarca_id' => ['type' => 'index', 'columns' => ['comarca_id'], 'length' => []],
            'nivel_id' => ['type' => 'index', 'columns' => ['nivel_id'], 'length' => []],
            'unidade_id' => ['type' => 'index', 'columns' => ['unidade_id'], 'length' => []],
            'tipo_funcionario' => ['type' => 'index', 'columns' => ['tipo_funcionario'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'funcionarios_ibfk_1' => ['type' => 'foreign', 'columns' => ['situacao_id'], 'references' => ['situacoes', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'funcionarios_ibfk_2' => ['type' => 'foreign', 'columns' => ['pessoa_id'], 'references' => ['pessoas', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'funcionarios_ibfk_3' => ['type' => 'foreign', 'columns' => ['comarca_id'], 'references' => ['comarcas', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'funcionarios_ibfk_4' => ['type' => 'foreign', 'columns' => ['unidade_id'], 'references' => ['unidades', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'funcionarios_ibfk_5' => ['type' => 'foreign', 'columns' => ['tipo_funcionario'], 'references' => ['tipo_funcionarios', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
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
            'registro' => 1,
            'data_inicio' => '2017-12-19',
            'data_fim' => '2017-12-19',
            'tipo_funcionario' => 1,
            'situacao_id' => 1,
            'pessoa_id' => 1,
            'comarca_id' => 1,
            'nivel_id' => 1,
            'unidade_id' => 1
        ],
    ];
}
