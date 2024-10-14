<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * FormatDateTime component
 */
class MascaraCPFSigadComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function aplicar($cpf)
    {

        if(strlen(trim($cpf)) != 11){
            throw new \Exception("Cpf Invalido", 1111313);
            return '';
        }

        $mascaraCpf = "%s%s%s.%s%s%s.%s%s%s-%s%s";
                                                                        
        $cpfComMascara = vsprintf($mascaraCpf, str_split($cpf));

        return $cpfComMascara;
    }

}
