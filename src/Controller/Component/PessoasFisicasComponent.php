<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use App\Support\ResultadoPesquisa;
use Cake\Mailer\Email;

/**
 * PessoasFisicas component
 */
class PessoasFisicasComponent extends Component
{
    
    public function existente($cpf)
    {        
        $existeCpfComMascara = $this->pesquisa($cpf);
        
        if($existeCpfComMascara->obs == ResultadoPesquisa::DUPLICATA){

            $this->envioEmail('Assistido com cpf mais que duplicado - com mascara', $cpf);            
        }

        if($existeCpfComMascara->resultado){
            return $existeCpfComMascara;
        }

        $cpfSemMascara = str_replace(array('.','-'),'', $cpf);
        
        $existeCpfSemMascara = $this->pesquisa($cpfSemMascara);
        
        if($existeCpfSemMascara->obs == ResultadoPesquisa::DUPLICATA){
            $this->envioEmail('Assistido com cpf mais que duplicado - sem mascara', $cpf);
        }
        
        return $existeCpfSemMascara;
       
    }    

    private function envioEmail($assunto, $cpf)
    {
        
        $email = new Email();
        $email->template('cpf_duplicado');
        $email->emailFormat('html');
        $email->from('suporte@defensoria.ba.def.br');
        $email->to("daniele.souza@defensoria.ba.def.br");
        $email->subject($assunto);
        $email->viewVars(['cpf' => $cpf]);
        $email->send();        
    }

    private function pesquisa($cpf)
    {
        $pessoaFisicasTable = TableRegistry::get('PessoaFisicas');
                
        $pessoaFisicas = $pessoaFisicasTable->findAllByCpf($cpf)->all();
        
        if($pessoaFisicas->isEmpty()){            
            $p = new ResultadoPesquisa(false, $cpf);
             return $p;
        } 
        if($pessoaFisicas->count() == 1){
            $pessoaFisica = $pessoaFisicas->first();
            //dd($pessoaFisica);
            if(empty($pessoaFisica->pessoa->assistidos)){
                if(!empty($pessoaFisicas->pessoa)){
                    return new ResultadoPesquisa(true, $cpf, null, $pessoaFisicas->pessoa );    
                }else{
                    return new ResultadoPesquisa(true, $cpf, null);    
                }
            }
            //dd($pessoaFisicas);
            $pessoa = $pessoaFisica->pessoa;
            $assistido = $pessoaFisica->pessoa->assistidos[0];
            return new ResultadoPesquisa(true, $cpf, null, $pessoa, $assistido);            
        }
                
        if(strpos($cpf, '.') !== false || strpos($cpf, '-') !== false){
            //dd($pessoaFisicas);
            $pessoaFisica = $pessoaFisicas->last();
            //dd($pessoaFisica);
            if(empty($pessoaFisica->pessoa->assistidos)){

                return new ResultadoPesquisa(true, $cpf, ResultadoPesquisa::DUPLICATA, null,  $pessoaFisica->pessoa);

            }
            
            $pessoa = $pessoaFisica->pessoa;
            
            $assistido = $pessoaFisica->pessoa->assistidos[0];
            return new ResultadoPesquisa(true, $cpf, null, $pessoa, $assistido);
            
        }

        return new ResultadoPesquisa(false, $cpf, ResultadoPesquisa::DUPLICATA);
    }
}