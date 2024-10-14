<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EnderecosFixture
 *
 */
class EnderecosFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'numero' => ['type' => 'string', 'length' => 5, 'null' => true, 'default' => '0', 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'referencia' => ['type' => 'string', 'length' => 150, 'null' => true, 'default' => '', 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'logradouro_descricao' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'bairro_descricao' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'cidade_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cep' => ['type' => 'string', 'length' => 9, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'cidade_id' => ['type' => 'index', 'columns' => ['cidade_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'enderecos_ibfk_1' => ['type' => 'foreign', 'columns' => ['cidade_id'], 'references' => ['cidades', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
            'numero' => 'Lor',
            'referencia' => 'Lorem ipsum dolor sit amet',
            'logradouro_descricao' => 'Lorem ipsum dolor sit amet',
            'bairro_descricao' => 'Lorem ipsum dolor sit amet',
            'cidade_id' => 1,
            'cep' => 'Lorem i'
        ],
    ];
}
