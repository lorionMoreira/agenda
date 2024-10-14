<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * AppUsuario component
 */
class AppUsuarioComponent extends Component
{
    
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function dados($user)
    {
        
        $assistidosTable = TableRegistry::get('Assistidos');
        $pessoasTable = TableRegistry::get('Pessoas');
        $pessoaFisicasTable = TableRegistry::get('PessoaFisicas');
        $contatosTable = TableRegistry::get('Contatos');
        $enderecosTable = TableRegistry::get('Enderecos');
        $cidadesTable = TableRegistry::get('Cidades');
        $estadosTable = TableRegistry::get('Estados');
        
        if(is_object($user)){
            $user = json_decode(json_encode($user), true);
        }

        $response = [
                'username' => $user['username'],
                'password' => null,
                'email' => $user['email']
        ];

        if ($user['sigad_user'] != null) {
            
            $assistido = $assistidosTable->get($user['sigad_user']);
            $pessoa = $pessoasTable->get($assistido->pessoa_id);
            $pessoaFisica = $pessoaFisicasTable->findByPessoaId($pessoa->id)->first();
            $contato = $contatosTable->get($pessoa->contato_id);
            $endereco = $enderecosTable->get($pessoa->endereco_id);
            $cidade = $cidadesTable->get($endereco->cidade_id);
            $estado = $estadosTable->get($cidade->estado_id);

//                debug($endereco);
            $response = array_merge($response, [
                'nome' => $pessoa->nome,
                'nascimento' => $pessoaFisica->nascimento != null ? $pessoaFisica->nascimento->format("d/m/Y") : ' ',
                'sigadUser' => $user['sigad_user'],
                'mae' => $pessoaFisica->nome_mae,
                'pai' => $pessoaFisica->nome_pai,
                'sexo' => $pessoaFisica->sexo != null ? $pessoaFisica->sexo : -1,
                'nacionalidade' => $pessoaFisica->nacionalidade,
                'naturalidade' => $pessoaFisica->naturalidade,
                'estadoCivil' => $pessoaFisica->estado_civil_id,
                'celular' => $contato->celular,
                'cep' => $endereco->cep,
                'telFixo' => $contato->residencial,
                'whatsapp' => $contato->whatsapp,
                'uf' => $estado->sigla,
                'cidade' => $cidade->nome,
                'cidadeId' => $endereco->cidade_id,
                'bairro' => $endereco->bairro_descricao,
                'logradouro' => $endereco->logradouro_descricao,
                'numero' => $endereco->numero,
                'referencia' => $endereco->referencia,
                'triagem' => $assistido->numero_triagem
            ]);
            
            return $response;
        }
        
        return $response;
    }
}
