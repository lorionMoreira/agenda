<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EspecializadasFixture
 *
 */
class EspecializadasFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'parent_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nome' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'sigla' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'agd_servidor' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '0 =  É área de atuação do Defensor
1 = É área de atuação do servidor
2 = É área de atuação do Defensor e Servidor', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'FK_especializadas_especializadas' => ['type' => 'index', 'columns' => ['parent_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'FK_especializadas_especializadas' => ['type' => 'foreign', 'columns' => ['parent_id'], 'references' => ['especializadas', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
            'parent_id' => 1,
            'nome' => 'Lorem ipsum dolor sit amet',
            'sigla' => 'Lorem ip',
            'agd_servidor' => 1
        ],
    ];
}
