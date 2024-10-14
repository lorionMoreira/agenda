<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;


/**
 * Historico component
 */
class HistoricoComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function registrarCancelamento($objeto)
    {
    	
        $historicoTable = TableRegistry::get('Historicos');
        $historico = $historicoTable->newEntity();
        $historico->agendamento_id = $objeto->agendamento->id;
        $historico->situacao_id = $objeto->cancelamento->id; //id para cancelado
        $historico->data = date('Y-m-d H:i:s');
        $historico->observacao = $objeto->obs;
        // "Cancelado pelo assistido - Agendamento Online App";

        return $historico;
    }
    
}
