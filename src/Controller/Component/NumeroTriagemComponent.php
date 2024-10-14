<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * NumeroTriagem component
 */
class NumeroTriagemComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function gerar($idPessoa)
    {
    	$assistidosTable = TableRegistry::get('Assistidos');
    	$assistido = $assistidosTable->newEntity();

    	return $assistido->montarTriagem($idPessoa);
    }
}
