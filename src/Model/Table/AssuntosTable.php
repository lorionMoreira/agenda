<?php
/**
 * Created by PhpStorm.
 * User: micaelomota
 * Date: 17/09/18
 * Time: 07:55
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class AssuntosTable extends Table
{

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName()
    {
        return 'sigad';
    }

}