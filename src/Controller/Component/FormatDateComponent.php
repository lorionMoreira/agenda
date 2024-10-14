<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\I18n\Time;

/**
 * FormatData component
 */
class FormatDateComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function ptBrParaBd($data)
    {
    	$dataFormatada = null;
        if(!empty($data)){
            $dataFormatada = Time::createFromFormat('!d/m/Y', $data);
        }
        
        return $dataFormatada;
    }

}
