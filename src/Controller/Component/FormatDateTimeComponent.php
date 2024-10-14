<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\I18n\Time;

/**
 * FormatDateTime component
 */
class FormatDateTimeComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function now()
    {
    	return Time::now();
    }

}
