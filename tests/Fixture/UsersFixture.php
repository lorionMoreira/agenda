<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'username' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'armscii8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 200, 'null' => false, 'default' => null, 'collate' => 'armscii8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'sigad_user' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'armscii8_general_ci'
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
            'username' => 'Lorem ipsum dolor sit amet',
            'password' => 'Lorem ipsum dolor sit amet',
            'sigad_user' => 1
        ],
    ];
}
