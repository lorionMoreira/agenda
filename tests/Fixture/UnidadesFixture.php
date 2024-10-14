<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UnidadesFixture
 *
 */
class UnidadesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'nome' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'comarca_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'endereco' => ['type' => 'string', 'length' => 300, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'situacao' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '1', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'contato_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'endereco_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'comarca_id' => ['type' => 'index', 'columns' => ['comarca_id'], 'length' => []],
            'fk_unidades_contatos1' => ['type' => 'index', 'columns' => ['contato_id'], 'length' => []],
            'fk_unidades_enderecos1' => ['type' => 'index', 'columns' => ['endereco_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_unidades_contatos1' => ['type' => 'foreign', 'columns' => ['contato_id'], 'references' => ['contatos', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'fk_unidades_enderecos1' => ['type' => 'foreign', 'columns' => ['endereco_id'], 'references' => ['enderecos', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'unidades_ibfk_1' => ['type' => 'foreign', 'columns' => ['comarca_id'], 'references' => ['comarcas', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
            'nome' => 'Lorem ipsum dolor sit amet',
            'comarca_id' => 1,
            'endereco' => 'Lorem ipsum dolor sit amet',
            'situacao' => 1,
            'contato_id' => 1,
            'endereco_id' => 1
        ],
    ];
}
