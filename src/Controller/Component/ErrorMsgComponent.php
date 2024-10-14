<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Collection\Collection;
use Cake\Validation\Validator;

/**
 * ErrorMsg component
 */
class ErrorMsgComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function msg($entidade)
    {
        $erros = $entidade->getErrors();                
        $errorCampos = $this->percorer($erros);        
                
        return $errorCampos;
    }

    private function percorer($erros)
    {
        
        $nomesErros = [];
        
        foreach ($erros as $tipo => $erro){            
            $error = $this->proximo($erro);        
            $campo = key($error);
            
            if($campo == 'RETORNO'){
               $textoErro = $tipo.' '.strtolower($error[$campo]);
            }else{
                $textoErro = key($error).' '.strtolower(array_values($error)[0]);
            }            
            
            $nomesErros[] = $textoErro;
        } 

        return  $nomesErros;
    }

    private function proximo($erro)
    {
        
        $valor = array_values($erro);        
        $valor =  $valor[0];
        if(is_array($valor)){
            
            $array = $this->proximo($valor);
            $key = key($array);
            
            if($key == 'RETORNO'){                
                return ['ADD_TIPO' => $array[$key]]; 
            }
            
            if($key == 'ADD_TIPO'){                           
                return [key($valor) => $array[$key]]; 
            }
                                    
            return $array;
        }
            
        return ['RETORNO' => $valor];    
    }

}
