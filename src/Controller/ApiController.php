<?php
namespace App\Controller;

use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Controller;
use Cake\Datasource\ConnectionManager;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Collection\Collection;
use App\Support\AreaAtuacaoRelacionamento;
use App\Model\Entity\Assistido;
use Cake\Routing\Router;
use Exception;
use App\Support\Objeto;
use Cake\I18n\Time;
use DateTimeImmutable;
use Cake\Core\Configure;
use Cake\Log\Log;

class ApiController extends Controller
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Auth', [
            'authorize' => 'Controller',
            'authenticate' => [
                'Basic' => [
                    'fields' => ['username' => 'username', 'password' => 'password'],
                    'userModel' => 'Users'
                ],
                'Form' => [
                    'fields' => ['username' => 'username', 'password' => 'password'],
                    'userModel' => 'Users'
                ],
            ]]);
    }

    public function beforeFilter(Event $event)
    {
        $this->viewBuilder()->className('Json');

        $this->Auth->allow(['login', 'cadastroBasico', 'recuperarSenha', 'areaAtuacao', 'perguntasFrequentes','listagemSolicitacoes',
            'locais', 'localPrincipal', 'recuperarSenha', 'verificacaoPje', 'verificacaoSolicitacaoPje', 'listaAssuntos', 'motivoContato',
            "tipoPartes", "comarca", "getComarca", "notificacao", "expedientespje", "termos", "atualizarTermos", "deleteAudio", "VerificaBloqueio", "expedientesretroativopje", "validarHorario"]);
    }

    public function isAuthorized($user)
    {
        // TODO: fazer isso elegante interceptando o handler de erro
        if ($this->Auth->identify() == null) {
            $this->response->type('json');
            $this->response->statusCode(403);
            $this->response->body(json_encode(
                array('success' => false, 'error_message' => 'Você não está autorizado a acessar esta api!')));
            $this->response->send();
            // foi a forma mais fácil de tratar a authenticação
            die();
        }

        return true;
    }


    public function login() {

        $user = $this->Auth->identify();
        

        if ($user != null) {

            $this->set('success', true);

            $this->loadComponent('AppUsuario');
            $response = $this->AppUsuario->dados($user);
            
            $this->set('data', $response);
        } else {
            $this->set('success', false);
            $this->set('error_message', "CPF ou senha inválidos!");
        }

        $this->set('_serialize', ['success', 'error_message', 'data']);
    }

    public function cadastroBasico()
    {
        $this->request->allowMethod(['post']);

        try{
            $data = $this->request->getData();
            $userTable = TableRegistry::get('Users');

            $user = $userTable->newEntity($data);
            $this->loadComponent('MascaraCPFSigad');
            $cpfComMascara = $this->MascaraCPFSigad->aplicar($user->username);
            $this->loadComponent('PessoasFisicas');
                
            $resultadoPesquisa = $this->PessoasFisicas->existente($cpfComMascara);

            if(!is_null($resultadoPesquisa->assistido)){
                $user->sigad_user = $resultadoPesquisa->assistido->id;
            }

            if(is_null($resultadoPesquisa->assistido) && !is_null($resultadoPesquisa->pessoa)){

                $assistidoTabela = TableRegistry::get('Assistidos');                    
                $assistidoModel = $assistidoTabela->newEntity();
                $assistidoModel->dados_completos = 1;
                $assistidoModel->dt_cadastro = date("Y-m-d H:i:s");
                $assistidoModel->pessoa_id = $resultadoPesquisa->pessoa->id;
                $assistidoModel->numero_triagem = $assistidoModel->montarTriagem($assistidoModel->pessoa_id);
                $assistidoTabela->save($assistidoModel);
                $user->sigad_user = $assistidoModel->id;
            }

            if($userTable->save($user)){
                if(!is_null($user->sigad_user)){

                    $this->loadComponent('AppUsuario');
                    $user = $this->AppUsuario->dados($user);       
                }

                $this->set('success', true);
                $this->set('data', $user);
            } else {
                $this->set('success', false);
                $this->loadComponent('ErrorMsg');
                $errorCampos = $this->ErrorMsg->msg($user);

                // $this->response->statusCode(400);
                $this->set('error_message', $errorCampos[0]);
            }
        }catch(\Exception $e){
            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('error_message', "Erro desconhecido");
        }
        $this->set('_serialize', ['success', 'error_message', 'data']);
    }

    function geradorSenhas()
    {
        $alfabeto = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789~!@#$%^&*()_+=";
        $senha = array();
        $comprimento_alfabeto = strlen($alfabeto) - 1;
        for ($i = 0; $i < 12; $i++) //$i é o tamanho da senha
        {
            $n = rand(0, $comprimento_alfabeto);
            $senha[] = $alfabeto[$n];
        }
        return implode($senha); //transformar o arry em string
    }

    public function agendamentoChatbotSemCadastroBlip()
    {
        /*
        Esta função cria um agendamento para um assistido que não tem nenhum cadastro no site da agenda


        
        ------------///-------- Pré-requisitos dos cadastros ------------///-------- 
        */
        // Cadastro Básico
        $this->request->allowMethod(['post']);
        $flag_mascara= false;
        $user_sigad= 123;


        // Dados do chatbot
        $success= true; //flag de erro
        $data = $this->request->getData(); //dados


        // Cadastro Completo
        $user = $this->Auth->identify(); //usado para o login do chatbot, porém não será usado para preencher os dados no cadastro completo
        $this->loadModel('Assistidos');
        $this->loadModel('Pessoas');
        $this->loadModel('PessoaFisicas');
        $this->loadModel('Contatos');
        $this->loadModel('Enderecos');
        $this->loadModel('Cidades');
        $this->loadModel('Estados');


        // Agendamento
        $this->loadModel('Solicitacoes');




        try         //######################################################### Tratando dados que vieram do chatbot
        {

            //tratando os dados
            $assistido_cidade= $data['cidade'];
            $assistido_relato= $data['relato'];
            $assistido_area_atendimento= $data['areaAtendimento'];
            $assistido_nome= $data['nome'];
            $assistido_cpf= $data['cpf']; //no chatbot o campo CPF tem regex, entao pode vir com ou sem mascara para comodidade do user
            if (strpos($assistido_cpf, '.') !== false) //testa se o CPF veio com máscara
                $assistido_cpf = preg_replace("/\D+/", "", $assistido_cpf); // remove qualquer caracter não numérico
            $assistido_email= $data['email'];
            $assistido_data_nascimento= $data['bday']; //vem no formato DD/MM/YYYY
            $assistido_data_nascimento= implode('-', array_reverse(explode('/', $assistido_data_nascimento))); //transforma em YYYY-MM-DD
            $assistido_mae_nome= $data['nomeMae'];
            $assistido_telefone= $data['telefone'];
            $assistido_turno_atendimento= $data['turnoAtendimento'];
            if (strpos($assistido_turno_atendimento, 'tarde') !== false) //trata a palavra "de tarde" para virar "tarde"
                $assistido_turno_atendimento= "tarde";
            else
                $assistido_turno_atendimento= "manha";

            $dados_tratados = [
                'nome_completo' => $assistido_nome,
                'cpf' => $assistido_cpf,
                'telefone' => $assistido_telefone,
                'email' => $assistido_email,
                'data_nasc' => $assistido_data_nascimento,
                'nome_mae_completo' => $assistido_mae_nome,
                'turno_atendimento' => $assistido_turno_atendimento,
            ];
        }
        catch (Exception $e)
        {
            $success = false;
            $error2= "erro ao tratar dados vindo do chatbot";             
            $this->set('_serialize', ['success', 'error_message']);
            return;
        }

        $userTable = TableRegistry::get('Users');

        /*if (strlen($dados_tratados['cpf']) != 11)
        {
            $usuario= $userTable->newEntity($dados_tratados);
            $subject= 'Instrução para Cadastro - Defensoria da Bahia';
            $template= 'chatbot_erro_dados_cpf';
            $this->enviarEmailErro($template, $usuario, $subject);
            $this->set('fulfillmentText', "Este CPF é inválido");
            $this->set('_serialize', ['fulfillmentText']);
            die();
        }*/

        if($success) //true == tratou os dados do chatbot corretamente
        {
            try     //######################################################### Realizando cadastro básico
            {
                //$userTable = TableRegistry::get('Users');
                
                $senha_temporaria= $this->geradorSenhas();
                $dados_cadastro_basico=
                [
                    'Users' =>[
                        'username' => $assistido_cpf,
                        'email' => $assistido_email,
                        'password' => $senha_temporaria,
                        'password2' => $senha_temporaria
                    ]
                ];

                $user = $userTable->newEntity($dados_cadastro_basico);

                $this->loadComponent('MascaraCPFSigad');
                $cpfComMascara = $this->MascaraCPFSigad->aplicar($user->username);
                $this->loadComponent('PessoasFisicas');
                $resultadoPesquisa = $this->PessoasFisicas->existente($cpfComMascara);
                

                if(!is_null($resultadoPesquisa->assistido))
                {
                    $user->sigad_user = $resultadoPesquisa->assistido->id;

                    $flag_mascara= true;
                    $user_sigad= $user->sigad_user;
                }

                if(is_null($resultadoPesquisa->assistido) && !is_null($resultadoPesquisa->pessoa)){
                    
                    $assistidoTabela = TableRegistry::get('Assistidos');                    
                    $assistidoModel = $assistidoTabela->newEntity();
                    $assistidoModel->dados_completos = 1;
                    $assistidoModel->dt_cadastro = date("Y-m-d H:i:s");
                    $assistidoModel->pessoa_id = $resultadoPesquisa->pessoa->id;
                    $assistidoModel->numero_triagem = $assistidoModel->montarTriagem($assistidoModel->pessoa_id);
                    $assistidoTabela->save($assistidoModel);
                    $user->sigad_user = $assistidoModel->id;
                }


                if ($userTable->save($user))
                {
                    //$this->set('success', true);
                    //$this->set('data', $user);
                }
                else //construção do email de erro
                {
                    $template= 'chatbot_erro_solicitacao';
                    $subject= 'Instrução para Solicitação de Agendamento - Defensoria da Bahia';
                    $this->enviarEmailErro($template, $user, $subject);
                    $success= false;
                    $this->set('fulfillmentText', "Este CPF já está cadastrado, favor acessar o site: https://agenda.defensoria.ba.def.br/");
                    $this->set('_serialize', ['fulfillmentText']);
                    //die();
                }
            }
            catch (Exception $e)
            {
                $success= false;
                $error3= "erro ao criar cadastro basico"; 
                $this->response->statusCode(400);
                $this->set('success', false);
                $this->set('error_message', "Erro desconhecido");
                //$this->set('error_message', $e->getMessage());
            }
            //$this->set('_serialize', ['success', 'error_message', 'data']); //resposta do cadastro basico
        }
        else
        {
            $resposta= [
                'fulfillmentText' => "Ocorreu um erro, parece que você já tem cadastro conosco", //resposta que o chatbot irá exibir
            ];
            $this->set('data', $resposta);
        }
        
        //user2 é o usuário que está sendo cadastrado
        $user2= [
                    'username' => $assistido_cpf,
                    'password' => $senha_temporaria
                ];
        if ($flag_mascara)//sigad_user que será inserido no cadastro completo SE o assistido ja tiver cadastro no sigad
        {
            $user2['sigad_user']= $user_sigad;
        }
        

        if ($success) //true == executou o cadastro basico normalmente
        {
            try     //######################################################### Realizando cadastro completo
            {
                $dados_cadastro_completo = [
                    'endereco' => [
                        'cidade_id' => "988" // 988 == Salvador
                    ],
                    'contato' => [
                        'email' => $assistido_email,
                        'celular' => $assistido_telefone
                    ],
                    'Pessoas' => [
                        'nome' => $assistido_nome,
                        'tipo' => 'F'
                    ],
                    'pessoa_fisicas' => [
                        [
                            'nome_mae' => $assistido_mae_nome,
                            'nascimento' => $assistido_data_nascimento,
                            'cpf' => $cpfComMascara //o CPF na tabela PF tem que ter máscara
                        ]
                    ]
                ];

                $this->loadModel('Pessoas');
                $pessoa = $this->Pessoas->newEntity($dados_cadastro_completo, [
                    'associated' => ['PessoaFisicas', 'Enderecos', 'Contatos', 'Assistidos']
                ]);

                $this->Pessoas->getConnection()->transactional(function() use($pessoa, $user2, $dados_cadastro_completo, $flag_mascara) {
                    //$this->set('success', true);

                    if(!$this->Pessoas->save($pessoa)){
                        $this->loadComponent('ErrorMsg');
                        $error = $this->ErrorMsg->msg($pessoa);

                        throw new \Exception($error[0], 2222);
                    }

                    $this->loadModel('Assistidos');
                    $this->loadComponent('FormatDateTime');
                    $dataNow = $this->FormatDateTime->now();
                    $this->loadComponent('NumeroTriagem');
                    $numeroTriagem = $this->NumeroTriagem->gerar($pessoa->id);
                    $dados_cadastro_completo = [
                        'numero_triagem' => $numeroTriagem,
                        'dados_completos' => Assistido::DADOS_COMPLETOS,
                        'dt_cadastro' => $dataNow,
                        'pessoa_id' => $pessoa->id
                    ];
                    

                    $assistido = $this->Assistidos->newEntity($dados_cadastro_completo);

                    if($this->Assistidos->save($assistido)) {
                        $id = $assistido->id;
                        $tuser = TableRegistry::get('Users');
                        $query = $tuser->findByUsername($user2['username']);
                        foreach ($query as $usuario)
                        {
                            $usuario_agenda= $usuario;
                        }
                        $usuario_agenda->sigad_user = $id;
                        if ($flag_mascara)
                        {
                            $usuario_agenda->sigad_user= $user2['sigad_user'];
                        }
                        
                        $tuser->save($usuario_agenda);

                        /*if (!$tuser->save($usuario_agenda)) {
                            debug($tuser->getErros());
                            die();
                        }*/
                    } else {
                        $this->loadComponent('ErrorMsg');
                        $error = $this->ErrorMsg->msg($assistido);
                        throw new \Exception($error[0], 1);
                    }
                });
            }
            catch (Excpetion $e)
            {
                $success= false;

                $this->Pessoas->getConnection()->rollback();
                    $this->set('success', false);
                    $this->set('error_message', $e->getMessage());
                    $this->set('_serialize', ['success', 'error_message']);
                    $this->response->statusCode(400);
                    return;
            }
        }
        else
        {
            $resposta= [
                'fulfillmentText' => "Ocorreu um erro, parece que você já tem cadastro conosco", //resposta que o chatbot irá exibir
            ];
            $this->set('data', $resposta);
        }


        if($success) //true == executou o cadastro completo normalmente
        {           //######################################################### Realizando o agendamento
            try 
            {
                if ($assistido_turno_atendimento == "tarde")
                {
                    $preferencia= [
                        "2"=> 2,
                        "4"=> 4,
                        "6"=> 6,
                        "8"=> 8,
                        "10"=> 10
                    ];
                }
                else //manhã
                {
                    $preferencia= [
                        "1"=> 1,
                        "3"=> 3,
                        "5"=> 5,
                        "7"=> 7,
                        "9"=> 9
                    ];
                }

                //pegando user na tabela para pegar o campo 'sigad_user'
                $tabela_user = TableRegistry::get('Users');
                $query = $tabela_user->findByUsername($user2['username']);
                foreach ($query as $usuario)
                {
                    $usuario_agenda= $usuario;
                }

                //montando dados
                $dados_agendamento = [
                    'assunto_id' => "28", //esse número pode ser diferente no ambiente de producao
                    'comarca' => "256", //esse também
                    'processo' => "0",
                    'sigad_user' => $usuario_agenda['sigad_user'],
                    //'relato' => 'Esta solicitação foi feita através do chatbot.'
                    'relato' => 'Esta solicitação foi feita através do chatbot, relato do assistido: ' . $assistido_relato
                ];
                
                
                switch($assistido_area_atendimento)
                {
                    case "civel_fazenda_publica":
                        $dados_agendamento['assunto_id']= 29; //ATENCAO>>> esses numeros podem ser diferentes no banco
                        break;
                    case "crime":
                        $dados_agendamento['assunto_id']= 30;
                        break;
                    case "direitos_crianca_adolescente":
                        $dados_agendamento['assunto_id']= 31;
                        break;
                    case "direitos_humanos":
                        $dados_agendamento['assunto_id']= 32;
                        break;
                    case "direitos_humanos_POP_RUA":
                        $dados_agendamento['assunto_id']= 33;
                        break;
                    case "familia":
                        $dados_agendamento['assunto_id']= 34;
                        break;
                    case "idoso":
                        $dados_agendamento['assunto_id']= 35;
                        break;
                    default:
                        $dados_agendamento['assunto_id']= 28;
                }

                //tratando o turno do agendamento
                foreach ($preferencia as $preferencias) 
                { 
                    $dados_agendamento['horarios'][]['id'] = $preferencias;
                }

                //criando obj
                $solicitacao = $this->Solicitacoes->newEntity($dados_agendamento, 
                        ['associated' => ['Horarios']]
                    );
                $solicitacao->status=1;
                
                if(!$this->Solicitacoes->save($solicitacao)){
                    $this->loadComponent('ErrorMsg');
                    $errorCampos = $this->ErrorMsg->msg($solicitacao);
                    throw new \Exception($errorCampos[0], 100000);
                }

                $senha= $user2['password'];

                $fulfillmentText2= "Pronto! Passei sua solicitação para o setor responsável e alguém vai te retornar sobre o agendamento no email que você me informou ou pelo site de Agendamentos. Você pode acompanhar tudo por aqui: https://agenda.defensoria.ba.def.br/ e sua senha é $senha"; //resposta que o chatbot irá exibir
                $this->viewBuilder()->className('Json');
                $this->set('fulfillmentText', $fulfillmentText2);
                $this->set('_serialize', ['fulfillmentText']);

                $this->loadModel('Users');
                $user = $this->Users->findUsuario($usuario_agenda['username'], $usuario_agenda['email']);
                $this->enviarEmailSolicitacao($user, $senha);
            }
            catch (Exception $e)
            {
                $success= false;
                $this->set('error_message', "Erro ao agendar");
                $this->set('error_message', $e->getMessage());
            }
        }
    }

    private function enviarEmailErro($template, $user, $subject)
    {
        $email = new Email();
        $email->profile('chatbot'); //carrega as configs do perfil de email 'chatbot'  em 'config/app.php'
        $email->template($template);
        $email->emailFormat('html');
        $email->from('suporte@defensoria.ba.def.br');
        $email->to($user->email);
        $email->subject($subject);
        $email->send();
    }

    private function enviarEmailSolicitacao($user, $senha)
    {
        $email = new Email();
        $email->profile('chatbot'); //carrega as configs do perfil de email 'chatbot'  em 'config/app.php'
        $email->template('chatbot_solicitacao_agendamento');
        $email->emailFormat('html');
        $email->from('chat-noreply@defensoria.ba.def.br');
        $email->to($user->email);
        $email->subject('Confirmação de Solicitação de Agendamento - Defensoria da Bahia');
        $email->viewVars(['senha' => $senha]); //coloca a senha gerada p/ o user no email
        $email->send();
    }



    public function cadastroCompleto()
    {
        $user = $this->Auth->identify();
        
        $this->loadModel('Assistidos');
        $this->loadModel('Pessoas');
        $this->loadModel('PessoaFisicas');
        $this->loadModel('Contatos');
        $this->loadModel('Enderecos');
        $this->loadModel('Cidades');
        $this->loadModel('Estados');

        $data = $this->request->getData();
        
        $dados = [
            'endereco' => [
                'bairro_descricao' => $data['bairro'],
                'logradouro_descricao' => $data['logradouro'],
                'cep' => $data['cep'],
                'numero' => $data['numero'],
                'referencia' => $data['referencia'],
                'cidade_id' => $data['cidadeId']
            ],
            'contato' => [
                'email' => $data['email'],
                'celular' => $data['celular'],
                'whatsapp' => $data['whatsapp'],
                'residencial' => $data['telFixo']
            ],
            'Pessoas' => [
                'nome' => $data['nome'],
                'tipo' => 'F'
            ],
            'pessoa_fisicas' => [
                [
                    'nome_pai' => $data['pai'],
                    'nome_mae' => $data['mae'],
                    'estado_civil_id' => $data['estadoCivil'],
                    'nascimento' => $data['nascimento'],
                    'sexo' => $data['sexo'],
                    'nacionalidade' => $data['nacionalidade'],
                    'naturalidade' => $data['naturalidade'],
                    'cpf' => $data['username']
                ]
            ]
        ];

        if($dados['pessoa_fisicas'][0]['nascimento'] != null){
            $this->loadComponent('FormatDate');
            $dataBd = $this->FormatDate->ptBrParaBd($dados['pessoa_fisicas'][0]['nascimento']);
            $dados['pessoa_fisicas'][0]['nascimento'] = $dataBd;
        }else{
            $dados['pessoa_fisicas'][0]['nascimento'] = '';
        }

        $this->loadComponent('MascaraCPFSigad');
        $cpfComMascara = $this->MascaraCPFSigad->aplicar($user['username']);
        $this->loadComponent('PessoasFisicas');
        $resultadoPesquisa = $this->PessoasFisicas->existente($cpfComMascara);
        
        if(is_null($user['sigad_user']) && $resultadoPesquisa->resultado == true){

            $tuser = TableRegistry::get('Users');

            $theUser = $tuser->get($user['id']);
            $idAssistido = null;
            if(!is_null($resultadoPesquisa->assistido)){
                $theUser->sigad_user = $resultadoPesquisa->assistido->id;
            }


            if(is_null($resultadoPesquisa->assistido)){

                $assistidoTabela = TableRegistry::get('Assistidos');                    
                $assistidoModel = $assistidoTabela->newEntity();
                $assistidoModel->dados_completos = 1;
                $assistidoModel->dt_cadastro = date("Y-m-d H:i:s");
                $assistidoModel->pessoa_id = $resultadoPesquisa->pessoa->id;
                $assistidoModel->numero_triagem = $assistidoModel->montarTriagem($assistidoModel->pessoa_id);
                $assistidoTabela->save($assistidoModel);
                $theUser->sigad_user = $assistidoModel->id;
            }
            
            $tuser->save($theUser);
            $user['sigad_user'] = $theUser->sigad_user;
            $data['sigadUser'] = $theUser->sigad_user;
        }


        if ($user['sigad_user'] != null) { // UPDATE
            $data['sigadUser'] = $user['sigad_user'];
            $assistido = $this->Assistidos->get($user['sigad_user']);
            
            $pessoa = $this->Pessoas->get($assistido->pessoa_id);
            $pessoaFisica = $this->PessoaFisicas->findByPessoaId($pessoa->id)->first();
            $contato = $this->Contatos->get($pessoa->contato_id);
            $endereco = $this->Enderecos->get($pessoa->endereco_id);

            $pessoa = $this->Pessoas->patchEntity($pessoa, $dados['Pessoas']);
            $pessoaFisica = $this->PessoaFisicas->patchEntity($pessoaFisica, $dados['pessoa_fisicas'][0]);

            $this->loadComponent('MascaraCPFSigad');
            $pessoaFisica->cpf = $this->MascaraCPFSigad->aplicar($pessoaFisica->cpf);
            
            $contato = $this->Contatos->patchEntity($contato, $dados['contato']);
            $endereco = $this->Enderecos->patchEntity($endereco, $dados['endereco']);
            
            /*
            $user['email'] = $contato->email;
            $this->loadModel('Users');
            $this->Users->save($user);
            */
            
            $success = true;
            
            if (!$this->Pessoas->save($pessoa) || !$this->PessoaFisicas->save($pessoaFisica) ||
                !$this->Contatos->save($contato) || !$this->Enderecos->save($endereco) ) {
                $success = false;               
            }
            
            $data['triagem'] = $assistido->numero_triagem;

            $this->set('success', $success);
            $this->set('data', $data);
            $this->set('_serialize', ['success', 'data']);
            
        } else { // CREATE
            $this->loadModel('Pessoas');
            $this->loadComponent('MascaraCPFSigad');
            $dados['pessoa_fisicas'][0]['cpf'] = $this->MascaraCPFSigad->aplicar($dados['pessoa_fisicas'][0]['cpf']);

            $pessoa = $this->Pessoas->newEntity($dados, [
                'associated' => ['PessoaFisicas', 'Enderecos', 'Contatos', 'Assistidos']
            ]);

            $this->set('_serialize', ['success', 'data']);

            try {
                $this->Pessoas->getConnection()->transactional(function() use($pessoa, $user, $data) {
                    $this->set('success', true);
                    $this->set('data', $data);

                    if(!$this->Pessoas->save($pessoa)){
                        $this->loadComponent('ErrorMsg');
                        $error = $this->ErrorMsg->msg($pessoa);

                        throw new \Exception($error[0], 2222);
                    }

                    $this->loadModel('Assistidos');
                    $this->loadComponent('FormatDateTime');
                    $dataNow = $this->FormatDateTime->now();
                    $this->loadComponent('NumeroTriagem');
                    $numeroTriagem = $this->NumeroTriagem->gerar($pessoa->id);
                    $dados = [
                        'numero_triagem' => $numeroTriagem,
                        'dados_completos' => Assistido::DADOS_COMPLETOS,
                        'dt_cadastro' => $dataNow,
                        'pessoa_id' => $pessoa->id,
                    ];

                    $assistido = $this->Assistidos->newEntity($dados);

                    if($this->Assistidos->save($assistido)) {
                        $id = $assistido->id;
                        $data['triagem'] = $assistido->numero_triagem;
                        $tuser = TableRegistry::get('Users');
                        $theUser = $tuser->get($user['id']);
                        $theUser->sigad_user = $id;

                        if (!$tuser->save($theUser)) {
                            debug($tuser->getErros());
                            die();
                        }
                    } else {
                        $this->loadComponent('ErrorMsg');
                        $error = $this->ErrorMsg->msg($assistido);
                        throw new \Exception($error[0], 1);
                    }
                });
            } catch (\Exception $e) {
                $this->Pessoas->getConnection()->rollback();
                $this->set('success', false);
                $this->set('error_message', $e->getMessage());
                $this->set('_serialize', ['success', 'error_message']);
                $this->response->statusCode(400);
                return;
            }
        }

    }

    public function listaAssuntos()
    {
        try {
            $this->viewBuilder()->className('Json');
            $assuntos = TableRegistry::get("Assuntos");
            $dados = $assuntos->find("all", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'nome','conditions' => ['chat_bot' => 0, 'estado' => 1]])->toArray();

            if ($dados) {
                $this->set('success', true);
            } else {
                $this->set('success', false);
                $this->set('msg_error', 'Nenhum registro encontrado.');
            }

        } catch (Exception $e) {
            $this->set('success', false);
            $this->set('error_message', $e->getMessage());
        }
        $this->set('data', $dados);
        $this->set('_serialize', ['success', 'error_message', 'data']);
    }

    public function listaEstados()
    {
        $dados = [];
        try {
            $this->viewBuilder()->className('Json');
            $assuntos = TableRegistry::get("Estados");
            $dados = $assuntos->find()->select(['id', 'nome', 'sigla'])->order(['nome ASC'])->all()->toArray();

            if ($dados) {
                $this->set('success', true);
            } else {
                $this->set('success', false);
                $this->set('msg_error', 'Nenhum registro encontrado.');
            }

        } catch (Exception $e) {
            $this->set('success', false);
            $this->set('error_message', $e->getMessage());
        }
        $this->set('data', $dados);
        $this->set('_serialize', ['success', 'error_message', 'data']);

    }

    public function listaCidades($estado = null)
    {

        $dados = [];
        try {
            $this->viewBuilder()->className('Json');
            $assuntos = TableRegistry::get("Cidades");

            if ($estado != null) {
                $dados = $assuntos->find()->select(['id', 'nome'])->where(['estado_id'=>$estado])->order(['nome ASC'])->all()->toArray();
            } else {
                $dados = $assuntos->find()->select(['id', 'nome'])->order(['nome ASC'])->all()->toArray();
            }

            if ($dados) {
                $this->set('success', true);
            } else {
                $this->set('success', false);
                $this->set('msg_error', 'Nenhum registro encontrado.');
            }

        } catch (Exception $e) {
            $this->set('success', false);
            $this->set('error_message', $e->getMessage());
        }
        $this->set('data', $dados);
        $this->set('_serialize', ['success', 'error_message', 'data']);

    }

    public function areaAtuacao()
    {
        $this->viewBuilder()->className('Json');
        
        try {
            $areaAtuacaoTable = TableRegistry::get("AreaAtuacao");
            $acoesRelacionadas = TableRegistry::get("AcoesRelacionada");
            $documentos = TableRegistry::get("Documentos");

            $areasAtuacao = $areaAtuacaoTable->find()
                ->select(["id", "ordem", "titulo", "descricao", "cor"])
                ->all();

            foreach ($areasAtuacao as $areaAtuacao) {
                $arList = $acoesRelacionadas->find()->select("nome")
                    ->where(['area_atuacao_id'=>$areaAtuacao->id])->all();
                $ar = [];
                foreach ($arList as $a) {
                    $ar[] = $a->nome;
                }
                $areaAtuacao['acoesRelacionadas'] = $ar;

                $docs = $documentos->find()->where(['area_atuacao_id'=>$areaAtuacao->id])->select("nome")->all();
                $dr = [];

                foreach ($docs as $d) {
                    $dr[] = $d->nome;
                }

                $areaAtuacao['documentosNecessarios'] = $dr;
            }
            
            $this->set('success', true);
            $this->set('data', $areasAtuacao);
        
        } catch (Exception $e) {
            $this->set('success', false);
            $this->set('error_message', $e->getMessage());
        }
                        
        $this->set('_serialize', ['success', 'error_message', 'data']);
    }

    public function perguntasFrequentes($idAreaAtuacao = null) {
        // TODO: treat possible errors
        $duvidas = TableRegistry::get("Duvida");

        $q = $duvidas->find()->select(['id', 'pergunta', 'resposta']);

        if ($idAreaAtuacao != null) {
            $q = $q->where(['area_atuacao_id'=>$idAreaAtuacao]);
        }

        $data = $q->all();
        $this->set('success', true);
        $this->set('data', $data);

        $this->set('_serialize',  ['success', 'error_message', 'data']);
    }
   
    public function solicitarAgendamento()
    {
        $user = $this->Auth->identify();

        //Verifica se está no periodo de bloqueio
        $pos_expediente_bloqueio = '';        
        $agora = date('d/m/Y');   
        $exibeaviso = false;


        $exibeaviso = $this->VerificaBloqueio();
        //$validarHorario = $this->validarHorario(); 


        if ($this->request->is('post')){
            
            try {
                
                $data['assunto_id'] = $this->request->getData('assunto_id');
                if($this->request->getData('assunto_id') == '-1'){
                    $data['assunto_id'] = '13';
                }

                if($this->request->getData('comarca') == '0'){
                    $data['comarca'] = '256';
                }else{
                    $data['comarca'] = $this->request->getData('comarca');
                }

                $prefencias = $this->request->getData('preferencia');                
                
                foreach( $prefencias as $prefencia){ 
                    $data['horarios'][]['id'] = $prefencia;
                }
                $data['processo'] = $this->request->getData('processo');
                $data['numero_processo'] = $this->request->getData('numero_processo');
                $this->loadComponent('FormatDateTime');
                $data['data_cadastro'] = $this->FormatDateTime->now();
                $data['sigad_user'] = $user['sigad_user'];
                
                $data['relato'] = $this->request->getData('relato');
                $this->loadModel('Solicitacoes');
                
                if($this->Solicitacoes->existsAgendamento($data['data_cadastro']->i18nFormat('yyyy-MM-dd'), $data['sigad_user'], $data['assunto_id'])){

                    throw new \Exception('Uma solicitação com este mesmo assunto já foi realizada. Em breve entraremos em contato. Fique atento ao seu email. Você também pode ficar sabendo a data do seu agendamento no site! Consulte o site do Agendamento on line da DPE com frequência para saber o dia de comparecer na Defensoria Pública!', 1205658);  
                }
                
                $solicitacao = $this->Solicitacoes->newEntity($data, 
                        ['associated' => ['Horarios']
                ]);

                $solicitacao->status = 1; // aberta
                $solicitacao->origem_solicitacao = "Aplicativo";
                $clicou = empty($this->request->getData("clicou_ia")) ? null : $this->request->getData("clicou_ia");
                $solicitacao->clicou_ia = $clicou;
                $predicao = empty($this->request->getData("valor_predicao")) ? 0 : $this->request->getData("valor_predicao");
                $solicitacao->valor_predicao = $predicao;
                $solicitacao->sub_assunto_id = $this->request->getData('sub_assunto_id');

                //Verifica se está no recesso de fim de ano antes de inserir
                
                if($exibeaviso == false){
                    if(!$this->Solicitacoes->save($solicitacao)){
                        $this->loadComponent('ErrorMsg');
                        $errorCampos = $this->ErrorMsg->msg($solicitacao);
                        throw new \Exception($errorCampos[0], 100000);
                    }
                    //
                    if($solicitacao->processo == 1){
                        $this->loadModel('SolicitacaoProcesso');
                        $solic_proc = $this->SolicitacaoProcesso->newEntity();
                        $solic_proc->solicitacao_id = $solicitacao->id;
                        $solic_proc->processo_motivo_contato_id = $this->request->getData('processo_motivo_contato_id');
                        $solic_proc->processo_tipo_parte_id = $this->request->getData('processo_tipo_parte_id');
                        if($solic_proc->processo_tipo_parte_id==3){
                            $solic_proc->nome_parte_representada = $this->request->getData('nome_parte_representada'); 
                        }
                        else if($solic_proc->processo_tipo_parte_id==4){
                            $solic_proc->nome_preso =  $this->request->getData('nome_preso');
                            $solic_proc->rg_preso = $this->request->getData('rg_preso');
                        }
                        $this->SolicitacaoProcesso->save($solic_proc);    
                        }else{
                        if($solicitacao->assunto_id == 36){
                            if($solicitacao->sub_assunto_id == 21){
                                $this->loadModel('SolicitacaoHerancas');
                                $s_her = $this->SolicitacaoHerancas->newEntity();
                                $s_her->solicitacao_id = $solicitacao->id;
                                $s_her->sub_assunto_id = $solicitacao->sub_assunto_id;
                                if($this->request->getData('heranca') == 1){
                                    $s_her->deixou_bens = 1;
                                    $s_her->deixou_valores = 0;
                                    $s_her->sem_bens = 0;
                                }
                                else if($this->request->getData('heranca') == 2){
                                    $s_her->deixou_bens = 0;
                                    $s_her->deixou_valores = 1;
                                    $s_her->sem_bens = 0;
                                }else{
                                    $s_her->deixou_bens = 0;
                                    $s_her->deixou_valores = 0;
                                    $s_her->sem_bens = 1;
                                }
                            
                                $s_her->data_obito = Time::createFromFormat('d/m/Y', $this->request->getData('data_obito'));
                                $s_her->filho_menor = $this->request->getData('f_menor');
                                $this->SolicitacaoHerancas->save($s_her);
                            }    
                            if($solicitacao->sub_assunto_id == 22){      
                                $this->loadModel('SolicitacaoCertidaoObitos');   
                                $s_cert_obito = $this->SolicitacaoCertidaoObitos->newEntity();
                                $s_cert_obito->solicitacao_id = $solicitacao->id;
                                $s_cert_obito->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_cert_obito->registrado_na_bahia = $this->request->getData('registrado_na_bahia');
                                $this->SolicitacaoCertidaoObitos->save($s_cert_obito);
                            }
                        }
                        else if($solicitacao->assunto_id == 42){
                            if($solicitacao->sub_assunto_id == 29){
                                $this->loadModel('SolicitacaoPlanoSaude');
                                $s_plano_saude = $this->SolicitacaoPlanoSaude->newEntity();
                                $s_plano_saude->solicitacao_id = $solicitacao->id;
                                $s_plano_saude->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_plano_saude->nome_plano = $this->request->getData('nome_plano');
                                $this->SolicitacaoPlanoSaude->save($s_plano_saude);
                            }
                        }
                        else if($solicitacao->assunto_id == 9){
                            if($solicitacao->sub_assunto_id == 30){
                                $this->loadModel('SolicitacaoAlimentos');
                                $s_alimentos = $this->SolicitacaoAlimentos->newEntity();
                                $s_alimentos->solicitacao_id = $solicitacao->id;
                                $s_alimentos->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_alimentos->tipo_beneficiario_id = $this->request->getData('tipo_beneficiario');
                                if($s_alimentos->tipo_beneficiario_id == 1){
                                    $s_alimentos->crianca_nascida = $this->request->getData('crianca_nasceu');
                                    if($s_alimentos->crianca_nascida == 0){
                                        $s_alimentos->tempo_gestacao = $this->request->getData('tempo_gestacao');
                                    }
                                    $s_alimentos->crianca_registrada = $this->request->getData('registrado_pai');
                                    $s_alimentos->pai_vivo = $this->request->getData('pai_vivo');
                                    $s_alimentos->pai_ofereceu_pensao = $this->request->getData('pai_ofereceu_pensao');
                                }else if($s_alimentos->tipo_beneficiario_id == 2){
                                    $s_alimentos->acao_divorcio = $this->request->getData('acao_divorcio');
                                }
                                $s_alimentos->alimentos_determinados = $this->request->getData('alim_deter');
                                if($s_alimentos->alimentos_determinados == 1){
                                    $s_alimentos->cobrar_parcelas = $this->request->getData('cobrar_parcelas');
                                    $s_alimentos->mudar_valor = $this->request->getData('mudar_valor');
                                }
                                $this->SolicitacaoAlimentos->save($s_alimentos);
                            }
                        }
                        else if($solicitacao->assunto_id == 44){
                            if($solicitacao->sub_assunto_id == 31){
                                $this->loadModel('SolicitacaoDesmarcarAgendamentos');
                                $s_desm_agend = $this->SolicitacaoDesmarcarAgendamentos->newEntity();
                                $s_desm_agend->solicitacao_id = $solicitacao->id;
                                $s_desm_agend->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_desm_agend->nome_agendado = $this->request->getData('nome_agendado');
                                $s_desm_agend->data_agendada = Time::createFromFormat('d/m/Y', $this->request->getData('data_agendam'));
                                $horario = $this->request->getData('hora_agendam').":00";
                                $s_desm_agend->hora_agendada = $horario;
                                $this->SolicitacaoDesmarcarAgendamentos->save($s_desm_agend);
                            }
                        }
                        else if($solicitacao->assunto_id == 38){
                            if($solicitacao->sub_assunto_id == 32){
                                $this->loadModel('SolicitacaoViolenciaDomesticas');
                                $s_violencia_domest = $this->SolicitacaoViolenciaDomesticas->newEntity();
                                $s_violencia_domest->solicitacao_id = $solicitacao->id;
                                $s_violencia_domest->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_violencia_domest->tipo_pedido_id = $this->request->getData('tipo_pedido');
                                $s_violencia_domest->ocorrencia_policial = $this->request->getData('ocorrencia_policial');
                                $s_violencia_domest->risco_fisico_emocional = $this->request->getData('risco_fisico_emocional');
                                $s_violencia_domest->medida_protetiva_urgencia = $this->request->getData('medida_protetiva_urgencia');
                                $s_violencia_domest->situacao_violencia = $this->request->getData('situacao_violencia');
                                $s_violencia_domest->ultima_violencia_sofrida = $this->request->getData('ultima_violencia_sofrida');                          
                                $this->SolicitacaoViolenciaDomesticas->save($s_violencia_domest);
                            }
                        }
                        /*Registro Publicos
                        else if($solicitacao->assunto_id == 41){
                            if($solicitacao->sub_assunto_id == 33){
                                $solic_atend_civel = $this->SolicitacaoAtendimentoCiveis->newEntity();
                                $solic_atend_civel->solicitacao_id = $solicitacao->id;
                                $solic_atend_civel->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_atend_civel->tipo_pedido_id = $this->request->getData('tipo_pedido_at_civel');
                                $this->SolicitacaoAtendimentoCiveis->save($solic_atend_civel);                            
                            }
                        }*/
                        else if($solicitacao->assunto_id == 43){
                            if($solicitacao->sub_assunto_id == 34){
                                $solic_fz_publica = $this->SolicitacaoFazendaPublica->newEntity();
                                $solic_fz_publica->solicitacao_id = $solicitacao->id;
                                $solic_fz_publica->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_fz_publica->tipo_pedido_id = $this->request->getData('tipo_pedido_faz_pub');
                                $solic_fz_publica->recebeu_intimacao = $this->request->getData('recebeu_intimacao');
                                if($solic_fz_publica->recebeu_intimacao == 1){
                                    $solic_fz_publica->numero = $this->request->getData('numero');
                                }
                                $this->SolicitacaoFazendaPublica->save($solic_fz_publica);                            
                            }
                        }
                        else if($solicitacao->assunto_id == 45){
                            if($solicitacao->sub_assunto_id == 35){
                                $this->loadModel('SolicitacaoSaudes');
                                $solic_sde = $this->SolicitacaoSaudes->newEntity();
                                $solic_sde->solicitacao_id = $solicitacao->id;
                                $solic_sde->sub_assunto_id = $solicitacao->sub_assunto_id;                            
                                $solic_sde->tipo_pedido_id = $this->request->getData('tipo_pedido_sde');
                                $solic_sde->plano_de_saude = $this->request->getData('planSaud');
                                if($solic_sde->plano_de_saude == 1){
                                    $solic_sde->nome_plano = $this->request->getData('n_pl');
                                }
                                $this->SolicitacaoSaudes->save($solic_sde);                              
                            }
                        }
                        else if($solicitacao->assunto_id == 40){
                            if($solicitacao->sub_assunto_id == 36){
                                $this->loadModel('SolicitacaoUsucapiao');
                                $solic_usuc = $this->SolicitacaoUsucapiao->newEntity();
                                $solic_usuc->solicitacao_id = $solicitacao->id;
                                $solic_usuc->sub_assunto_id = $solicitacao->sub_assunto_id;
                                if($this->request->getData('tp_usucapiao') == 1){
                                    $solic_usuc->primeiro_atendimento = 1;
                                    $solic_usuc->retornar_ao_nucleo = 0;
                                }else{
                                    $solic_usuc->primeiro_atendimento = 0;
                                    $solic_usuc->retornar_ao_nucleo = 1;
                                }
                                $this->SolicitacaoUsucapiao->save($solic_usuc);
                            }
                        }
                        else if($solicitacao->assunto_id == 39){
                            if($solicitacao->sub_assunto_id == 37){
                                $this->loadModel('SolicitacaoTrabalhistas');
                                $solic_trab = $this->SolicitacaoTrabalhistas->newEntity();
                                $solic_trab->solicitacao_id = $solicitacao->id;
                                $solic_trab->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_trab->vinculo_trabalhista_id = $this->request->getData('tipo_vinc');
                                $solic_trab->revisao_beneficio = $this->request->getData('tra_solic_vtb');
                                $this->SolicitacaoTrabalhistas->save($solic_trab);
                            }
                        }
                        else if($solicitacao->assunto_id == 37){
                            if($solicitacao->sub_assunto_id == 38){
                                $this->loadModel('SolicitacaoCasamentos');
                                $solic_casamento = $this->SolicitacaoCasamentos->newEntity();
                                $solic_casamento->solicitacao_id = $solicitacao->id;
                                $solic_casamento->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_casamento->noivos_maiores = $this->request->getData('noivos_maiores');
                                if($solic_casamento->noivos_maiores ==0){
                                    $solic_casamento->pais_de_acordo = $this->request->getData('pais_de_acordo');
                                }
                                $this->SolicitacaoCasamentos->save($solic_casamento);
                            }
                            else if($solicitacao->sub_assunto_id == 39){
                                $this->loadModel('SolicitacaoDivorcios');
                                $solic_divorcio = $this->SolicitacaoDivorcios->newEntity();
                                $solic_divorcio->solicitacao_id = $solicitacao->id;
                                $solic_divorcio->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_divorcio->casado = $this->request->getData('casado');
                                $solic_divorcio->filho_menor = $this->request->getData('filho_menor');
                                $solic_divorcio->possui_bens = $this->request->getData('possui_bens');
                                $solic_divorcio->divorcio_amigavel = $this->request->getData('tipo_divorcio');
                                $solic_divorcio->medida_protetiva = $this->request->getData('medida_protetiva');
                                $solic_divorcio->ocorrencia_conjuges = $this->request->getData('ocorrencia_conjuge');
                                $solic_divorcio->ocorrencia_filhos = $this->request->getData('ocorrencia_filho');
                                $this->SolicitacaoDivorcios->save($solic_divorcio);
                            }
                            else if($solicitacao->sub_assunto_id == 40){
                                $this->loadModel('SolicitacaoCertidaoCasamentos');
                                $solic_cert_casam = $this->SolicitacaoCertidaoCasamentos->newEntity();
                                $solic_cert_casam->solicitacao_id = $solicitacao->id;
                                $solic_cert_casam->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_cert_casam->realizado_na_bahia = $this->request->getData('realizado_bahia');
                                if($this->request->getData('tipo_atend') == 1){
                                    $solic_cert_casam->retificacao = 1;
                                    $solic_cert_casam->averbacao_nome_casada = 0;
                                    $solic_cert_casam->averbacao_obito = 0;
                                    $solic_cert_casam->transcricao_casamento = 0;
                                }
                                else if($this->request->getData('tipo_atend') == 2){
                                    $solic_cert_casam->retificacao = 0;
                                    $solic_cert_casam->averbacao_nome_casada = 1;
                                    $solic_cert_casam->averbacao_obito = 0;
                                    $solic_cert_casam->transcricao_casamento = 0;
                                }
                                else if($this->request->getData('tipo_atend')== 3){
                                    $solic_cert_casam->retificacao = 0;
                                    $solic_cert_casam->averbacao_nome_casada = 0;
                                    $solic_cert_casam->averbacao_obito = 1;
                                    $solic_cert_casam->transcricao_casamento = 0;
                                }
                                else{
                                    $solic_cert_casam->retificacao = 0;
                                    $solic_cert_casam->averbacao_nome_casada = 0;
                                    $solic_cert_casam->averbacao_obito = 0;
                                    $solic_cert_casam->transcricao_casamento = 1;
                                }
                                $this->SolicitacaoCertidaoCasamentos->save($solic_cert_casam);
                            }
                            else if($solicitacao->sub_assunto_id == 41){
                                $this->loadModel('SolicitacaoComprovarUniaoEstavel');
                                $solic_uniao_estavel = $this->SolicitacaoComprovarUniaoEstavel->newEntity();
                                $solic_uniao_estavel->solicitacao_id = $solicitacao->id;
                                $solic_uniao_estavel->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_uniao_estavel->companheiro_vivo = $this->request->getData('companheiro_vivo');
                                $solic_uniao_estavel->casal_vive_junto = $this->request->getData('casal_vive_junto');
                                $solic_uniao_estavel->obter_beneficio = $this->request->getData('obter_beneficio');
                                if($solic_uniao_estavel->obter_beneficio == 1){
                                    if($this->request->getData('tipo_instituto') == 1){
                                        $solic_uniao_estavel->inss = 1;
                                        $solic_uniao_estavel->inst_estadual = 0;
                                        $solic_uniao_estavel->inst_municipal = 0;
                                    }
                                    else if($this->request->getData('tipo_instituto') == 2){
                                        $solic_uniao_estavel->inss = 0;
                                        $solic_uniao_estavel->inst_estadual = 1;
                                        $solic_uniao_estavel->inst_municipal = 0;
                                    }else{
                                        $solic_uniao_estavel->inss = 0;
                                        $solic_uniao_estavel->inst_estadual = 0;
                                        $solic_uniao_estavel->inst_municipal = 1;
                                    }
                                }
                                $this->SolicitacaoComprovarUniaoEstavel->save($solic_uniao_estavel);
                            }
                        }
                        else if($solicitacao->assunto_id == 1){
                            if($solicitacao->sub_assunto_id == 51){
                                $this->loadModel('SolicitacaoAdocao');
                                $s_adocao = $this->SolicitacaoAdocao->newEntity();
                                $s_adocao->solicitacao_id = $solicitacao->id;
                                $s_adocao->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_adocao->idade_adotado = $this->request->getData('idade_adotado');
                                $this->SolicitacaoAdocao->save($s_adocao);
                            }
                        }
                        else if($solicitacao->assunto_id == 26){
                            if($solicitacao->sub_assunto_id == 52){
                                $this->loadModel('SolicitacaoViagemInter');
                                $s_vg_inter = $this->SolicitacaoViagemInter->newEntity();
                                $s_vg_inter->solicitacao_id = $solicitacao->id;
                                $s_vg_inter->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_vg_inter->inicio_viagem = Time::createFromFormat('d/m/Y', $this->request->getData('inicio_viagem'));
                                $s_vg_inter->fim_viagem = Time::createFromFormat('d/m/Y', $this->request->getData('fim_viagem'));
                                $s_vg_inter->previsao_retorno = $this->request->getData('previsao_retorno');
                                $this->SolicitacaoViagemInter->save($s_vg_inter);
                            }
                        }
                        else if($solicitacao->assunto_id == 2){
                            $this->loadModel('SolicitacaoAlvaraHeranca');
                            if($solicitacao->sub_assunto_id == 55){
                                $s_her_avara = $this->SolicitacaoAlvaraHeranca->newEntity();
                                $s_her_avara->solicitacao_id = $solicitacao->id;
                                $s_her_avara->sub_assunto_id = $solicitacao->sub_assunto_id;
                                if($this->request->getData("heranca_alvara") == 1){
                                    $s_her_avara->algum_bem = 1;
                                    $s_her_avara->apenas_valores = 0;
                                    $s_her_avara->nenhum_bem = 0;
                                }
                                else if($this->request->getData("heranca_alvara") == 2){
                                    $s_her_avara->algum_bem = 0;
                                    $s_her_avara->apenas_valores = 1;
                                    $s_her_avara->nenhum_bem = 0;
                                }
                                else{
                                    $s_her_avara->algum_bem = 0;
                                    $s_her_avara->apenas_valores = 0;
                                    $s_her_avara->nenhum_bem = 1;
                                }
                                
                                $s_her_avara->data_obito = Time::createFromFormat('d/m/Y', $this->request->getData('data_obito_alvara'));
                                $s_her_avara->filho_menor = $this->request->getData('f_menor_alvara');
                                $s_her_avara->bens_a_dividir = $this->request->getData('bens_dividir_alvara');
                                $this->SolicitacaoAlvaraHeranca->save($s_her_avara);
                            }
                            if($solicitacao->sub_assunto_id == 22){    
                                $this->loadModel('SolicitacaoCertidaoObitos');                      
                                $s_cert_obito = $this->SolicitacaoCertidaoObitos->newEntity();
                                $s_cert_obito->solicitacao_id = $solicitacao->id;
                                $s_cert_obito->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_cert_obito->registrado_na_bahia = $this->request->getData('registrado_na_bahia');
                                $this->SolicitacaoCertidaoObitos->save($s_cert_obito);
                            }
                        }
                        else if($solicitacao->assunto_id == 5){
                            if($solicitacao->sub_assunto_id == 57){
                                $this->loadModel('SolicitacaoExameDeDna'); 
                                $s_exame_de_dna = $this->SolicitacaoExameDeDna->newEntity();
                                $s_exame_de_dna->solicitacao_id = $solicitacao->id;
                                $s_exame_de_dna->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $s_exame_de_dna->pai_registro = $this->request->getData('pai_registro');
                                $this->SolicitacaoExameDeDna->save($s_exame_de_dna);
                            }
                        }
                        else if($solicitacao->assunto_id == 27){
                            if($solicitacao->sub_assunto_id == 58){
                                $this->loadModel('SolicitacaoIdoso'); 
                                $sass_idoso = $this->SolicitacaoIdoso->newEntity();
                                $sass_idoso->solicitacao_id = $solicitacao->id;
                                $sass_idoso->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $sass_idoso->tipo_pedido_id = $this->request->getData('tipo_pedido_idoso');
                                $sass_idoso->ocorrencia_policial = $this->request->getData('ocorrencia_policial_idoso');
                                $sass_idoso->maus_tratos = $this->request->getData('mau_trato_idoso');
                                $this->SolicitacaoIdoso->save($sass_idoso);
                            }
                        }
                        else if($solicitacao->assunto_id == 11){
                            if($solicitacao->sub_assunto_id == 59){
                                $this->loadModel('SolicitacaoRegulamentacaoVisita'); 
                                $solic_Regulam_visita = $this->SolicitacaoRegulamentacaoVisita->newEntity();
                                $solic_Regulam_visita->solicitacao_id = $solicitacao->id;
                                $solic_Regulam_visita->sub_assunto_id = $solicitacao->sub_assunto_id;
                                $solic_Regulam_visita->visita_determinada = $this->request->getData('visita_determinada');
                                if($solic_Regulam_visita->visita_determinada == 1){
                                    $solic_Regulam_visita->modificar = $this->request->getData('modificar');
                                }
                                $solic_Regulam_visita->cumprir_decisao = $this->request->getData('decisao_justica');
                                $this->SolicitacaoRegulamentacaoVisita->save($solic_Regulam_visita);                            
                            }
                        }
                    }
                    
                    $this->set('success', true);
                    $this->set('data', $solicitacao);
                }else{
                    $this->set('success', false);  

                    $mensagem = __('Este canal está suspenso temporariamente. Somente os casos urgentes serão atendidos pela nossa central de atendimento telefônico.');

                    $this->set('error_message', $mensagem);           
             
                }
                
            } catch (Exception $e) {
                $this->set('success', false);
                $this->set('error_message', $e->getMessage());
                
            }
        }

        $this->set('_serialize', ['success', 'error_message', 'data']);
    }

    public function listagemSolicitacoes2()
    {

    }
    public function listagemSolicitacoes()
    {
        $success = true;
        try {
            //$user = $this->Auth->identify();

            $this->loadModel('Solicitacoes');
            $this->loadModel('Agendamentos');

            $data = [];
            $user = [];
            $user['sigad_user'] = 179284;

            $agendamentos = $this->Agendamentos->find('pendentesPorUsuario', [
            'usuario_sigad' => $user['sigad_user'],
            'considerarHorario' => true
            ])->all();

            $this->loadModel('Situacoes');
            $cancelamento = $this->Situacoes->cancelamento();
            foreach ($agendamentos as $agendamento) {                
                
                if($agendamento->cancelado()){
                    continue;
                }

                if($agendamento->especializada->atendimento_remoto == 1){
                    $unid_atend = 'O ATENDIMENTO SERÁ REMOTO ATRAVÉS DE CONTATO TELEFÔNICO';
                    $end_atend = 'O ATENDIMENTO SERÁ REMOTO ATRAVÉS DE CONTATO TELEFÔNICO';
                }else{
                    $unid_atend = $agendamento->funcionario->unidade->nome;
                    $end_atend =  utf8_decode($agendamento->funcionario->unidade->endereco);
                }

                $data[] = [
                    'id_agendamento' => $agendamento->id,
                    'id' => null,
                    'requestDate' => $agendamento->data_cadastro->format("d/m/Y"),
                    'scheduleDate' => $agendamento != null ? $agendamento->agenda->data->format("d/m/Y") . " às " . $agendamento->agenda->escala->hora->nome : null,
                    'subject' => is_null($agendamento->tipo_aco->nome) ? 'Agendamento' : $agendamento->tipo_aco->nome,
                    'description' => '',
                    'answer' => '',
                    'status' => 2, // solicitacao com agendamento   
                    'unit' => $agendamento != null ? $unid_atend : null, 
                    'address' => $agendamento != null ? $end_atend : null,   
                    /*
                    'unit' => $agendamento != null ? 'O ATENDIMENTO SERÁ REMOTO ATRAVÉS DE CONTATO TELEFÔNICO' : null,
                    'address' => $agendamento != null ? 'O ATENDIMENTO SERÁ REMOTO ATRAVÉS DE CONTATO TELEFÔNICO' : null,  
                    */
                    /*'unit' => $agendamento != null ? utf8_decode($agendamento->funcionario->unidade->nome) : null,*/                                      
                    /*'address' => $agendamento != null ? utf8_decode($agendamento->funcionario->unidade->endereco) : null,     */               
                    'process' => ''
                    
                ];

            }
            
            $solicitacoes = $this->Solicitacoes->find()

                ->select(["id", "relato", "status", "processo", "numero_processo", "data_cadastro", "agendamento_id", "orientacao", "Assunto.nome"])
                ->join([
                    'table' => 'sigad.assuntos',
                    'alias' => "Assunto",
                    'type' => "LEFT",
                    "conditions" => ["Solicitacoes.assunto_id = Assunto.id"]
                ])
                ->where(['sigad_user' => $user['sigad_user'], 
                        'agendamento_id is' => null
                ])->all();
            
            foreach ($solicitacoes as $s) { // Solicitações que não foram agendadas
                $a = null;
                if ($s->agendamento_id != null) {
                    $a = $this->Agendamentos->findById($s->agendamento_id)
                        ->select(['Agendamentos.id', 'Agendamentos.funcionario_id', 'Funcionarios.id', 'Funcionarios.unidade_id','Unidades.nome', 'Unidades.endereco', 'Agendamentos.agenda_id', 'Agendas.data','Agendas.escala_id','Escalas.hora_id','Horas.nome'])->contain([
                        'Agendas' => ['Escalas' => ['Horas']],
                        'Funcionarios' => ['Unidades']
                    ])->first();
                }
                

                $data[] = [
                    'id_agendamento' => null,
                    'id' => $s->id,
                    'requestDate' => $s->data_cadastro->format("d/m/Y"),
                    'scheduleDate' => $a != null ? $a->agenda->data->format("d/m/Y") . " às " . $a->agenda->escala->hora->nome : null,
                    'subject' => $s['Assunto']['nome'],
                    'description' => $s->relato,
                    'answer' => $s->orientacao,
                    'status' => $s->status,
                    'unit' => $a != null ? 'O ATENDIMENTO SERÁ REMOTO ATRAVÉS DE CONTATO TELEFÔNICO' : null,
                    /*'unit' => $a != null ? utf8_decode($a->funcionario->unidade->nome) : null,   */
                    'address' => $a != null ? 'O ATENDIMENTO SERÁ REMOTO ATRAVÉS DE CONTATO TELEFÔNICO' : null,                  
                    /*'address' => $a != null ? utf8_decode($a->funcionario->unidade->endereco) : null, */                   
                    'process' => $s->numero_processo
                ];
            }            
           
        } catch (\Exception $e) {
            $success = false;            
            $this->set('error_message', $e->getMessage());                
            
            $this->set('_serialize', ['success', 'error_message']);

            return;
        }

        $this->set('success', $success);
        $this->set('data', $data);
        //        $this->set('agenda', $solicitacoes);
        $this->set('_serialize', ['success', 'data', 'agenda']);
           
    }
    
    public function recuperarSenha()
    {
        if ($this->request->is('post')){            
            $success = false;

            $username = $this->request->getData('username');
            $email = $this->request->getData('email');
            
            $this->loadModel('Users');
            $user = $this->Users->findUsuario($username, $email);

            if($user)
            {
                $user->passkey = uniqid();
                $user->timeout = time() + DAY;
                $url = Router::Url(['controller' => 'users', 'action' => 'reset'], true) . '/' . $user->passkey;

                if($this->Users->save($user))
                {
                    $this->sendResetEmail($url, $user);
                    $success = true;
                    $message = "Enviamos as instruções para recuperação de senha para o e-mail informado.";
                } else {
                    $success = false;
                    $message = "Desculpe, estamos com um problema temporário. Tente novamente mais tarde.";
                }
            } else {
                $success = false;
                $message = "Não encontramos um usuário com estes dados.";
            }

            $this->set('success', $success);
            if ($success) {
                $this->set('data', $message);
            } else {
                $this->set('error_message', $message);
            }

            $this->set('_serialize', ['success', 'data', 'error_message']);
        }
        
    }

    // TODO: Remove duplicate signature of this function
    private function sendResetEmail($url, $user)
    {
        $email = new Email();
        $email->template('resetpw');
        $email->emailFormat('both');
        $email->from('suporte@defensoria.ba.def.br');
        $email->to($user->email);
        $email->subject('Redefenir a senha do Agendamento Online - Defensoria da Bahia');
        $email->viewVars(['url' => $url]);
        $email->send();
    }

    public function cancelarAgendamento($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $success = true;

        try {
            $this->loadModel('Agendamentos');
            $this->Agendamentos->getConnection()->transactional(function() use($id, &$success){

                $this->loadModel('Situacoes');
                $cancelamento = $this->Situacoes->cancelamento();

                $this->loadModel('Agendamentos');
                $agendamento = $this->Agendamentos->findById($id)->first();
                
                $objeto = new Objeto;
                $objeto->agendamento = $agendamento;
                $objeto->obs = "Cancelado pelo assistido - Agendamento Online APP";
                $objeto->cancelamento = $cancelamento;
                
                $this->loadComponent('Historico');
                $historico = $this->Historico->registrarCancelamento($objeto);
               
                $this->loadModel('Historicos');
                if ($this->Historicos->save($historico)) {
                    $this->set('data', 'Agendamento cancelado com sucesso!');
                }else{
                    throw new \Exception("erro so salvar historico", 1121320);
                }
                
            });

        } catch(\Exception $e) {
            $this->Agendamentos->getConnection()->rollback();
            $success = false;
            $this->set('error_message', $e->getMessage());
        }

        $this->set('success', $success);
        $this->set('_serialize', ['success', 'data', 'error_message']);
    }

    public function cancelarSolicitacao($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        try {
            $user = $this->Auth->identify();
            $this->loadModel('Solicitacoes');
            $data = $this->Solicitacoes->proUsuario($user['sigad_user'], $id);
            $success = true;                                        
            if (is_null($data)) {
                $success = false;
                $this->set('error_message', "Solicitação de agendamento não encontrada!");
            } else {
                //var_dump($data);

                if ($data->agendamento_id != null) {
                    $this->loadModel('Historicos');
                    $historico = $this->Historicos->newEntity();
                    $historico->agendamento_id = $data->agendamento_id;
                    $historico->situacao_id = 43; //id para cancelado
                    $historico->data = date('Y-m-d H:i:s');
                    $historico->observacao = "Cancelado pelo assistido - Agendamento Online";

                    if ($this->Historicos->save($historico)) {
                        $this->set('data', 'Agendamento cancelado com sucesso!');
                    } else {
                        $success = false;
                        $this->set('error_message', 'Erro ao cancelar agendamento.');
                    }
                    $data->status = 4; // STATUS CANCELADO PELO USUÁRIO TODO: colocar isso numa constante
                    if ($this->Solicitacoes->save($data)) {
                        $this->set('data', 'Solicitação cancelada com sucesso!');
                    } else {
                        $success = false;
                        $this->set('data', 'Erro ao cancelar solicitação!');
                    }
                } else {
                    if($data->sub_assunto_id == 30){
                        $this->loadModel('SolicitacaoAlimentos');
                        $subAssunto = TableRegistry::get("solicitacao_alimentos");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoAlimentos->delete($sub);
                    }
                    if($data->sub_assunto_id == 29){
                        $this->loadModel('SolicitacaoPlanoSaude');
                        $subAssunto = TableRegistry::get("solicitacao_plano_saude");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoPlanoSaude->delete($sub);
                    }
                    if($data->sub_assunto_id == 31){
                        $this->loadModel('SolicitacaoDesmarcarAgendamentos');
                        $subAssunto = TableRegistry::get("solicitacao_desmarcar_agendamentos");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoDesmarcarAgendamentos->delete($sub);
                    }
                    if($data->sub_assunto_id == 32){
                        $this->loadModel('SolicitacaoViolenciaDomesticas');
                        $subAssunto = TableRegistry::get("solicitacao_violencia_domesticas");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoViolenciaDomesticas->delete($sub);
                    }
                    if($data->sub_assunto_id == 35){
                        $this->loadModel('SolicitacaoSaudes');
                        $subAssunto = TableRegistry::get("solicitacao_saudes");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoSaudes->delete($sub);
                    }
                    if($data->sub_assunto_id == 22){
                        $this->loadModel('SolicitacaoCertidaoObitos');
                        $subAssunto = TableRegistry::get("solicitacao_certidao_obitos");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoCertidaoObitos->delete($sub);
                    }
                    if($data->sub_assunto_id == 21){
                        $this->loadModel('SolicitacaoHerancas');
                        $subAssunto = TableRegistry::get("solicitacao_herancas");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoHerancas->delete($sub);
                    }
                    if($data->sub_assunto_id == 39){
                        $this->loadModel('SolicitacaoDivorcios');
                        $subAssunto = TableRegistry::get("solicitacao_divorcios");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoDivorcios->delete($sub);
                    }
                    if($data->sub_assunto_id == 38){
                        $this->loadModel('SolicitacaoCasamentos');
                        $subAssunto = TableRegistry::get("solicitacao_casamentos");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoCasamentos->delete($sub);
                    }
                    if($data->sub_assunto_id == 40){
                        $this->loadModel('SolicitacaoCertidaoCasamentos');
                        $subAssunto = TableRegistry::get("solicitacao_certidao_casamentos");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoCertidaoCasamentos->delete($sub);
                    }
                    if($data->sub_assunto_id == 41){
                        $this->loadModel('SolicitacaoComprovarUniaoEstavel');
                        $subAssunto = TableRegistry::get("solicitacao_comprovar_uniao_estavel");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoComprovarUniaoEstavel->delete($sub);
                    }
                    if($data->sub_assunto_id == 37){
                        $this->loadModel('SolicitacaoTrabalhistas');
                        $subAssunto = TableRegistry::get("solicitacao_trabalhistas");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoTrabalhistas->delete($sub);
                    }
                    if($data->sub_assunto_id == 36){
                        $this->loadModel('SolicitacaoUsucapiao');
                        $subAssunto = TableRegistry::get("solicitacao_usucapiao");
                        $sub = $subAssunto->find()->select()->where(["solicitacao_id" => $data->id])->first();
                        $this->SolicitacaoUsucapiao->delete($sub);
                    }

                    if($data->processo == 1){
                        $this->loadModel('SolicitacaoProcesso');
                        $soliProcesso =  TableRegistry::get("solicitacao_processo");
                        $sp = $soliProcesso->find()->select()->where(["solicitacao_id" => $data->id])->first();                
                        $this->SolicitacaoProcesso->delete($sp);
                    }

                    $this->Solicitacoes->delete($data); // TODO: Não remover tbm aqui, apenas mudar status - não fiz agora pq do tempo
                    $this->set('data', 'Solicitação cancelada com sucesso!');
                }
            }

        } catch(\Exception $e) {
            $success = false;
            $this->set('error_message', $e->getMessage());
        }
    
        $this->set('success', $success);
        $this->set('_serialize', ['success', 'data', 'error_message']);
    }

    public function locais($idAreaAtuacao = null) {
        $Locations = TableRegistry::get("Location");

        if ($idAreaAtuacao == null) {
            $this->set('success', true);
            $this->set('data', $Locations->find()->all());
            $this->set('_serialize', ['success', 'data', 'error_message']);
        } else {
            // TODO: filter locations by area atuacao
//            $Locations = TableRegistry::get("Location");
            $areaAtuacaoTable = TableRegistry::get("AreaAtuacao");
            $areaAtuacao = $areaAtuacaoTable->get($idAreaAtuacao);
                if($areaAtuacao != null){
                    $areaAtuacaoLocation = TableRegistry::get("AreaAtuacaoLocation");
                    $areaLocation = $areaAtuacaoLocation->find()->where(['area_atuacao_id' => $idAreaAtuacao]);
                    if($areaLocation != null){
                        $list = [];
                        foreach($areaLocation as $local){
                            $areaLocal = $Locations->get($local->localization_id);
                            $list[] = $areaLocal;
                        }
                    }
                    $this->set('success', true);
                    $this->set('data', $list);
                    $this->set('_serialize', ['success', 'data', 'error_message']);                    
                }

        }
    }

    public function localPrincipal() {
        // TODO: treat possible errors and set the right answers
        $Locations = TableRegistry::get("Location");
        $this->set('success', true);
        $this->set('data', $Locations->find()->where(['principal' => 1])->first());
        $this->set('_serialize', ['success', 'data', 'error_message']);
    }

    public function mudarSenha() {
        $this->request->allowMethod(['put']);

        $success = true;

        $newPassword = $this->request->getData('password');

        if (!empty($newPassword)) {
            $authUser = $this->Auth->identify();

            $this->loadModel('Users');

            $user = $this->Users->get($authUser['id']);
            $user->password = $newPassword;

            if ($this->Users->save($user)) {
                $this->set('data', "Senha alterada com sucesso!");
            } else {
                $success = false;
                $this->set('error_message', "Erro interno ao alterar senha. Por favor tente novamente.");
            }
        } else {
            $success = false;
            $this->set('error_message', "A nova senha não pode ser vazia.");
        }

        $this->set('success', $success);
        $this->set('_serialize', ['success', 'data', 'error_message']);
    }

    public function confirmarPresenca() {
        $this->request->allowMethod(['post']);
        $success = true;

        $idPrioridade = $this->request->getData('prioridade');
        $codigo = $this->request->getData('qrcode');

        $idAgendamento = $this->request->getData('agendamento');

        if ($codigo != 'gcL6tLd2Q8BTLK84-defensoria-bahia-instituicao-essencial-a-justica-XzNpAmZYZtPBFWFQ') {
            $success = false;
            $this->set('error_message', "Código de confirmação inválido!");
        } else {

            $user = $this->Auth->identify();

            if ($user['sigad_user'] == null) {
                $success = false;
                $this->set('error_message', "Erro ao identificar dados do usuário");
            } else {
                $conn = ConnectionManager::get('sigad');
                $this->loadModel('Solicitacoes');
                $this->loadModel('Agendamentos');
                
                $agendamentoHoje = null;
                $solicitacaoHoje = null;
                $a = $this->Agendamentos->findById($idAgendamento)
                    ->contain([
                        'Agendas' => ['Escalas' => ['Horas']],
                        'Unidades',
                    ])
                    ->where([
                    'assistido_id' => $user['sigad_user'],
                    //'status' => 2, // agendado
                    ])
                    ->first();
               
                //dd($a);
                $solicitacaoHoje = null;
                if (!is_null($a) && $a->agenda->data->format("Y-m-d") == date('Y-m-d')){
                        $agendamentoHoje = $a;
                        $sa = $this->Solicitacoes->find()->where(['agendamento_id' => $a->id])->first();
                        if(!is_null($sa)){
                            $solicitacaoHoje = $sa;
                        }
                }    
                
                if ($agendamentoHoje == null) {
                    $success = false;
                    $this->set('error_message', "Nenhum agendamento encontrado para a data de hoje.");
                } else {
                    // confirma presença e gerar senha!
                    
                    $idAgendamento = $agendamentoHoje->id;
                    if(!is_null($solicitacaoHoje)){

                        $solicitacaoHoje->status = 6; // presença confirmada
                        
                        $this->Solicitacoes->save($solicitacaoHoje);
                        $idAgendamento = $solicitacaoHoje->agendamento_id;
                    }
                    
                    $Historico = TableRegistry::get('Historicos');
                    $h = $Historico->newEntity();
                    $h->agendamento_id = $idAgendamento;
                    $h->situacao_id = 39; // presente
                    $h->data = date("Y-m-d H:i:s");
                    $h->funcionario_id = $agendamentoHoje->funcionario_id;
                    $h->observacao = "Confirmação realizada pelo aplicativo";

                    $today = date("Y-m-d");

                    $servidor = $conn->query("select count(*) as 'servidor' from perfis_usuarios pu
                                          inner join pessoas p on p.usuario_id = pu.usuario_id
                                          inner join funcionarios f on p.id = f.pessoa_id
                    where f.id = $agendamentoHoje->funcionario_id and perfil_id = 103;")->fetchAll(); // perfil é servidor
                    $idCategoria = $servidor[0][0] == 1 ? 19 : 17; // 19: Servidor, 17: Defensor

                    $qPainel = $conn->query("select painel_id from fila_guiches where funcionario_id = $agendamentoHoje->funcionario_id limit 1;")->fetchAll();
                    $idPainel = $qPainel[0][0];

                    $nomePainel = $conn->query("select nome from fila_paineis where id = $idPainel")->fetchAll()[0][0];

                    $sql = "select MAX(contador) + 1 AS contador from fila_senhas where unidade_id = $agendamentoHoje->unidade_id and painel_id = $idPainel and DATE(data_hora_emissao) = '$today';";
                    $count = $conn->query($sql)->fetchAll();
                    $contador = !empty($count[0][0]) ? $count[0][0] : 1;

                    $this->set('data', [$contador,$count, $idPainel,$agendamentoHoje->unidade_id, $today, $sql]);

                    $qSigla = $conn->query("select sigla from fila_categorias where id = $idCategoria;")->fetchAll();
                    $sigla = $qSigla[0][0];

                    $numero = !empty($idPrioridade) ? 'P' . str_pad($contador, 3, '0', STR_PAD_LEFT) : str_pad($contador, 3, '0', STR_PAD_LEFT);
                    $senha = "$numero - ($sigla)";

                    if ($Historico->save($h)) { // presença confirmada;
                        $FilaSenha = TableRegistry::get('FilaSenhas');
                        $FilaHistorico = TableRegistry::get('FilaHistoricos');

                        $fs = $FilaSenha->newEntity();
                        $fs->assistido_id = $user['sigad_user'];
                        $fs->funcionario_id = $agendamentoHoje->funcionario_id;
                        $fs->unidade_id = $agendamentoHoje->unidade_id;
                        $fs->data_hora_emissao  = date("Y-m-d H:i:s");
                        $fs->agendamento_id = $agendamentoHoje->id;
                        $fs->painel_id = $idPainel;
                        $fs->categoria_id = $idCategoria;
                        $fs->contador = $contador;
                        $fs->senha = $senha;
                        $fs->defensor_id = $agendamentoHoje->funcionario_id;
                        $fs->tipo_prioridade_id = $idPrioridade;
                        if ($FilaSenha->save($fs)) {             
                            $historico = $FilaHistorico->newEntity();
                            $historico->senha_id = $fs->id;
                            $historico->data_hora = date("Y-m-d H:i:s");
                            $historico->funcionario_id = $agendamentoHoje->funcionario_id;
                            $historico->situacao_id = 1; // situação aberta
                            $historico->observacao = "Senha gerada pelo aplicativo";
                            if ($FilaHistorico->save($historico)) {
                                $this->set('data', [
                                    'id' => $fs->id,
                                    'senha' => $senha,
                                    'painel' => $nomePainel,
                                    'emissao' => $fs->data_hora_emissao,
                                    'agendamentoId' => $agendamentoHoje->id
                                ]);
                            }
                        }

                    } else {
                        $success = false;
                        $this->set('error_message', "Erro ao confirmar presença.");
                    }
                }
            }
        }


        $this->set('success', $success);
        $this->set('_serialize', ['success', 'data', 'error_message']);
    }

    public function motivoContato(){
        $motivo = TableRegistry::get("processo_motivo_contato");
        $dados = $motivo->find('all');

        if(!empty($dados)){
            $this->set("success", true);
            $this->set("data", $dados);
        }else{
            $this->set("success", true);
            $this->set("error_message", "Erro ao listar motivos");
        }
        $this->set("_serialize", ["success", "data", "error_message"]); 
    }

    public function tipoPartes(){
        $tipo = TableRegistry::get("processo_tipo_partes");
        $dados = $tipo->find('all');

        if(!empty($dados)){
            $this->set("success", true);
            $this->set("data", $dados);
        }else{
            $this->set("success", true);
            $this->set("error_message", "Erro ao listar motivos");
        }
        $this->set("_serialize", ["success", "data", "error_message"]); 
    }

    public function comarca(){
        $conn = ConnectionManager::get('sigad');
        $dados = $conn
        ->newQuery()
        ->select('*')
        ->from('comarcas')->execute()->fetchAll('assoc');
        //$dados = $conn->execute("select id, nome from comarcas")->fetchAll();
        if(!empty($dados)){
            $this->set("success", true);
            $this->set("data", $dados);
        }else{
            $this->set("success", true);
            $this->set("error_message", "Erro ao listar motivos");
        }
        $this->set("_serialize", ["success", "data", "error_message"]); 
    }

    public function getComarca($nome){
        $conn = ConnectionManager::get('sigad');
        $dados = $conn->execute("select id as nome from comarcas where nome LIKE '%".$nome."%' LIMIT 1")->fetchAll();
        if(!empty($dados)){
            $this->set("success", true);
            $this->set("data", $dados[0][0]);
        }else{
            $this->set("success", true);
            $this->set("error_message", "Erro ao listar motivos");
        }
        $this->set("_serialize", ["success", "data", "error_message"]);   
    }

    public function subassunto(){
        $subAssunto = TableRegistry::get("sub_assuntos");
        $dados = $subAssunto->find('all');
        if(!empty($dados)){
            $this->set("success", true);
            $this->set("data", $dados);
        }else{
            $this->set("success", false);
            $this->set("error_message", "Erro ao listar assuntos");
        }
        $this->set("_serialize", ["success", "data", "error_message"]);   
    }

    public function tipoPedido($id){
        $assunto = TableRegistry::get("sub_assuntos");
        $idAssunto = $assunto->find()->select(["id"])->where(["assunto_id" => $id]);
        $count = $assunto->find("all")->where(["assunto_id" => $id]);

        if($count->count() > 1){
            $subAssunto = $assunto->find()->where(["assunto_id" => $id]);
            $this->set("success", true);
            $this->set("data", $subAssunto);

        }else{
            $pedido = TableRegistry::get("tipo_pedidos");
            $dados = $pedido->find()->join([
                'table' => 'agenda.tipo_pedido_sub_assuntos',
                'alias' => "tp",
                'type' => "LEFT",
                "conditions" => ["tipo_pedidos.id = tp.tipo_pedido_id"],
            ])->where(["sub_assunto_id" => $idAssunto]);

            if($dados->count() > 1){
                $this->set("success", true);
                $this->set("data", $dados);
                
            }else{
                $this->set("success", false);
                $this->set("error_message", "Não existe este assunto_id");
            }            
        }


        $this->set("_serialize", ["success", "data", "error_message"]);  
    }

    //Processos no SIGAD
    public function verificacaoPje(){
        $this->viewBuilder()->className('Json');
        $Processos = TableRegistry::get("processos");
        $data = date("Y-m-d");
        //Verifica todos processos do dia 
        $dados = $Processos->find('all', [
            'conditions' => ['processos.verificado_pje is null'],
            'limit' => ['5']
        ]);
        // $dados = $Processos->find('all', [
        //     'conditions' => ['processos.verificado_pje is null', "processos.data_cadastro LIKE"=> $data."%"]
        // ]);
        //Se existir processo
        if(!empty($dados)){
            for($i = 0; $i <= $dados->count(); $i++){
                //Pega o primeiro processo da fila
                // $dado = $Processos->find()->select(['numeracao_unica'])->where(['verificado_pje is null', "data_cadastro LIKE '".$data."%'"])->limit(1);
                $dado = $Processos->find()->select(['numeracao_unica'])->where(['verificado_pje is null'])->limit(1);
                foreach($dado as $d){
                    $query = $Processos->query();
                    $api = self::api($d->numeracao_unica);
                    //Se Api do Pje estiver em manutenção
                    if($api == "500"){
                        $this->set('processos', "PJE em Manutenção");
                        $this->set('_serialize', 'processos');                     
                    }else{
                        //Se o processo existir no PJE
                        if($api == "true"){
                            $result = $query->update()->set(['processo_pje' => 1, 'verificado_pje' => 1])->where(['numeracao_unica' => $d->numeracao_unica])->execute();                 
                        }else{
                            $result = $query->update()->set(['processo_pje' => 0, 'verificado_pje' => 1])->where(['numeracao_unica' => $d->numeracao_unica])->execute();
                        }          
                        $this->set('processos', "Processos Verificados");
                        $this->set('_serialize', 'processos');        
                    }
                }
            }            
        }else{
            $this->set('processos', "Não existe Processos pendentes");
            $this->set('_serialize', ['processos']); 
        }

    }
    
    //Solicitações na Agenda
    public function verificacaoSolicitacaoPje(){
        try{
            $this->viewBuilder()->className('Json');
            $Processos = TableRegistry::get("solicitacoes");
            $data = date("Y-m-d");
            $dataAtual = date("Y-m-d H:i:s");
            $dados = $Processos->find('all', [
                'conditions' => ['solicitacoes.processo' => 1,'verificado_pje is null', "data_cadastro LIKE"=> $data."%"],
            ]);
            //$dado = $Processos->find()->select()->where(['verificado_pje' => 2])->all(1);

            //$this->set('processos', $dado);
            //$this->set('_serialize', ['processos']);
            $list = array();
            if(!empty($dados)){
                for($i = 0; $i <= 50; $i++){
                    //Pega o primeiro processo da fila
                    $dado = $Processos->find()->select(['numero_processo'])->where(['processo' => 1,'verificado_pje is null', "data_cadastro LIKE"=> $data."%"])->limit(1);
                    foreach($dado as $d){
                        $query = $Processos->query();

                        //if(strlen($d->numero_processo) >= 20){
                            $api = self::api($d->numero_processo);
                            array_push($list, $d->numero_processo);
                            //Se Api do Pje estiver em manutenção
                            if($api == "500"){
                        
                            }else{
                                //Se o processo existir no PJE
                                if($api == "true"){
                                    $result = $query->update()->set(['processo_pje' => 1, 'verificado_pje' => 1, 'data_verificacao_pje' => $dataAtual])->where(['numero_processo' => $d->numero_processo])->execute();                 
                                }else{
                                    $result = $query->update()->set(['processo_pje' => 0, 'verificado_pje' => 1, 'data_verificacao_pje' => $dataAtual])->where(['numero_processo' => $d->numero_processo])->execute();
                                }          
                                $this->set('numeracao_unica', $list);      
                                $this->set('processos', "Processos Verificados");
                                $this->set('_serialize', ['processos', 'numeracao_unica']);        
                            }                                                
                        //}
                    }
                }            
            }else{
                $this->set('processos', "Não existe Processos pendentes");
                $this->set('_serialize', ['processos']); 
            } 
        } catch (Exception $e) {
            $this->set('success', false);
            $this->set('error_message', $e->getMessage());
        }
    }

    private function api($num_unica){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pje1g-mni.tjba.jus.br/pje/intercomunicacao',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://www.cnj.jus.br/servico-intercomunicacao-2.2.2/" xmlns:tip="http://www.cnj.jus.br/tipos-servico-intercomunicacao-2.2.2">
            <soapenv:Header/>
            <soapenv:Body>
            <ser:consultarProcesso>
            <tip:idConsultante>17493080020</tip:idConsultante>
            <tip:senhaConsultante>DPEb@2019</tip:senhaConsultante>
            <tip:numeroProcesso>'.$num_unica.'</tip:numeroProcesso>
            </ser:consultarProcesso>
            </soapenv:Body>
            </soapenv:Envelope>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/xml',
                'Cookie: ADC_CONN_539B3595F4E=A950D037711C683F91898B0FB305952764C44531CE055C4B0CAE32ABFF2EEC61C93EB835C1215054; JSESSIONID=siDwNDbmN1nJTFjOt1OjlOuaA-y7HM_DYLNVCLj0.pje1gapp002; ADC_REQ_2E94AF76E7=FE5811571A7A32BD5A0ECBB6BE3F4482369CD8EEBEFF9CB84BC4C1CE72890AB877F15E141A469942'
            ),
        ));

        $response = curl_exec($curl);
        $codeHTTP = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if($codeHTTP == 200){
            $xml = substr($response, 188, -45);
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", (string)$xml);
            $xml = new \SimpleXMLElement(utf8_encode($response));
            $body = $xml->xpath('//soapBody');
            $array = json_encode((array)$body); 
            $res = json_decode($array, true);
            if($res[0]['ns4consultarProcessoResposta']['sucesso'] == 'true'){
                return 'true';
            }else{
                return 'false';
            } 
        }else{
            return "500";
        }

    }


    // - tranca sexta às 15h 
    //- abre segunda as 07h 
    //- tranca feriado
    public function VerificaBloqueio(){
        $dia_semana = date('w');
        $hora_atual = date('H:i');        
        $exibe = false;
        $agoraSigad = date('Y-m-d');
        $agora = date('Y/m/d');
        $bloq = 0;

        $conn = ConnectionManager::get('sigad');
        $dados = $conn
        ->newQuery()
        ->select('*')
        ->from('bloqueio_solicitacao')->execute()->fetchAll('assoc');
        foreach($dados as $datas){
            $dateInicial = new DateTimeImmutable($datas['data_inicio']); 
            $dateFinal = new DateTimeImmutable($datas['data_fim']);
            
            if($agoraSigad >= $dateInicial->format('Y-m-d') && $agoraSigad <= $dateFinal->format('Y-m-d')){
                $exibe = true;   
                if($dateInicial->format('Y-m-d') === $dateFinal->format('Y-m-d')){
                    if($hora_atual >= $datas['hora_inicio'] && $hora_atual < $datas['hora_fim']){
                        $exibe = true;
                    }else{
                        $exibe = false;
                    }
                }else{
                    if($agoraSigad === $dateInicial->format('Y-m-d')){
                        if($hora_atual >= $datas['hora_inicio']){
                            $exibe = true;
                        }else{
                            $exibe = false;
                        }
                    }
                    else if($agoraSigad === $dateFinal->format('Y-m-d')){
                        if($hora_atual < $datas['hora_fim']){
                            $exibe = true;
                        }else{
                            $exibe = false;
                        }
                    }                    
                }
            }
        }
        if($exibe != true){
            $horaAtual = date("H:i:s");
            $diaSemana = date('N');
            if($horaAtual >= '08:00:00' && $horaAtual <= '17:00:00' && $diaSemana >= 1 && $diaSemana <= 5)
            {
                $exibe = false;
            }else{
                $exibe = true;
            }
        }
        if($bloq == 1){

            if($dia_semana >= 1 and $dia_semana <= 4){
                //segunda a quinta
                /*
                if($dia_semana == 1 and $hora_atual < '07:00'){ 
                    if($hora_atual < '07:00' or $hora_atual > '18:00'){            
                        
                        $exibe = true;
                                        
                    }else{                    
                        $exibe = false;
                    }
                } 
                */
                 $exibe = false;                          
            }elseif($dia_semana == 5){
                //sexta
                if($hora_atual < '07:00' or $hora_atual > '15:00'){
                    $exibe = true;
                }else{
                    $exibe = false;
                }
            }else{
                //fim de semana
                $exibe = true;
            } 
        }

        //Bloquear uma data específica
        $data_bloqueada = ["01/05/2023"];
        $encontrou_data = in_array($agora, $data_bloqueada);             
        if($encontrou_data){
            $exibe = true;
        }        
        $this->set("success", true);
        $this->set("data", $exibe);   
        $this->set("_serialize", ["success", "data", "error_message"]); 
    }

    public function validarHorario()
    {
        $horaAtual = date("H:i:s");
        $diaSemana = date('N');

        if($horaAtual >= '08:00:00' && $horaAtual <= '17:00:00' && $diaSemana >= 1 && $diaSemana <= 5)
        {
            return true;
        }

        return false;
    }

    public function notificacao($id){
        $conn = ConnectionManager::get('sigad');
        $dados = $conn->execute("select count(*) from notifica_assistidos where assistido_id = ".$id." and msg_lida = 0")->fetchAll();
        if($dados[0][0] != 0){
            $this->set("success", true);
            $this->set("data", $dados[0][0]);
        }else{
            $this->set('success', false); 
        }

        $this->set("_serialize", ["success", "data", "error_message"]);      
    }

    public function termos($id){
        $conn = ConnectionManager::get('sigad');
        $dados = $conn->execute("select * from assistidos where id = ".$id)->fetchAll();
        if($dados[0][7] == null){
            $this->set("success", true);
        }else{
            $this->set('success', false); 
        }

        $this->set("_serialize", ["success", "data", "error_message"]);      
    }

    public function atualizarTermos($id,$termo){
        $conn = ConnectionManager::get('sigad');
        $dados = $conn->execute("UPDATE assistidos set autorizacao_lgpd =".$termo." where id = ".$id)->execute();
        if($dados){
            $this->set("success", true);
        }
        $this->set("_serialize", ["success", "data", "error_message"]);   
    }

    public function getMensagem($id){
        $Notifica = TableRegistry::get("NotificaAssistidos");
        $dados = $Notifica->find()
        ->select(['NotificaAssistidos.id','NotificaAssistidos.acao_historico_id', 'NotificaAssistidos.assistido_id', 'NotificaAssistidos.created', 'NotificaAssistidos.data_notificacao', 'NotificaAssistidos.email_assistido', 'NotificaAssistidos.funcionario_id', 'NotificaAssistidos.msg_lida', 'NotificaAssistidos.relato', 'ac.tipo_acao_id', 'ac.processo_id', 'numeracao_unica' => 'p.numeracao_unica', 'ac.numero', 'ac.situacao_id', 'nome' => 'si.nome'])
        ->join([
            'table' => 'sigad.acao_historicos',
            'alias' => 'ah',
            'type' => 'LEFT',
            'conditions' => ['NotificaAssistidos.acao_historico_id = ah.id']
        ])
        ->join([
            'table' => 'sigad.acoes',
            'alias' => 'ac',
            'type' => 'LEFT',
            'conditions' => ['ac.id = ah.acao_id']
        ])
        ->join([
            'table' => 'sigad.situacoes',
            'alias' => 'si',
            'type' => 'LEFT',
            'conditions' => ['ac.situacao_id = si.id']
        ])
        ->join([
            'table' => 'sigad.processos',
            'alias' => 'p',
            'type' => 'LEFT',
            'conditions' => ['ac.processo_id = p.id']
        ])
        ->where(["NotificaAssistidos.assistido_id" => $id])
        ->order('NotificaAssistidos.msg_lida ASC, NotificaAssistidos.data_notificacao  DESC')->toArray();
        if(!empty($dados)){
            
            $this->set("success", true);
            $this->set("data", $dados);
        }else{
            $this->set("success", false);
        }
        $this->set("_serialize", ["success", "data", "error_message"]);  
    }
    
    public function readMensagem($id){
        $this->loadModel('NotificaAssistidos');
        $notificacao = TableRegistry::get("NotificaAssistidos");

        $query = $notificacao->query();
        $res = $query->update()->set(['msg_lida' => 1])->where(['id = '.$id])->execute();
        if(!empty($res)){
            $this->set('success', true);
        }else{
            $this->set('success', false);
        }

        $this->set("_serialize", ["success"]);

    }

    public function anexos($id, $user){
        $this->loadModel('NotificaAssistidos');   
        $notificacao = TableRegistry::get("NotificaAssistidos");
        $anexos =  $notificacao->find()
        ->select([ "url" => 'a.caminho_fisico','filename' => 'a.filename', "anexo_id" => 'aa.anexo_id'])
        ->join([
            'table' => 'sigad.acao_historicos',
            'alias' => 'ah',
            'type' => 'LEFT',
            'conditions' => ['ah.id = NotificaAssistidos.acao_historico_id']
        ])
        ->join([
            'table' => 'sigad.acoes_anexos',
            'alias' => 'aa',
            'type' => 'LEFT',
            'conditions' => ['ah.acao_id = aa.acao_id']
        ])
        ->join([
            'table' => 'sigad.anexos',
            'alias' => 'a',
            'type' => 'LEFT',
            'conditions' => ['aa.anexo_id = a.id']
        ])
        ->where(['NotificaAssistidos.id = '.$id, 'NotificaAssistidos.assistido_id' => $user])->toArray();

        if(!empty($anexos[0]["url"])){
            $this->set("success", true);
            $this->set("data", $anexos);
        }else{
            $this->set("success", false);
        }
        $this->set("_serialize", ["success", "data", "error_message"]);  
    }

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Integração Aviso Pendentes Plataforma PJE - (INICIO)

    public function expedientespje(){
        
        $conn       = ConnectionManager::get('sigad');
        $sql_url    = "SELECT * FROM sigad.pje_urls;";      
        $sql        = "SELECT * FROM pje_perfis as pf;";
        $credencial = $conn->query($sql)->fetchAll();
        $consulta   = $conn->query($sql_url)->fetchAll();
        $enderecoUrl =  $consulta[0][2];
        $cookkie    =  $consulta[0][3];
        $perfil     = ["CUR", "NC", "G"];

        for($k = 0; $k < 3; $k++){

            $sql = "SELECT data_expedicao
                    FROM pje_aviso_pendentes
                    WHERE perfil_importacao = '".$perfil[$k]."'
                    ORDER BY data_expedicao DESC
                    LIMIT 1;";
                    
            $ultimaData = $conn->query($sql)->fetchAll();

            if(empty($ultimaData)){
                $dataHoraInicial = date('Ymd000000');
            }
            else{
                $dataHoraInicial = date('YmdHis', strtotime($ultimaData[0][0]));
            }  
            
            if($perfil[$k] == "CUR"){
                $res = self::consultaAvisoPendentesApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
                $tpPendencia = 1;
                if($res == 500){
                    $enderecoUrl = $consulta[0][4];
                    $res = self::consultaAvisoPendentesApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
                }
            }
            else if($perfil[$k] == "NC"){
                $res = self::consultaAvisoPendentesApi($credencial[1][1], $credencial[1][2], $dataHoraInicial, $enderecoUrl, $cookkie);
                $tpPendencia = 1;
                if($res == 500){
                    $enderecoUrl = $consulta[0][4];
                    $res = self::consultaAvisoPendentesApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
                }     
            }
            else{
                $res = self::consultaAvisoPendentesApi($credencial[2][1], $credencial[2][2], $dataHoraInicial, $enderecoUrl, $cookkie);
                $tpPendencia = 1;
                if($res == 500){
                    $enderecoUrl = $consulta[0][4];
                    $res = self::consultaAvisoPendentesApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
                }
            }
            if($res != 500){
                $tam = count($res);
                if($tam == 1)
                {
                    $sql        = "SELECT pda.id FROM sigad.pje_descricao_atos as pda WHERE pda.nome = '".$res['PjeIntimacao']['descricao_ato']."'";
                    $retorno    = $conn->query($sql)->fetchAll();

                    if (empty($retorno))
                    {
                        $sqlInsert = "INSERT INTO sigad.pje_descricao_atos (nome) VALUES ('".$res['PjeIntimacao']['descricao_ato']."');";
                        $conn->query($sqlInsert);
                    }                    
    
                    $sql = "INSERT INTO
                    pje_intimacao (
                        id_aviso,
                        cod_orgao_julgador,
                        nome_orgao,
                        processo_numeracao_unica,
                        codigoLocalidade,
                        tipo_comunicacao,
                        destinatario_pje,
                        docDestinatario,
                        tipoPessoa,
                        valor_causa,
                        prazo,
                        data_limite_ciencia,
                        descricao_ato,
                        data_expedicao,
                        meio,
                        data_disponibilizacao,
                        instancia,
                        codigo_municipio_ibge
                    )
                    VALUES (
                        '".$res['PjeIntimacao']['id_aviso']."', 
                        '".$res['PjeIntimacao']['cod_orgao_julgador']."',
                        '".addslashes($res['PjeIntimacao']['nome_orgao'])."',
                        '".$res['PjeIntimacao']['processo_numeracao_unica']."',
                        '".$res['PjeIntimacao']['codigoLocalidade']."',
                        '".$res['PjeIntimacao']['tipo_comunicacao']."',
                        '".addslashes($res['PjeIntimacao']['destinatario_pje'])."',
                        '".$res['PjeIntimacao']['docDestinatario']."',
                        '".$res['PjeIntimacao']['tipoPessoa']."',
                        '".$res['PjeIntimacao']['valor_causa']."',
                        '".$res['PjeIntimacao']['prazo']."',
                        '".$res['PjeIntimacao']['data_limite_ciencia']."',
                        '".$res['PjeIntimacao']['descricao_ato']."',
                        '".$res['PjeIntimacao']['data_expedicao']."',
                        '".$res['PjeIntimacao']['meio']."',
                        '".$res['PjeIntimacao']['data_disponibilizacao']."',
                        '".$res['PjeIntimacao']['instancia']."',
                        '".$res['PjeIntimacao']['codigo_municipio_ibge']."'
                    );";
                    $conn->query($sql);
                }
                else{
                    for($j = 0; $j < $tam; $j++){
                        $sql        = "SELECT pda.id FROM pje_descricao_atos as pda WHERE pda.nome = '".$res[$j]['PjeIntimacao']['descricao_ato']."'";
                        $retorno    = $conn->query($sql)->fetchAll();

                        if (empty($retorno))
                        {
                            $sqlInsert = "INSERT INTO pje_descricao_atos (nome) VALUES ('".$res[$j]['PjeIntimacao']['descricao_ato']."');";
                            $conn->query($sqlInsert);
                        }

                        $sql = "INSERT INTO
                        pje_intimacao (
                            id_aviso,
                            cod_orgao_julgador,
                            nome_orgao,
                            processo_numeracao_unica,
                            codigoLocalidade,
                            tipo_comunicacao,
                            destinatario_pje,
                            docDestinatario,
                            tipoPessoa,
                            valor_causa,
                            prazo,
                            data_limite_ciencia,
                            descricao_ato,
                            data_expedicao,
                            meio,
                            data_disponibilizacao,
                            instancia,
                            codigo_municipio_ibge
                        )
                        VALUES (
                            '".$res[$j]['PjeIntimacao']['id_aviso']."', 
                            '".$res[$j]['PjeIntimacao']['cod_orgao_julgador']."',
                            '".addslashes($res[$j]['PjeIntimacao']['nome_orgao'])."',
                            '".$res[$j]['PjeIntimacao']['processo_numeracao_unica']."',
                            '".$res[$j]['PjeIntimacao']['codigoLocalidade']."',
                            '".$res[$j]['PjeIntimacao']['tipo_comunicacao']."',
                            '".addslashes($res[$j]['PjeIntimacao']['destinatario_pje'])."',
                            '".$res[$j]['PjeIntimacao']['docDestinatario']."',
                            '".$res[$j]['PjeIntimacao']['tipoPessoa']."',
                            '".$res[$j]['PjeIntimacao']['valor_causa']."',
                            '".$res[$j]['PjeIntimacao']['prazo']."',
                            '".$res[$j]['PjeIntimacao']['data_limite_ciencia']."',
                            '".$res[$j]['PjeIntimacao']['descricao_ato']."',
                            '".$res[$j]['PjeIntimacao']['data_expedicao']."',
                            '".$res[$j]['PjeIntimacao']['meio']."',
                            '".$res[$j]['PjeIntimacao']['data_disponibilizacao']."',
                            '".$res[$j]['PjeIntimacao']['instancia']."',
                            '".$res[$j]['PjeIntimacao']['codigo_municipio_ibge']."'
                        );";
                        $conn->query($sql);
                    }
                }

                $sql = "SELECT * FROM pje_intimacao;";
                $tableAviso = $conn->query($sql)->fetchAll();
                $tamTable = count($tableAviso);
            
                if($tamTable == 1){
                    $retAviso = $this->existeAviso($tableAviso[0][1]);
                    $retProc = $this->existeProcesso($tableAviso[0][4]);
                    $retAtuacao = $this->existeAtuacao($tableAviso[0][4]);
                    $idAtc = $this->idAtuacao($tableAviso[0][2]);
                    $idUnidDef = $this->idUnidDefensorial($tableAviso[0][2]);

                    if($idAtc == -1){
                         $sql = "INSERT INTO atuacoes (cod_pje, nome) VALUES ('".$tableAviso[0][2]."','".$tableAviso[0][3]."');";
                         $conn->query($sql);
                         $sql = "SELECT id FROM atuacoes ORDER BY id DESC LIMIT 1;";
                         $retorno = $conn->query($sql)->fetchAll();
                         $idAtc = $retorno[0][0];
                     }
                    if($retAviso == 0){
                        $avisoPendente['PjeAvisoPendentes']['perfil_importacao']         = $perfil[$k];
                        $avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']         = $tpPendencia;
                        $avisoPendente['PjeAvisoPendentes']['id_aviso']                  = $tableAviso[0][1];
                        $avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']        = $tableAviso[0][2];
                        $avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']  = $this->mascara($tableAviso[0][4],"#######-##.####.#.##.####");
                        $avisoPendente['PjeAvisoPendentes']['codigoLocalidade']          = $tableAviso[0][5];
                        $avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']          = $tableAviso[0][6];
                        $avisoPendente['PjeAvisoPendentes']['destinatario_pje']          = $tableAviso[0][7];
                        $avisoPendente['PjeAvisoPendentes']['docDestinatario']           = $tableAviso[0][8];                        
                        $avisoPendente['PjeAvisoPendentes']['tipoPessoa']                = $tableAviso[0][9];
                        $avisoPendente['PjeAvisoPendentes']['valor_causa']               = $tableAviso[0][10];                   
                        $avisoPendente['PjeAvisoPendentes']['prazo']                     = $tableAviso[0][11];
                        $avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']       = $tableAviso[0][12];
                        $avisoPendente['PjeAvisoPendentes']['descricao_ato']             = $tableAviso[0][13];
                        $avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']      = (int)$this->descricao_ato((string)$tableAviso[0][13]);
                        $avisoPendente['PjeAvisoPendentes']['data_expedicao']            = $tableAviso[0][14];
                        $avisoPendente['PjeAvisoPendentes']['meio']                      = $tableAviso[0][15]; 
                        $avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']     = $tableAviso[0][16];
                        $avisoPendente['PjeAvisoPendentes']['instancia']                 = $tableAviso[0][17];
                        $avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']     = $tableAviso[0][18];
                        $avisoPendente['PjeAvisoPendentes']['processo_id']               = $retProc;

                        if($retProc != null){
                            $sql = "UPDATE processos
                                    SET valor_causa = '".$tableAviso[0][10]."', processo_pje = 1, verificado_pje = 1
                                    WHERE id = $retProc;";
                            $conn->query($sql);

                            if($retAtuacao == null){
                                $sql = "UPDATE processos SET atuacao_id = $idAtc WHERE id = $retProc;";
                                $conn->query($sql);
                            }
                        }

                        $sql = "INSERT INTO
                        pje_aviso_pendentes (
                            perfil_importacao,
                            tipo_pendencia_id,
                            id_aviso,
                            cod_orgao_julgador,
                            processo_numeracao_unica,
                            codigoLocalidade,
                            tipo_comunicacao,
                            destinatario_pje,
                            docDestinatario,
                            nome_sigad,
                            tipoPessoa,
                            valor_causa,
                            prazo,
                            data_limite_ciencia,
                            descricao_ato,
                            pje_descricao_ato_id,
                            data_expedicao,
                            meio,
                            data_disponibilizacao,
                            instancia,
                            codigo_municipio_ibge,
                            processo_id
                        )
                        VALUES (
                            '".$avisoPendente['PjeAvisoPendentes']['perfil_importacao']."', 
                            '".$avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']."',
                            '".$avisoPendente['PjeAvisoPendentes']['id_aviso']."',
                            '".$avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']."',
                            '".$avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']."',
                            '".$avisoPendente['PjeAvisoPendentes']['codigoLocalidade']."',
                            '".$avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']."',
                            '".$avisoPendente['PjeAvisoPendentes']['destinatario_pje']."',
                            '".$avisoPendente['PjeAvisoPendentes']['docDestinatario']."',
                            'nome',
                            '".$avisoPendente['PjeAvisoPendentes']['tipoPessoa']."',
                            '".$avisoPendente['PjeAvisoPendentes']['valor_causa']."',
                            '".$avisoPendente['PjeAvisoPendentes']['prazo']."',
                            '".$avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']."',
                            '".$avisoPendente['PjeAvisoPendentes']['descricao_ato']."',
                            '".$avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']."',
                            '".$avisoPendente['PjeAvisoPendentes']['data_expedicao']."',
                            '".$avisoPendente['PjeAvisoPendentes']['meio']."',
                            '".$avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']."',
                            '".$avisoPendente['PjeAvisoPendentes']['instancia']."',
                            '".$avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']."',
                            '".$avisoPendente['PjeAvisoPendentes']['processo_id']."'
                        );";

                        $conn->query($sql);

                        if(strcmp($tableAviso[0][8], 'Não informado') != 0){
                            if(strcmp($tableAviso[0][9], 'fisica') == 0){
                                $idAssit = $this->existeCPF($tableAviso[0][8]);
                            }
                            else if(strcmp($tableAviso[0][9], 'juridica') == 0){
                                $idAssit = $this->existeCNPJ($tableAviso[0][8]);
                            }
                        }
                        else{
                            $idAssit = 0; 
                        }

                        if(($retProc == null)&&($idAssit != 0)){

                            $sql = "INSERT INTO
                            processos (
                                numeracao_unica,
                                assistido_id,
                                comarca_id,
                                atuacao_id,
                                valor_causa,
                                processo_pje,
                                verificado_pje,
                                unidade_defensorial_id
                            )
                            VALUES (
                                '".$this->mascara($tableAviso[0][4],"#######-##.####.#.##.####")."',
                                '".$idAssit."',
                                '".$this->IdComarca($tableAviso[0][5])."',
                                '".$idAtc."',
                                '".$tableAviso[0][10]."',
                                '1',
                                '1',
                                '".$idUnidDef."'
                            );";
                            $conn->query($sql);

                            $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
                            $retorno = $conn->query($sql)->fetchAll();
                            $idPAP = $retorno[0][0];

                            $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
                            $retorno = $conn->query($sql)->fetchAll();
                            $idPrc = $retorno[0][0];

                            $sql = "UPDATE pje_aviso_pendentes
                                    SET processo_id = '".$idPrc."'
                                    WHERE id = $idPAP;";
                            $conn->query($sql);
                        }
                        else if(($retProc == null)&&($idAssit == 0)){

                            $sql = "INSERT INTO
                            processos (
                                numeracao_unica,
                                comarca_id,
                                atuacao_id,
                                valor_causa,
                                processo_pje,
                                verificado_pje,
                                unidade_defensorial_id
                            )
                            VALUES (
                                '".$this->mascara($tableAviso[0][4],"#######-##.####.#.##.####")."',
                                '".$this->IdComarca($tableAviso[0][5])."',
                                '".$idAtc."',
                                '".$tableAviso[0][10]."',
                                '1',
                                '1',
                                '".$idUnidDef."'
                            );";
                            $conn->query($sql);

                            $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
                            $retorno = $conn->query($sql)->fetchAll();
                            $idPAP = $retorno[0][0];

                            $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
                            $retorno = $conn->query($sql)->fetchAll();
                            $idPrc = $retorno[0][0];

                            $sql = "UPDATE pje_aviso_pendentes
                                    SET processo_id = '".$idPrc."'
                                    WHERE id = $idPAP;";
                            $conn->query($sql);
                        }
                    }  
                }
                else if($tamTable > 1){
                    for($i = 0; $i < $tamTable; $i++){
                        $retAviso = $this->existeAviso($tableAviso[$i][1]);
                        $retProc = $this->existeProcesso($tableAviso[$i][4]);
                        $retAtuacao = $this->existeAtuacao($tableAviso[$i][4]);
                        $idAtc = $this->idAtuacao($tableAviso[$i][2]);
                        $idUnidDef = $this->idUnidDefensorial($tableAviso[$i][2]);
            
                         if($idAtc == -1){
                             $sql = "INSERT INTO atuacoes (cod_pje, nome) VALUES ('".$tableAviso[$i][2]."','".$tableAviso[$i][3]."');";
                             $conn->query($sql);
                             $sql = "SELECT id FROM atuacoes ORDER BY id DESC LIMIT 1;";
                             $retorno = $conn->query($sql)->fetchAll();
                             $idAtc = $retorno[0][0];
                         }
                        if($retAviso == 0){
                            $avisoPendente['PjeAvisoPendentes']['perfil_importacao']         = $perfil[$k];
                            $avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']         = $tpPendencia;
                            $avisoPendente['PjeAvisoPendentes']['id_aviso']                  = $tableAviso[$i][1];
                            $avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']        = $tableAviso[$i][2];
                            $avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']  = $this->mascara($tableAviso[$i][4],"#######-##.####.#.##.####");
                            $avisoPendente['PjeAvisoPendentes']['codigoLocalidade']          = $tableAviso[$i][5];
                            $avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']          = $tableAviso[$i][6];
                            $avisoPendente['PjeAvisoPendentes']['destinatario_pje']          = $tableAviso[$i][7];
                            $avisoPendente['PjeAvisoPendentes']['docDestinatario']           = $tableAviso[$i][8];
                            $avisoPendente['PjeAvisoPendentes']['tipoPessoa']                = $tableAviso[$i][9];
                            $avisoPendente['PjeAvisoPendentes']['valor_causa']               = $tableAviso[$i][10];                   
                            $avisoPendente['PjeAvisoPendentes']['prazo']                     = $tableAviso[$i][11];
                            $avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']       = $tableAviso[$i][12];
                            $avisoPendente['PjeAvisoPendentes']['descricao_ato']             = $tableAviso[$i][13];
                            $avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']      = (int)$this->descricao_ato((string)$tableAviso[$i][13]);
                            $avisoPendente['PjeAvisoPendentes']['data_expedicao']            = $tableAviso[$i][14];
                            $avisoPendente['PjeAvisoPendentes']['meio']                      = $tableAviso[$i][15]; 
                            $avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']     = $tableAviso[$i][16];
                            $avisoPendente['PjeAvisoPendentes']['instancia']                 = $tableAviso[$i][17];
                            $avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']     = $tableAviso[$i][18];
                            $avisoPendente['PjeAvisoPendentes']['processo_id']               = $retProc;
            
                            if($retProc != null){
                                $sql = "UPDATE processos
                                        SET valor_causa = '".$tableAviso[$i][10]."', processo_pje = 1, verificado_pje = 1
                                        WHERE id = $retProc;";
                                $conn->query($sql);
        
                                if($retAtuacao == null){
                                    $sql = "UPDATE processos SET atuacao_id = $idAtc WHERE id = $retProc;";
                                    $conn->query($sql);
                                }
                            }
                            
                            $sql = "INSERT INTO
                            pje_aviso_pendentes (
                                perfil_importacao,
                                tipo_pendencia_id,
                                id_aviso,
                                cod_orgao_julgador,
                                processo_numeracao_unica,
                                codigoLocalidade,
                                tipo_comunicacao,
                                destinatario_pje,
                                docDestinatario,
                                nome_sigad,
                                tipoPessoa,
                                valor_causa,
                                prazo,
                                data_limite_ciencia,
                                descricao_ato,
                                pje_descricao_ato_id,
                                data_expedicao,
                                meio,
                                data_disponibilizacao,
                                instancia,
                                codigo_municipio_ibge,
                                processo_id
                            )
                            VALUES (
                                '".$avisoPendente['PjeAvisoPendentes']['perfil_importacao']."', 
                                '".$avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']."',
                                '".$avisoPendente['PjeAvisoPendentes']['id_aviso']."',
                                '".$avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']."',
                                '".$avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']."',
                                '".$avisoPendente['PjeAvisoPendentes']['codigoLocalidade']."',
                                '".$avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']."',
                                '".$avisoPendente['PjeAvisoPendentes']['destinatario_pje']."',
                                '".$avisoPendente['PjeAvisoPendentes']['docDestinatario']."',
                                'nome',
                                '".$avisoPendente['PjeAvisoPendentes']['tipoPessoa']."',
                                '".$avisoPendente['PjeAvisoPendentes']['valor_causa']."',
                                '".$avisoPendente['PjeAvisoPendentes']['prazo']."',
                                '".$avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']."',
                                '".$avisoPendente['PjeAvisoPendentes']['descricao_ato']."',
                                '".$avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']."',
                                '".$avisoPendente['PjeAvisoPendentes']['data_expedicao']."',
                                '".$avisoPendente['PjeAvisoPendentes']['meio']."',
                                '".$avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']."',
                                '".$avisoPendente['PjeAvisoPendentes']['instancia']."',
                                '".$avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']."',
                                '".$avisoPendente['PjeAvisoPendentes']['processo_id']."'
                            );";
                            
                            $conn->query($sql);

                            if(strcmp($tableAviso[$i][8], 'Não informado') != 0){
                                if(strcmp($tableAviso[$i][9], 'fisica') == 0){
                                    $idAssit = $this->existeCPF($tableAviso[$i][8]);
                                }
                                else if(strcmp($tableAviso[$i][9], 'juridica') == 0){
                                    $idAssit = $this->existeCNPJ($tableAviso[$i][8]);
                                }
                            }
                            else{
                                $idAssit = 0; 
                            }
                    
                            if(($retProc == null)&&($idAssit != 0)){
                                $sql = "INSERT INTO
                                processos (
                                    numeracao_unica,
                                    assistido_id,
                                    comarca_id,
                                    atuacao_id,
                                    valor_causa,
                                    processo_pje,
                                    verificado_pje,
                                    unidade_defensorial_id
                                )
                                VALUES (
                                    '".$this->mascara($tableAviso[$i][4],"#######-##.####.#.##.####")."',
                                    '".$idAssit."',
                                    '".$this->IdComarca($tableAviso[$i][5])."',
                                    '".$idAtc."',
                                    '".$tableAviso[$i][10]."',
                                    '1',
                                    '1',
                                    '".$idUnidDef."'
                                );";
                                echo "Perfil = " . $perfil[$k] . "<br><br>";
                                echo "CodPje = " . $tableAviso[$i][2] . "<br><br>";
                                echo "idUnidDef = " . $idUnidDef . "<br><br>";
                                echo $sql;
                                $conn->query($sql);
        
                                $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
                                $retorno = $conn->query($sql)->fetchAll();
                                $idPAP = $retorno[0][0];
        
                                $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
                                $retorno = $conn->query($sql)->fetchAll();
                                $idPrc = $retorno[0][0];
                                
                                $sql = "UPDATE pje_aviso_pendentes
                                        SET processo_id = '".$idPrc."'
                                        WHERE id = $idPAP;";
                                $conn->query($sql);
                            }
                            else if(($retProc == null)&&($idAssit == 0)){

                                $sql = "INSERT INTO
                                processos (
                                    numeracao_unica,
                                    comarca_id,
                                    atuacao_id,
                                    valor_causa,
                                    processo_pje,
                                    verificado_pje,
                                    unidade_defensorial_id
                                )
                                VALUES (
                                    '".$this->mascara($tableAviso[$i][4],"#######-##.####.#.##.####")."',
                                    '".$this->IdComarca($tableAviso[$i][5])."',
                                    '".$idAtc."',
                                    '".$tableAviso[$i][10]."',
                                    '1',
                                    '1',
                                    '".$idUnidDef."'
                                );";

                                echo "Perfil = " . $perfil[$k] . "<br><br>";
                                echo "CodPje = " . $tableAviso[$i][2] . "<br><br>";
                                echo "idUnidDef = " . $idUnidDef . "<br><br>";
                                echo $sql;

                                $conn->query($sql);
        
                                $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
                                $retorno = $conn->query($sql)->fetchAll();
                                $idPAP = $retorno[0][0];
        
                                $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
                                $retorno = $conn->query($sql)->fetchAll();
                                $idPrc = $retorno[0][0];
        
                                $sql = "UPDATE pje_aviso_pendentes
                                        SET processo_id = '".$idPrc."'
                                        WHERE id = $idPAP;";
                                $conn->query($sql);
                            }
                        }  
                    }
                }
            }
        }

        $this->autoRender = false;
    }

    public function expedientesretroativopje(){
        
        ini_set("memory_limit", 99999999999);
        ini_set('max_execution_time', 99999999);
        set_time_limit(999999999);
        Configure::write('debug', 2);

        $tamAvisos = null;
        try {

            $conn       = ConnectionManager::get('sigad');
            $sql_url    = "SELECT * FROM sigad.pje_urls;";      
            $sql        = "SELECT * FROM pje_perfis as pf;";
            $credencial = $conn->query($sql)->fetchAll();
            $consulta   = $conn->query($sql_url)->fetchAll();
            $enderecoUrl =  $consulta[0][2];
            $cookkie    =  $consulta[0][3];
            $perfil     = ["CUR", "NC", "G"];

            for($k = 0; $k < 3; $k++){
                $tamAvisos = 0;

                $sqlId = "SELECT id
                        FROM expedientes_retroativo_pje
                        ORDER BY id DESC
                        LIMIT 1;";
                        
                $queryId = $conn->query($sqlId)->fetchAll();
                $id = (count($queryId) == 0) ? 0 : (int)$queryId[0][0];
                $id = $id + 1;

                $sqlData = "SELECT data_inicio
                        FROM expedientes_retroativo_pje
                        WHERE perfil_importacao = '".$perfil[$k]."'
                        ORDER BY data_inicio DESC
                        LIMIT 1;";
                        
                $queryData = $conn->query($sqlData)->fetchAll();

                if(count($queryData) == 0) {
                    $dataHoraInicial = date('YmdHis', strtotime('-12 hour'));
                } else {
                    $dataHoraInicial = date('YmdHis', strtotime('-12 hour', strtotime($queryData[0][0])));
                }
                // $dataHoraInicial = "20240101004504";

                $this->inserirRegistroExpedienteRetroativo($id, $perfil[$k]);
                
                if($perfil[$k] == "CUR"){
                    $user = $credencial[0][1];
                    $senha = $credencial[0][2];
                } else if($perfil[$k] == "NC"){
                    $user = $credencial[1][1];
                    $senha = $credencial[1][2];
                } else{
                    $user = $credencial[2][1];
                    $senha = $credencial[2][2];
                }

                $response = self::consultaAvisoPendentesApi($user, $senha, $dataHoraInicial, $enderecoUrl, $cookkie);
                $tpPendencia = 1;
                if($response == 500){
                    $enderecoUrl = $consulta[0][4];
                    $response = self::consultaAvisoPendentesApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
                }
                
                if($response != 500 && count($response) > 0){
                    $tam = count($response);

                    if($tam == 1) {
                        $rr = array();
                        array_push($rr, $response);
                    } else {
                        $rr = $response;
                    }

                    $tamAvisos = count($rr);

                    foreach ($rr as $resp) {
                        $sql        = "SELECT pda.id FROM sigad.pje_descricao_atos as pda WHERE pda.nome = '".$resp['PjeIntimacao']['descricao_ato']."'";
                        $retorno    = $conn->query($sql)->fetchAll();
    
                        if (empty($retorno)) {
                            $sqlInsert = "INSERT INTO sigad.pje_descricao_atos (nome) VALUES ('".$resp['PjeIntimacao']['descricao_ato']."');";
                            $conn->query($sqlInsert);
                        }

                        $sql = "INSERT INTO
                            pje_intimacao (
                                id_aviso,
                                cod_orgao_julgador,
                                nome_orgao,
                                processo_numeracao_unica,
                                codigoLocalidade,
                                tipo_comunicacao,
                                destinatario_pje,
                                docDestinatario,
                                tipoPessoa,
                                valor_causa,
                                prazo,
                                data_limite_ciencia,
                                descricao_ato,
                                data_expedicao,
                                meio,
                                data_disponibilizacao,
                                instancia,
                                codigo_municipio_ibge
                            )
                            VALUES (
                                '".$resp['PjeIntimacao']['id_aviso']."', 
                                '".$resp['PjeIntimacao']['cod_orgao_julgador']."',
                                '".addslashes($resp['PjeIntimacao']['nome_orgao'])."',
                                '".$resp['PjeIntimacao']['processo_numeracao_unica']."',
                                '".$resp['PjeIntimacao']['codigoLocalidade']."',
                                '".$resp['PjeIntimacao']['tipo_comunicacao']."',
                                '".addslashes($resp['PjeIntimacao']['destinatario_pje'])."',
                                '".$resp['PjeIntimacao']['docDestinatario']."',
                                '".$resp['PjeIntimacao']['tipoPessoa']."',
                                '".$resp['PjeIntimacao']['valor_causa']."',
                                '".$resp['PjeIntimacao']['prazo']."',
                                '".$resp['PjeIntimacao']['data_limite_ciencia']."',
                                '".$resp['PjeIntimacao']['descricao_ato']."',
                                '".$resp['PjeIntimacao']['data_expedicao']."',
                                '".$resp['PjeIntimacao']['meio']."',
                                '".$resp['PjeIntimacao']['data_disponibilizacao']."',
                                '".$resp['PjeIntimacao']['instancia']."',
                                '".$resp['PjeIntimacao']['codigo_municipio_ibge']."'
                            );";
                        $conn->query($sql);

                    }

                    $sql = "SELECT * FROM pje_intimacao;";
                    $tableAviso = $conn->query($sql)->fetchAll();

                    foreach ($tableAviso as $aviso) {
                        $retAviso = $this->existeAviso($aviso[1]);
                        $retProc = $this->existeProcesso($aviso[4]);
                        $retAtuacao = $this->existeAtuacao($aviso[4]);
                        $idAtc = $this->idAtuacao($aviso[2]);
                        $idUnidDef = $this->idUnidDefensorial($aviso[2]);

                        if($idAtc == -1){
                            $sql = "INSERT INTO atuacoes (cod_pje, nome) VALUES ('".$aviso[2]."','".$aviso[3]."');";
                            $conn->query($sql);
                            $sql = "SELECT id FROM atuacoes ORDER BY id DESC LIMIT 1;";
                            $retorno = $conn->query($sql)->fetchAll();
                            $idAtc = $retorno[0][0];
                        }

                        if($retAviso == 0){
                            $avisoPendente['PjeAvisoPendentes']['perfil_importacao']         = $perfil[$k];
                            $avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']         = $tpPendencia;
                            $avisoPendente['PjeAvisoPendentes']['id_aviso']                  = $aviso[1];
                            $avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']        = $aviso[2];
                            $avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']  = $this->mascara($aviso[4],"#######-##.####.#.##.####");
                            $avisoPendente['PjeAvisoPendentes']['codigoLocalidade']          = $aviso[5];
                            $avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']          = $aviso[6];
                            $avisoPendente['PjeAvisoPendentes']['destinatario_pje']          = $aviso[7];
                            $avisoPendente['PjeAvisoPendentes']['docDestinatario']           = $aviso[8];                        
                            $avisoPendente['PjeAvisoPendentes']['tipoPessoa']                = $aviso[9];
                            $avisoPendente['PjeAvisoPendentes']['valor_causa']               = $aviso[10];                   
                            $avisoPendente['PjeAvisoPendentes']['prazo']                     = $aviso[11];
                            $avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']       = $aviso[12];
                            $avisoPendente['PjeAvisoPendentes']['descricao_ato']             = $aviso[13];
                            $avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']      = (int)$this->descricao_ato((string)$aviso[13]);
                            $avisoPendente['PjeAvisoPendentes']['data_expedicao']            = $aviso[14];
                            $avisoPendente['PjeAvisoPendentes']['meio']                      = $aviso[15]; 
                            $avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']     = $aviso[16];
                            $avisoPendente['PjeAvisoPendentes']['instancia']                 = $aviso[17];
                            $avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']     = $aviso[18];
                            $avisoPendente['PjeAvisoPendentes']['processo_id']               = $retProc;

                            if($retProc != null){
                                $sql = "UPDATE processos
                                        SET valor_causa = '".$aviso[10]."', processo_pje = 1, verificado_pje = 1
                                        WHERE id = $retProc;";
                                $conn->query($sql);

                                if($retAtuacao == null){
                                    $sql = "UPDATE processos SET atuacao_id = $idAtc WHERE id = $retProc;";
                                    $conn->query($sql);
                                }
                            }

                            $sqlAddAviso = "INSERT INTO
                            pje_aviso_pendentes (
                                perfil_importacao,
                                tipo_pendencia_id,
                                id_aviso,
                                cod_orgao_julgador,
                                processo_numeracao_unica,
                                codigoLocalidade,
                                tipo_comunicacao,
                                destinatario_pje,
                                docDestinatario,
                                nome_sigad,
                                tipoPessoa,
                                valor_causa,
                                prazo,
                                data_limite_ciencia,
                                descricao_ato,
                                pje_descricao_ato_id,
                                data_expedicao,
                                meio,
                                data_disponibilizacao,
                                instancia,
                                codigo_municipio_ibge,
                                processo_id
                            )
                            VALUES (
                                '".$avisoPendente['PjeAvisoPendentes']['perfil_importacao']."', 
                                '".$avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']."',
                                '".$avisoPendente['PjeAvisoPendentes']['id_aviso']."',
                                '".$avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']."',
                                '".$avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']."',
                                '".$avisoPendente['PjeAvisoPendentes']['codigoLocalidade']."',
                                '".$avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']."',
                                '".$avisoPendente['PjeAvisoPendentes']['destinatario_pje']."',
                                '".$avisoPendente['PjeAvisoPendentes']['docDestinatario']."',
                                'nome',
                                '".$avisoPendente['PjeAvisoPendentes']['tipoPessoa']."',
                                '".$avisoPendente['PjeAvisoPendentes']['valor_causa']."',
                                '".$avisoPendente['PjeAvisoPendentes']['prazo']."',
                                '".$avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']."',
                                '".$avisoPendente['PjeAvisoPendentes']['descricao_ato']."',
                                '".$avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']."',
                                '".$avisoPendente['PjeAvisoPendentes']['data_expedicao']."',
                                '".$avisoPendente['PjeAvisoPendentes']['meio']."',
                                '".$avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']."',
                                '".$avisoPendente['PjeAvisoPendentes']['instancia']."',
                                '".$avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']."',
                                '".$avisoPendente['PjeAvisoPendentes']['processo_id']."'
                            );";

                            $conn->query($sqlAddAviso);

                            if(strcmp($aviso[8], 'Não informado') != 0){
                                if(strcmp($aviso[9], 'fisica') == 0){
                                    $idAssit = $this->existeCPF($aviso[8]);
                                }
                                else if(strcmp($aviso[9], 'juridica') == 0){
                                    $idAssit = $this->existeCNPJ($aviso[8]);
                                }
                            }
                            else{
                                $idAssit = 0; 
                            }

                            echo "Perfil = " . $perfil[$k] . "<br>";
                            echo "Qtd_avisos = " . $tamAvisos . "<br>";
                            echo "CodPje = " . $aviso[2] . "<br>";
                            echo "idUnidDef = " . $idUnidDef . "<br>";

                            if(($retProc == null)&&($idAssit != 0)){
                                $sqlAddProc = "INSERT INTO
                                processos (
                                    numeracao_unica,
                                    assistido_id,
                                    comarca_id,
                                    atuacao_id,
                                    valor_causa,
                                    processo_pje,
                                    verificado_pje,
                                    unidade_defensorial_id
                                )
                                VALUES (
                                    '".$this->mascara($aviso[4],"#######-##.####.#.##.####")."',
                                    '".$idAssit."',
                                    '".$this->IdComarca($aviso[5])."',
                                    '".$idAtc."',
                                    '".$aviso[10]."',
                                    '1',
                                    '1',
                                    '".$idUnidDef."'
                                );";
                                
                                echo "SQL = '" . $sqlAddProc . "'<br><br>";
                                $conn->query($sqlAddProc);
                            } else if(($retProc == null)&&($idAssit == 0)){

                                $sqlAddProc = "INSERT INTO
                                processos (
                                    numeracao_unica,
                                    comarca_id,
                                    atuacao_id,
                                    valor_causa,
                                    processo_pje,
                                    verificado_pje,
                                    unidade_defensorial_id
                                )
                                VALUES (
                                    '".$this->mascara($aviso[4],"#######-##.####.#.##.####")."',
                                    '".$this->IdComarca($aviso[5])."',
                                    '".$idAtc."',
                                    '".$aviso[10]."',
                                    '1',
                                    '1',
                                    '".$idUnidDef."'
                                );";
                                
                                echo "SQL = '" . $sqlAddProc . "'<br><br>";
                                $conn->query($sqlAddProc);
                            }

                            $sql = "SELECT id FROM processos WHERE REPLACE(REPLACE(numeracao_unica ,'-',''),'.','') = '{$aviso[4]}' LIMIT 1;";
                            $retorno = $conn->query($sql)->fetchAll();
                            $idPrc = $retorno[0][0];

                            $sql = "UPDATE pje_aviso_pendentes
                                    SET processo_id = '".$idPrc."'
                                    WHERE id_aviso = {$avisoPendente['PjeAvisoPendentes']['id_aviso']};";
                            $conn->query($sql);
                        }
                    }
                    
                }

                $sucesso = ($response == null || $response == 500) ? 0 : 1;
                $qtd_aviso = ($response == null || $response == 500) ? 0 : $tamAvisos;
        
                $this->atualizarRegistroExpedienteRetroativo($id, $sucesso, $qtd_aviso);
            }
            
        } catch (\Exception $ex) {
            $this->atualizarRegistroExpedienteRetroativo($id, 0, $tamAvisos);
            echo $ex->getMessage() . "<br><br>";
            Log::error($ex->getMessage());
        }


        $this->autoRender = false;
    }

    public function inserirRegistroExpedienteRetroativo($id, $perfil) {
        $conn = ConnectionManager::get('sigad');
        
        $sql = "INSERT INTO
                expedientes_retroativo_pje (
                    id,
                    perfil_importacao
                )
                VALUES (
                    ".$id.",
                    '".$perfil."'
                );";
                
        $conn->query($sql);

    }

    public function atualizarRegistroExpedienteRetroativo($id, $sucesso, $qtd_aviso) {
        $conn = ConnectionManager::get('sigad');
        $sql = "UPDATE expedientes_retroativo_pje SET data_fim = CURRENT_TIMESTAMP(), sucesso = {$sucesso}, qtd_aviso = {$qtd_aviso} WHERE id = {$id}";
        $conn->query($sql);
    }

    // public function expedientesretroativopje(){
        
    //     $conn       = ConnectionManager::get('sigad');
    //     $sql_url    = "SELECT * FROM sigad.pje_urls;";      
    //     $sql        = "SELECT * FROM pje_perfis as pf;";
    //     $credencial = $conn->query($sql)->fetchAll();
    //     $consulta   = $conn->query($sql_url)->fetchAll();
    //     $enderecoUrl =  $consulta[0][2];
    //     $cookkie    =  $consulta[0][3];
    //     $perfil     = ["CUR", "NC", "G"];

    //     // Obtém a data atual
    //     //$hoje = new DateTime();
    //     // Subtrai um dia
    //     //$hoje->sub(new DateInterval('P1D'));
    //     // Define a hora como meia-noite
    //     //$hoje->setTime(0, 0, 0);
    //     // Formate a data como uma string, se necessário
    //     //$dataHoraInicial = $hoje->format('YmdHis');
    //     $dataHoraInicial = "20231031000000";

    //     for($k = 0; $k < 3; $k++)
    //     {
    //         if($perfil[$k] == "CUR"){
    //             $res = self::consultaAvisoPendentesRetroativoApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
    //             $tpPendencia = 1;
    //             if($res == 500){
    //                 $enderecoUrl = $consulta[0][4];
    //                 $res = self::consultaAvisoPendentesRetroativoApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
    //             }
    //         }
    //         else if($perfil[$k] == "NC"){
    //             $res = self::consultaAvisoPendentesRetroativoApi($credencial[1][1], $credencial[1][2], $dataHoraInicial, $enderecoUrl, $cookkie);
    //             $tpPendencia = 1;
    //             if($res == 500){
    //                 $enderecoUrl = $consulta[0][4];
    //                 $res = self::consultaAvisoPendentesRetroativoApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
    //             }     
    //         }
    //         else{
    //             $res = self::consultaAvisoPendentesRetroativoApi($credencial[2][1], $credencial[2][2], $dataHoraInicial, $enderecoUrl, $cookkie);
    //             $tpPendencia = 1;
    //             if($res == 500){
    //                 $enderecoUrl = $consulta[0][4];
    //                 $res = self::consultaAvisoPendentesRetroativoApi($credencial[0][1], $credencial[0][2], $dataHoraInicial, $enderecoUrl, $cookkie);
    //             }
    //         }
    //         if($res != 500){
    //             $tam = count($res);
    //             if($tam == 1)
    //             {
    //                 // $sql        = "SELECT pda.id FROM sigad.pje_descricao_atos as pda WHERE pda.nome = '".$res['PjeIntimacao']['descricao_ato']."'";
    //                 // $retorno    = $conn->query($sql)->fetchAll();

    //                 // if (empty($retorno))
    //                 // {
    //                 //     $sqlInsert = "INSERT INTO sigad.pje_descricao_atos (nome) VALUES ('".$res['PjeIntimacao']['descricao_ato']."');";
    //                 //     $conn->query($sqlInsert);
    //                 // }                    
    
    //                 $sql = "INSERT INTO
    //                 pje_sincroniza_expedientes (
    //                     id_aviso,
    //                     cod_orgao_julgador,
    //                     nome_orgao,
    //                     processo_numeracao_unica,
    //                     codigoLocalidade,
    //                     tipo_comunicacao,
    //                     destinatario_pje,
    //                     docDestinatario,
    //                     tipoPessoa,
    //                     valor_causa,
    //                     prazo,
    //                     data_limite_ciencia,
    //                     descricao_ato,
    //                     data_expedicao,
    //                     meio,
    //                     data_disponibilizacao,
    //                     instancia,
    //                     codigo_municipio_ibge
    //                 )
    //                 VALUES (
    //                     '".$res['PjeIntimacao']['id_aviso']."', 
    //                     '".$res['PjeIntimacao']['cod_orgao_julgador']."',
    //                     '".addslashes($res['PjeIntimacao']['nome_orgao'])."',
    //                     '".$res['PjeIntimacao']['processo_numeracao_unica']."',
    //                     '".$res['PjeIntimacao']['codigoLocalidade']."',
    //                     '".$res['PjeIntimacao']['tipo_comunicacao']."',
    //                     '".addslashes($res['PjeIntimacao']['destinatario_pje'])."',
    //                     '".$res['PjeIntimacao']['docDestinatario']."',
    //                     '".$res['PjeIntimacao']['tipoPessoa']."',
    //                     '".$res['PjeIntimacao']['valor_causa']."',
    //                     '".$res['PjeIntimacao']['prazo']."',
    //                     '".$res['PjeIntimacao']['data_limite_ciencia']."',
    //                     '".$res['PjeIntimacao']['descricao_ato']."',
    //                     '".$res['PjeIntimacao']['data_expedicao']."',
    //                     '".$res['PjeIntimacao']['meio']."',
    //                     '".$res['PjeIntimacao']['data_disponibilizacao']."',
    //                     '".$res['PjeIntimacao']['instancia']."',
    //                     '".$res['PjeIntimacao']['codigo_municipio_ibge']."'
    //                 );";
    //                 $conn->query($sql);
    //             }
    //             else{
    //                 for($j = 0; $j < $tam; $j++){
    //                     // $sql        = "SELECT pda.id FROM pje_descricao_atos as pda WHERE pda.nome = '".$res[$j]['PjeIntimacao']['descricao_ato']."'";
    //                     // $retorno    = $conn->query($sql)->fetchAll();

    //                     // if (empty($retorno))
    //                     // {
    //                     //     $sqlInsert = "INSERT INTO pje_descricao_atos (nome) VALUES ('".$res[$j]['PjeIntimacao']['descricao_ato']."');";
    //                     //     $conn->query($sqlInsert);
    //                     // }

    //                     $sql = "INSERT INTO
    //                     pje_sincroniza_expedientes (
    //                         id_aviso,
    //                         cod_orgao_julgador,
    //                         nome_orgao,
    //                         processo_numeracao_unica,
    //                         codigoLocalidade,
    //                         tipo_comunicacao,
    //                         destinatario_pje,
    //                         docDestinatario,
    //                         tipoPessoa,
    //                         valor_causa,
    //                         prazo,
    //                         data_limite_ciencia,
    //                         descricao_ato,
    //                         data_expedicao,
    //                         meio,
    //                         data_disponibilizacao,
    //                         instancia,
    //                         codigo_municipio_ibge
    //                     )
    //                     VALUES (
    //                         '".$res[$j]['PjeIntimacao']['id_aviso']."', 
    //                         '".$res[$j]['PjeIntimacao']['cod_orgao_julgador']."',
    //                         '".addslashes($res[$j]['PjeIntimacao']['nome_orgao'])."',
    //                         '".$res[$j]['PjeIntimacao']['processo_numeracao_unica']."',
    //                         '".$res[$j]['PjeIntimacao']['codigoLocalidade']."',
    //                         '".$res[$j]['PjeIntimacao']['tipo_comunicacao']."',
    //                         '".addslashes($res[$j]['PjeIntimacao']['destinatario_pje'])."',
    //                         '".$res[$j]['PjeIntimacao']['docDestinatario']."',
    //                         '".$res[$j]['PjeIntimacao']['tipoPessoa']."',
    //                         '".$res[$j]['PjeIntimacao']['valor_causa']."',
    //                         '".$res[$j]['PjeIntimacao']['prazo']."',
    //                         '".$res[$j]['PjeIntimacao']['data_limite_ciencia']."',
    //                         '".$res[$j]['PjeIntimacao']['descricao_ato']."',
    //                         '".$res[$j]['PjeIntimacao']['data_expedicao']."',
    //                         '".$res[$j]['PjeIntimacao']['meio']."',
    //                         '".$res[$j]['PjeIntimacao']['data_disponibilizacao']."',
    //                         '".$res[$j]['PjeIntimacao']['instancia']."',
    //                         '".$res[$j]['PjeIntimacao']['codigo_municipio_ibge']."'
    //                     );";
    //                     $conn->query($sql);
    //                 }
    //             }

    //             $sql = "SELECT * FROM pje_sincroniza_expedientes;";
    //             $tableAviso = $conn->query($sql)->fetchAll();
    //             $tamTable = count($tableAviso);
            
    //             if($tamTable == 1){
    //                 $retAviso = $this->existeAviso($tableAviso[0][1]);
    //                 // $retProc = $this->existeProcesso($tableAviso[0][4]);
    //                 // $retAtuacao = $this->existeAtuacao($tableAviso[0][4]);
    //                 // $idAtc = $this->idAtuacao($tableAviso[0][2]);
    //                 // $idUnidDef = $this->idUnidDefensorial($tableAviso[0][2]);

    //                 // if($idAtc == -1){
    //                 //      $sql = "INSERT INTO atuacoes (cod_pje, nome) VALUES ('".$tableAviso[0][2]."','".$tableAviso[0][3]."');";
    //                 //      $conn->query($sql);
    //                 //      $sql = "SELECT id FROM atuacoes ORDER BY id DESC LIMIT 1;";
    //                 //      $retorno = $conn->query($sql)->fetchAll();
    //                 //      $idAtc = $retorno[0][0];
    //                 //  }
    //                 if($retAviso == 1){
    //                     $sql = "UPDATE pje_sincroniza_expedientes
    //                         SET aviso_sincronizado = '1'
    //                             WHERE id_aviso = ".$tableAviso[0][1].";";
    //                     $conn->query($sql);
    //                     // $avisoPendente['PjeAvisoPendentes']['perfil_importacao']         = $perfil[$k];
    //                     // $avisoPendente['PjeAvisoPendentes']['perfil_importacao']         = "G";
    //                     // $avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']         = $tpPendencia;
    //                     // $avisoPendente['PjeAvisoPendentes']['id_aviso']                  = $tableAviso[0][1];
    //                     // $avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']        = $tableAviso[0][2];
    //                     // $avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']  = $this->mascara($tableAviso[0][4],"#######-##.####.#.##.####");
    //                     // $avisoPendente['PjeAvisoPendentes']['codigoLocalidade']          = $tableAviso[0][5];
    //                     // $avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']          = $tableAviso[0][6];
    //                     // $avisoPendente['PjeAvisoPendentes']['destinatario_pje']          = $tableAviso[0][7];
    //                     // $avisoPendente['PjeAvisoPendentes']['docDestinatario']           = $tableAviso[0][8];                        
    //                     // $avisoPendente['PjeAvisoPendentes']['tipoPessoa']                = $tableAviso[0][9];
    //                     // $avisoPendente['PjeAvisoPendentes']['valor_causa']               = $tableAviso[0][10];                   
    //                     // $avisoPendente['PjeAvisoPendentes']['prazo']                     = $tableAviso[0][11];
    //                     // $avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']       = $tableAviso[0][12];
    //                     // $avisoPendente['PjeAvisoPendentes']['descricao_ato']             = $tableAviso[0][13];
    //                     // $avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']      = (int)$this->descricao_ato((string)$tableAviso[0][13]);
    //                     // $avisoPendente['PjeAvisoPendentes']['data_expedicao']            = $tableAviso[0][14];
    //                     // $avisoPendente['PjeAvisoPendentes']['meio']                      = $tableAviso[0][15]; 
    //                     // $avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']     = $tableAviso[0][16];
    //                     // $avisoPendente['PjeAvisoPendentes']['instancia']                 = $tableAviso[0][17];
    //                     // $avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']     = $tableAviso[0][18];
    //                     // $avisoPendente['PjeAvisoPendentes']['processo_id']               = $retProc;

    //                     // if($retProc != null){
    //                     //     $sql = "UPDATE processos
    //                     //             SET valor_causa = '".$tableAviso[0][10]."', processo_pje = 1, verificado_pje = 1
    //                     //             WHERE id = $retProc;";
    //                     //     $conn->query($sql);

    //                     //     if($retAtuacao == null){
    //                     //         $sql = "UPDATE processos SET atuacao_id = $idAtc WHERE id = $retProc;";
    //                     //         $conn->query($sql);
    //                     //     }
    //                     // }

    //                     // $sql = "INSERT INTO
    //                     // pje_aviso_pendentes (
    //                     //     perfil_importacao,
    //                     //     tipo_pendencia_id,
    //                     //     id_aviso,
    //                     //     cod_orgao_julgador,
    //                     //     processo_numeracao_unica,
    //                     //     codigoLocalidade,
    //                     //     tipo_comunicacao,
    //                     //     destinatario_pje,
    //                     //     docDestinatario,
    //                     //     nome_sigad,
    //                     //     tipoPessoa,
    //                     //     valor_causa,
    //                     //     prazo,
    //                     //     data_limite_ciencia,
    //                     //     descricao_ato,
    //                     //     pje_descricao_ato_id,
    //                     //     data_expedicao,
    //                     //     meio,
    //                     //     data_disponibilizacao,
    //                     //     instancia,
    //                     //     codigo_municipio_ibge,
    //                     //     processo_id
    //                     // )
    //                     // VALUES (
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['perfil_importacao']."', 
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['id_aviso']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['codigoLocalidade']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['destinatario_pje']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['docDestinatario']."',
    //                     //     'nome',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['tipoPessoa']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['valor_causa']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['prazo']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['descricao_ato']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['data_expedicao']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['meio']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['instancia']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']."',
    //                     //     '".$avisoPendente['PjeAvisoPendentes']['processo_id']."'
    //                     // );";

    //                     // $conn->query($sql);

    //                     // if(strcmp($tableAviso[0][8], 'Não informado') != 0){
    //                     //     if(strcmp($tableAviso[0][9], 'fisica') == 0){
    //                     //         $idAssit = $this->existeCPF($tableAviso[0][8]);
    //                     //     }
    //                     //     else if(strcmp($tableAviso[0][9], 'juridica') == 0){
    //                     //         $idAssit = $this->existeCNPJ($tableAviso[0][8]);
    //                     //     }
    //                     // }
    //                     // else{
    //                     //     $idAssit = 0; 
    //                     // }

    //                     // if(($retProc == null)&&($idAssit != 0)){

    //                     //     $sql = "INSERT INTO
    //                     //     processos (
    //                     //         numeracao_unica,
    //                     //         assistido_id,
    //                     //         comarca_id,
    //                     //         atuacao_id,
    //                     //         valor_causa,
    //                     //         processo_pje,
    //                     //         verificado_pje,
    //                     //         unidade_defensorial_id
    //                     //     )
    //                     //     VALUES (
    //                     //         '".$this->mascara($tableAviso[0][4],"#######-##.####.#.##.####")."',
    //                     //         '".$idAssit."',
    //                     //         '".$this->IdComarca($tableAviso[0][5])."',
    //                     //         '".$idAtc."',
    //                     //         '".$tableAviso[0][10]."',
    //                     //         '1',
    //                     //         '1',
    //                     //         '".$idUnidDef."'
    //                     //     );";
    //                     //     $conn->query($sql);

    //                     //     $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
    //                     //     $retorno = $conn->query($sql)->fetchAll();
    //                     //     $idPAP = $retorno[0][0];

    //                     //     $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
    //                     //     $retorno = $conn->query($sql)->fetchAll();
    //                     //     $idPrc = $retorno[0][0];

    //                     //     $sql = "UPDATE pje_aviso_pendentes
    //                     //             SET processo_id = '".$idPrc."'
    //                     //             WHERE id = $idPAP;";
    //                     //     $conn->query($sql);
    //                     // }
    //                     // else if(($retProc == null)&&($idAssit == 0)){

    //                     //     $sql = "INSERT INTO
    //                     //     processos (
    //                     //         numeracao_unica,
    //                     //         comarca_id,
    //                     //         atuacao_id,
    //                     //         valor_causa,
    //                     //         processo_pje,
    //                     //         verificado_pje,
    //                     //         unidade_defensorial_id
    //                     //     )
    //                     //     VALUES (
    //                     //         '".$this->mascara($tableAviso[0][4],"#######-##.####.#.##.####")."',
    //                     //         '".$this->IdComarca($tableAviso[0][5])."',
    //                     //         '".$idAtc."',
    //                     //         '".$tableAviso[0][10]."',
    //                     //         '1',
    //                     //         '1',
    //                     //         '".$idUnidDef."'
    //                     //     );";
    //                     //     $conn->query($sql);

    //                     //     $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
    //                     //     $retorno = $conn->query($sql)->fetchAll();
    //                     //     $idPAP = $retorno[0][0];

    //                     //     $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
    //                     //     $retorno = $conn->query($sql)->fetchAll();
    //                     //     $idPrc = $retorno[0][0];

    //                     //     $sql = "UPDATE pje_aviso_pendentes
    //                     //             SET processo_id = '".$idPrc."'
    //                     //             WHERE id = $idPAP;";
    //                     //     $conn->query($sql);
    //                     // }
    //                 }  
    //             }
    //             else if($tamTable > 1){
    //                 for($i = 0; $i < $tamTable; $i++){
    //                     $retAviso = $this->existeAviso($tableAviso[$i][1]);
    //                     // $retProc = $this->existeProcesso($tableAviso[$i][4]);
    //                     // $retAtuacao = $this->existeAtuacao($tableAviso[$i][4]);
    //                     // $idAtc = $this->idAtuacao($tableAviso[$i][2]);
    //                     // $idUnidDef = $this->idUnidDefensorial($tableAviso[$i][2]);
            
    //                     //  if($idAtc == -1){
    //                     //      $sql = "INSERT INTO atuacoes (cod_pje, nome) VALUES ('".$tableAviso[$i][2]."','".$tableAviso[$i][3]."');";
    //                     //      $conn->query($sql);
    //                     //      $sql = "SELECT id FROM atuacoes ORDER BY id DESC LIMIT 1;";
    //                     //      $retorno = $conn->query($sql)->fetchAll();
    //                     //      $idAtc = $retorno[0][0];
    //                     //  }
    //                     if($retAviso == 1){
    //                         $sql = "UPDATE pje_sincroniza_expedientes
    //                         SET aviso_sincronizado = '1'
    //                             WHERE id_aviso = ".$tableAviso[0][1].";";
    //                         $conn->query($sql);
    //                         //$avisoPendente['PjeAvisoPendentes']['perfil_importacao']         = $perfil[$k];
    //                         // $avisoPendente['PjeAvisoPendentes']['perfil_importacao']         = 'G';
    //                         // $avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']         = $tpPendencia;
    //                         // $avisoPendente['PjeAvisoPendentes']['id_aviso']                  = $tableAviso[$i][1];
    //                         // $avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']        = $tableAviso[$i][2];
    //                         // $avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']  = $this->mascara($tableAviso[$i][4],"#######-##.####.#.##.####");
    //                         // $avisoPendente['PjeAvisoPendentes']['codigoLocalidade']          = $tableAviso[$i][5];
    //                         // $avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']          = $tableAviso[$i][6];
    //                         // $avisoPendente['PjeAvisoPendentes']['destinatario_pje']          = $tableAviso[$i][7];
    //                         // $avisoPendente['PjeAvisoPendentes']['docDestinatario']           = $tableAviso[$i][8];
    //                         // $avisoPendente['PjeAvisoPendentes']['tipoPessoa']                = $tableAviso[$i][9];
    //                         // $avisoPendente['PjeAvisoPendentes']['valor_causa']               = $tableAviso[$i][10];                   
    //                         // $avisoPendente['PjeAvisoPendentes']['prazo']                     = $tableAviso[$i][11];
    //                         // $avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']       = $tableAviso[$i][12];
    //                         // $avisoPendente['PjeAvisoPendentes']['descricao_ato']             = $tableAviso[$i][13];
    //                         // $avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']      = (int)$this->descricao_ato((string)$tableAviso[$i][13]);
    //                         // $avisoPendente['PjeAvisoPendentes']['data_expedicao']            = $tableAviso[$i][14];
    //                         // $avisoPendente['PjeAvisoPendentes']['meio']                      = $tableAviso[$i][15]; 
    //                         // $avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']     = $tableAviso[$i][16];
    //                         // $avisoPendente['PjeAvisoPendentes']['instancia']                 = $tableAviso[$i][17];
    //                         // $avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']     = $tableAviso[$i][18];
    //                         // $avisoPendente['PjeAvisoPendentes']['processo_id']               = $retProc;
            
    //                         // if($retProc != null){
    //                         //     $sql = "UPDATE processos
    //                         //             SET valor_causa = '".$tableAviso[$i][10]."', processo_pje = 1, verificado_pje = 1
    //                         //             WHERE id = $retProc;";
    //                         //     $conn->query($sql);
        
    //                         //     if($retAtuacao == null){
    //                         //         $sql = "UPDATE processos SET atuacao_id = $idAtc WHERE id = $retProc;";
    //                         //         $conn->query($sql);
    //                         //     }
    //                         // }
                            
    //                         // $sql = "INSERT INTO
    //                         // pje_aviso_pendentes (
    //                         //     perfil_importacao,
    //                         //     tipo_pendencia_id,
    //                         //     id_aviso,
    //                         //     cod_orgao_julgador,
    //                         //     processo_numeracao_unica,
    //                         //     codigoLocalidade,
    //                         //     tipo_comunicacao,
    //                         //     destinatario_pje,
    //                         //     docDestinatario,
    //                         //     nome_sigad,
    //                         //     tipoPessoa,
    //                         //     valor_causa,
    //                         //     prazo,
    //                         //     data_limite_ciencia,
    //                         //     descricao_ato,
    //                         //     pje_descricao_ato_id,
    //                         //     data_expedicao,
    //                         //     meio,
    //                         //     data_disponibilizacao,
    //                         //     instancia,
    //                         //     codigo_municipio_ibge,
    //                         //     processo_id
    //                         // )
    //                         // VALUES (
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['perfil_importacao']."', 
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['tipo_pendencia_id']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['id_aviso']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['cod_orgao_julgador']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['processo_numeracao_unica']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['codigoLocalidade']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['tipo_comunicacao']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['destinatario_pje']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['docDestinatario']."',
    //                         //     'nome',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['tipoPessoa']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['valor_causa']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['prazo']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['data_limite_ciencia']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['descricao_ato']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['pje_descricao_ato_id']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['data_expedicao']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['meio']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['data_disponibilizacao']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['instancia']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['codigo_municipio_ibge']."',
    //                         //     '".$avisoPendente['PjeAvisoPendentes']['processo_id']."'
    //                         // );";
                            
    //                         // $conn->query($sql);

    //                         // if(strcmp($tableAviso[$i][8], 'Não informado') != 0){
    //                         //     if(strcmp($tableAviso[$i][9], 'fisica') == 0){
    //                         //         $idAssit = $this->existeCPF($tableAviso[$i][8]);
    //                         //     }
    //                         //     else if(strcmp($tableAviso[$i][9], 'juridica') == 0){
    //                         //         $idAssit = $this->existeCNPJ($tableAviso[$i][8]);
    //                         //     }
    //                         // }
    //                         // else{
    //                         //     $idAssit = 0; 
    //                         // }
                    
    //                         // if(($retProc == null)&&($idAssit != 0)){
    //                         //     $sql = "INSERT INTO
    //                         //     processos (
    //                         //         numeracao_unica,
    //                         //         assistido_id,
    //                         //         comarca_id,
    //                         //         atuacao_id,
    //                         //         valor_causa,
    //                         //         processo_pje,
    //                         //         verificado_pje,
    //                         //         unidade_defensorial_id
    //                         //     )
    //                         //     VALUES (
    //                         //         '".$this->mascara($tableAviso[$i][4],"#######-##.####.#.##.####")."',
    //                         //         '".$idAssit."',
    //                         //         '".$this->IdComarca($tableAviso[$i][5])."',
    //                         //         '".$idAtc."',
    //                         //         '".$tableAviso[$i][10]."',
    //                         //         '1',
    //                         //         '1',
    //                         //         '".$idUnidDef."'
    //                         //     );";
    //                         //     $conn->query($sql);
        
    //                         //     $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
    //                         //     $retorno = $conn->query($sql)->fetchAll();
    //                         //     $idPAP = $retorno[0][0];
        
    //                         //     $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
    //                         //     $retorno = $conn->query($sql)->fetchAll();
    //                         //     $idPrc = $retorno[0][0];
                                
    //                         //     $sql = "UPDATE pje_aviso_pendentes
    //                         //             SET processo_id = '".$idPrc."'
    //                         //             WHERE id = $idPAP;";
    //                         //     $conn->query($sql);
    //                         // }
    //                         // else if(($retProc == null)&&($idAssit == 0)){

    //                         //     $sql = "INSERT INTO
    //                         //     processos (
    //                         //         numeracao_unica,
    //                         //         comarca_id,
    //                         //         atuacao_id,
    //                         //         valor_causa,
    //                         //         processo_pje,
    //                         //         verificado_pje,
    //                         //         unidade_defensorial_id
    //                         //     )
    //                         //     VALUES (
    //                         //         '".$this->mascara($tableAviso[$i][4],"#######-##.####.#.##.####")."',
    //                         //         '".$this->IdComarca($tableAviso[$i][5])."',
    //                         //         '".$idAtc."',
    //                         //         '".$tableAviso[$i][10]."',
    //                         //         '1',
    //                         //         '1',
    //                         //         '".$idUnidDef."'
    //                         //     );";
    //                         //     $conn->query($sql);
        
    //                         //     $sql = "SELECT id FROM pje_aviso_pendentes ORDER BY id DESC LIMIT 1;";
    //                         //     $retorno = $conn->query($sql)->fetchAll();
    //                         //     $idPAP = $retorno[0][0];
        
    //                         //     $sql = "SELECT id FROM processos ORDER BY id DESC LIMIT 1;";
    //                         //     $retorno = $conn->query($sql)->fetchAll();
    //                         //     $idPrc = $retorno[0][0];
        
    //                         //     $sql = "UPDATE pje_aviso_pendentes
    //                         //             SET processo_id = '".$idPrc."'
    //                         //             WHERE id = $idPAP;";
    //                         //     $conn->query($sql);
    //                         // }
    //                     }  
    //                 }
    //             }
    //         }
    //     }

    //     $this->autoRender = false;
    // }

    private function consultaAvisoPendentesApi($user, $senha, $dataHora, $enderecoUrl, $cookkie){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $enderecoUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://www.cnj.jus.br/servico-intercomunicacao-2.2.2/" xmlns:tip="http://www.cnj.jus.br/tipos-servico-intercomunicacao-2.2.2">
                    <soapenv:Header/>
                    <soapenv:Body>
                    <ser:consultarAvisosPendentes>
                        <tip:idConsultante>'.$user.'</tip:idConsultante>
                        <tip:senhaConsultante>'.$senha.'</tip:senhaConsultante>
                        <tip:dataReferencia>'.$dataHora.'</tip:dataReferencia>
                    </ser:consultarAvisosPendentes>
                    </soapenv:Body>
                </soapenv:Envelope>',
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/xml',
            'Cookie: '.$cookkie.''
            ),
        ));
        
        $response = curl_exec($curl);
        $codeHTTP = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if($codeHTTP == 200){
            $xml = explode("\n", $response);
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", (string)$xml[5]);
            $xml = new \SimpleXMLElement(utf8_encode($response));
            $res = $xml->xpath('//soapBody');

            $conn = ConnectionManager::get('sigad');
            $conn->query("TRUNCATE TABLE pje_intimacao");
            
            if(!isset($res[0]->ns4consultarAvisosPendentesResposta->aviso)){
                $tam = 0;
            }
            else if(!isset($res[0]->ns4consultarAvisosPendentesResposta->aviso[0])){
                $tam = 1;
            }
            else{
                $tam = count($res[0]->ns4consultarAvisosPendentesResposta->aviso);
            }
            if($tam == 1){
                $avisoPendente['PjeIntimacao']['id_aviso']                  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso['idAviso'];
                $avisoPendente['PjeIntimacao']['cod_orgao_julgador']        = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['codigoOrgao'];
                $avisoPendente['PjeIntimacao']['nome_orgao']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['nomeOrgao'];
                $avisoPendente['PjeIntimacao']['processo_numeracao_unica']  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo['numero'];
                $avisoPendente['PjeIntimacao']['codigoLocalidade']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo['codigoLocalidade'];
                $avisoPendente['PjeIntimacao']['tipo_comunicacao']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso['tipoComunicacao'];
                $avisoPendente['PjeIntimacao']['destinatario_pje']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['nome'];
                
                if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'])){
                    $avisoPendente['PjeIntimacao']['docDestinatario']       = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'];
                }
                else $avisoPendente['PjeIntimacao']['docDestinatario']      = "Não informado";
                $avisoPendente['PjeIntimacao']['tipoPessoa']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['tipoPessoa'];

                if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2valorCausa)){
                    $avisoPendente['PjeIntimacao']['valor_causa']           = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2valorCausa;
                }
                else $avisoPendente['PjeIntimacao']['valor_causa']          = "Não informado";
                
                $vetor = [];
                $tamParam = count($res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2outroParametro);
                for($j = 0; $j < $tamParam; $j++) $vetor[(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2outroParametro[$j]['nome']] = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2outroParametro[$j]['valor'];
                
                $avisoPendente['PjeIntimacao']['prazo']                     = $vetor['mni:pje:expedientePrazo'];

                if(isset($vetor['mni:pje:expedienteDataLimiteCiencia'])){
                    $avisoPendente['PjeIntimacao']['data_limite_ciencia']   = $this->mascara("".$vetor['mni:pje:expedienteDataLimiteCiencia']."", "####-##-## ##:##:##");
                }
                else $avisoPendente['PjeIntimacao']['data_limite_ciencia']  = "Não informado";
                
                $avisoPendente['PjeIntimacao']['descricao_ato']             = $vetor['mni:pje:expedienteDescricaoAto'];
                $avisoPendente['PjeIntimacao']['data_expedicao']            = $this->mascara("".$vetor['mni:pje:expedienteDataExpedicao']."", "####-##-## ##:##:##");
                $avisoPendente['PjeIntimacao']['meio']                      = $vetor['mni:pje:expedienteMeio']; 
                $avisoPendente['PjeIntimacao']['data_disponibilizacao']     = $this->mascara("".(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2dataDisponibilizacao."", "####-##-## ##:##:##");
                $avisoPendente['PjeIntimacao']['instancia']                 = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['instancia'];
                $avisoPendente['PjeIntimacao']['codigo_municipio_ibge']     = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['codigoMunicipioIBGE'];
                
                return $avisoPendente;   
            }
            else if ($tam > 1){
                for($i = 0; $i < $tam; $i++){
                    $avisoPendente[$i]['PjeIntimacao']['id_aviso']                  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]['idAviso'];
                    $avisoPendente[$i]['PjeIntimacao']['cod_orgao_julgador']        = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['codigoOrgao'];
                    $avisoPendente[$i]['PjeIntimacao']['nome_orgao']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['nomeOrgao'];
                    $avisoPendente[$i]['PjeIntimacao']['processo_numeracao_unica']  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo['numero'];
                    $avisoPendente[$i]['PjeIntimacao']['codigoLocalidade']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo['codigoLocalidade'];
                    $avisoPendente[$i]['PjeIntimacao']['tipo_comunicacao']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]['tipoComunicacao'];
                    $avisoPendente[$i]['PjeIntimacao']['destinatario_pje']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['nome']; 
                    
                    if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'])){
                        $avisoPendente[$i]['PjeIntimacao']['docDestinatario']       = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'];
                    }       
                    else $avisoPendente[$i]['PjeIntimacao']['docDestinatario']      = "Não informado";
                    $avisoPendente[$i]['PjeIntimacao']['tipoPessoa']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['tipoPessoa'];

                    if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2valorCausa)){
                        $avisoPendente[$i]['PjeIntimacao']['valor_causa']           = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2valorCausa;
                    }
                    else $avisoPendente[$i]['PjeIntimacao']['valor_causa']          = "Não informado";
                
                    $vetor = [];
                    $tamParam = count($res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2outroParametro);
                    for($j = 0; $j < $tamParam; $j++) $vetor[$i][(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2outroParametro[$j]['nome']] = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2outroParametro[$j]['valor'];
                    
                    $avisoPendente[$i]['PjeIntimacao']['prazo']                     = $vetor[$i]['mni:pje:expedientePrazo'];

                    if(isset($vetor[$i]['mni:pje:expedienteDataLimiteCiencia'])){
                        $avisoPendente[$i]['PjeIntimacao']['data_limite_ciencia']   = $this->mascara("".$vetor[$i]['mni:pje:expedienteDataLimiteCiencia']."", "####-##-## ##:##:##");
                    }
                    else $avisoPendente[$i]['PjeIntimacao']['data_limite_ciencia']  = "Não informado";
                    
                    $avisoPendente[$i]['PjeIntimacao']['descricao_ato']             = $vetor[$i]['mni:pje:expedienteDescricaoAto'];
                    $avisoPendente[$i]['PjeIntimacao']['data_expedicao']            = $this->mascara("".$vetor[$i]['mni:pje:expedienteDataExpedicao']."", "####-##-## ##:##:##");
                    $avisoPendente[$i]['PjeIntimacao']['meio']                      = $vetor[$i]['mni:pje:expedienteMeio']; 
                    $avisoPendente[$i]['PjeIntimacao']['data_disponibilizacao']     = $this->mascara("".(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2dataDisponibilizacao."", "####-##-## ##:##:##");
                    $avisoPendente[$i]['PjeIntimacao']['instancia']                 = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['instancia'];
                    $avisoPendente[$i]['PjeIntimacao']['codigo_municipio_ibge']     = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['codigoMunicipioIBGE'];                
                }
                return $avisoPendente;
            }                            
        }
        else{
            return 500;
        }   
    }

    private function consultaAvisoPendentesRetroativoApi($user, $senha, $dataHora, $enderecoUrl, $cookkie){
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => $enderecoUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'
                <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://www.cnj.jus.br/servico-intercomunicacao-2.2.2/" xmlns:tip="http://www.cnj.jus.br/tipos-servico-intercomunicacao-2.2.2">
                    <soapenv:Header/>
                    <soapenv:Body>
                    <ser:consultarAvisosPendentes>
                        <tip:idConsultante>'.$user.'</tip:idConsultante>
                        <tip:senhaConsultante>'.$senha.'</tip:senhaConsultante>
                        <tip:dataReferencia>'.$dataHora.'</tip:dataReferencia>
                    </ser:consultarAvisosPendentes>
                    </soapenv:Body>
                </soapenv:Envelope>',
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/xml',
            'Cookie: '.$cookkie.''
            ),
        ));
        
        $response = curl_exec($curl);
        $codeHTTP = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if($codeHTTP == 200){
            $xml = explode("\n", $response);
            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", (string)$xml[5]);
            $xml = new \SimpleXMLElement(utf8_encode($response));
            $res = $xml->xpath('//soapBody');
            
            if(!isset($res[0]->ns4consultarAvisosPendentesResposta->aviso)){
                $tam = 0;
            }
            else if(!isset($res[0]->ns4consultarAvisosPendentesResposta->aviso[0])){
                $tam = 1;
            }
            else{
                $tam = count($res[0]->ns4consultarAvisosPendentesResposta->aviso);
            }
            if($tam == 1){
                $avisoPendente['PjeIntimacao']['id_aviso']                  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso['idAviso'];
                $avisoPendente['PjeIntimacao']['cod_orgao_julgador']        = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['codigoOrgao'];
                $avisoPendente['PjeIntimacao']['nome_orgao']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['nomeOrgao'];
                $avisoPendente['PjeIntimacao']['processo_numeracao_unica']  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo['numero'];
                $avisoPendente['PjeIntimacao']['codigoLocalidade']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo['codigoLocalidade'];
                $avisoPendente['PjeIntimacao']['tipo_comunicacao']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso['tipoComunicacao'];
                $avisoPendente['PjeIntimacao']['destinatario_pje']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['nome'];
                
                if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'])){
                    $avisoPendente['PjeIntimacao']['docDestinatario']       = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'];
                }
                else $avisoPendente['PjeIntimacao']['docDestinatario']      = "Não informado";
                $avisoPendente['PjeIntimacao']['tipoPessoa']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2destinatario->ns2pessoa['tipoPessoa'];

                if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2valorCausa)){
                    $avisoPendente['PjeIntimacao']['valor_causa']           = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2valorCausa;
                }
                else $avisoPendente['PjeIntimacao']['valor_causa']          = "Não informado";
                
                $vetor = [];
                $tamParam = count($res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2outroParametro);
                for($j = 0; $j < $tamParam; $j++) $vetor[(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2outroParametro[$j]['nome']] = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2outroParametro[$j]['valor'];
                
                $avisoPendente['PjeIntimacao']['prazo']                     = $vetor['mni:pje:expedientePrazo'];

                if(isset($vetor['mni:pje:expedienteDataLimiteCiencia'])){
                    $avisoPendente['PjeIntimacao']['data_limite_ciencia']   = $this->mascara("".$vetor['mni:pje:expedienteDataLimiteCiencia']."", "####-##-## ##:##:##");
                }
                else $avisoPendente['PjeIntimacao']['data_limite_ciencia']  = "Não informado";
                
                $avisoPendente['PjeIntimacao']['descricao_ato']             = $vetor['mni:pje:expedienteDescricaoAto'];
                $avisoPendente['PjeIntimacao']['data_expedicao']            = $this->mascara("".$vetor['mni:pje:expedienteDataExpedicao']."", "####-##-## ##:##:##");
                $avisoPendente['PjeIntimacao']['meio']                      = $vetor['mni:pje:expedienteMeio']; 
                $avisoPendente['PjeIntimacao']['data_disponibilizacao']     = $this->mascara("".(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2dataDisponibilizacao."", "####-##-## ##:##:##");
                $avisoPendente['PjeIntimacao']['instancia']                 = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['instancia'];
                $avisoPendente['PjeIntimacao']['codigo_municipio_ibge']     = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso->ns2processo->ns2orgaoJulgador['codigoMunicipioIBGE'];
                
                return $avisoPendente;   
            }
            else if ($tam > 1){
                for($i = 0; $i < $tam; $i++){
                    $avisoPendente[$i]['PjeIntimacao']['id_aviso']                  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]['idAviso'];
                    $avisoPendente[$i]['PjeIntimacao']['cod_orgao_julgador']        = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['codigoOrgao'];
                    $avisoPendente[$i]['PjeIntimacao']['nome_orgao']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['nomeOrgao'];
                    $avisoPendente[$i]['PjeIntimacao']['processo_numeracao_unica']  = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo['numero'];
                    $avisoPendente[$i]['PjeIntimacao']['codigoLocalidade']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo['codigoLocalidade'];
                    $avisoPendente[$i]['PjeIntimacao']['tipo_comunicacao']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]['tipoComunicacao'];
                    $avisoPendente[$i]['PjeIntimacao']['destinatario_pje']          = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['nome']; 
                    
                    if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'])){
                        $avisoPendente[$i]['PjeIntimacao']['docDestinatario']       = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['numeroDocumentoPrincipal'];
                    }       
                    else $avisoPendente[$i]['PjeIntimacao']['docDestinatario']      = "Não informado";
                    $avisoPendente[$i]['PjeIntimacao']['tipoPessoa']                = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2destinatario->ns2pessoa['tipoPessoa'];

                    if(isset($res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2valorCausa)){
                        $avisoPendente[$i]['PjeIntimacao']['valor_causa']           = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2valorCausa;
                    }
                    else $avisoPendente[$i]['PjeIntimacao']['valor_causa']          = "Não informado";
                
                    $vetor = [];
                    $tamParam = count($res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2outroParametro);
                    for($j = 0; $j < $tamParam; $j++) $vetor[$i][(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2outroParametro[$j]['nome']] = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2outroParametro[$j]['valor'];
                    
                    $avisoPendente[$i]['PjeIntimacao']['prazo']                     = $vetor[$i]['mni:pje:expedientePrazo'];

                    if(isset($vetor[$i]['mni:pje:expedienteDataLimiteCiencia'])){
                        $avisoPendente[$i]['PjeIntimacao']['data_limite_ciencia']   = $this->mascara("".$vetor[$i]['mni:pje:expedienteDataLimiteCiencia']."", "####-##-## ##:##:##");
                    }
                    else $avisoPendente[$i]['PjeIntimacao']['data_limite_ciencia']  = "Não informado";
                    
                    $avisoPendente[$i]['PjeIntimacao']['descricao_ato']             = $vetor[$i]['mni:pje:expedienteDescricaoAto'];
                    $avisoPendente[$i]['PjeIntimacao']['data_expedicao']            = $this->mascara("".$vetor[$i]['mni:pje:expedienteDataExpedicao']."", "####-##-## ##:##:##");
                    $avisoPendente[$i]['PjeIntimacao']['meio']                      = $vetor[$i]['mni:pje:expedienteMeio']; 
                    $avisoPendente[$i]['PjeIntimacao']['data_disponibilizacao']     = $this->mascara("".(string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2dataDisponibilizacao."", "####-##-## ##:##:##");
                    $avisoPendente[$i]['PjeIntimacao']['instancia']                 = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['instancia'];
                    $avisoPendente[$i]['PjeIntimacao']['codigo_municipio_ibge']     = (string)$res[0]->ns4consultarAvisosPendentesResposta->aviso[$i]->ns2processo->ns2orgaoJulgador['codigoMunicipioIBGE'];                
                }
                return $avisoPendente;
            }                            
        }
        else{
            return 500;
        }   
    }

    private function descricao_ato($D_ato)
    {
        $conn = ConnectionManager::get('sigad');
        $sql        = "SELECT pda.id FROM sigad.pje_descricao_atos as pda WHERE pda.nome = '".$D_ato."'";
        $retorno    = $conn->query($sql)->fetchAll();
        return $retorno[0][0];
    }

    private function IdComarca($codPJE){
        $conn = ConnectionManager::get('sigad');       
        $sql = "SELECT cdd.comarca_id
                FROM cidades as cdd
                WHERE cdd.cod_pje = '$codPJE'";
        $retorno = $conn->query($sql)->fetchAll();
        if (empty($retorno))
            return 0;
        else
            return $retorno[0][0];
    }

    private function existeProcesso($processo){   
        $conn = ConnectionManager::get('sigad');     
        $sql = "SELECT prcss.id 
                FROM processos as prcss
                WHERE REPLACE(REPLACE(prcss.numeracao_unica,'-',''),'.','') = '$processo'";
        $retorno = $conn->query($sql)->fetchAll();
        if (!empty($retorno))
            return $retorno[0][0];
    }

    private function cpfProcesso($processo, $ctrl){
        $conn = ConnectionManager::get('sigad');        
        $sql = "SELECT psf.cpf, ps.nome
                FROM processos as prcss
                INNER JOIN assistidos as ass ON ass.id = prcss.assistido_id
                INNER JOIN pessoas as ps ON ps.id = ass.pessoa_id
                INNER JOIN pessoa_fisicas as psf ON psf.pessoa_id = ps.id
                WHERE REPLACE(REPLACE(prcss.numeracao_unica,'-',''),'.','') = '$processo'";
        $retorno = $conn->query($sql)->fetchAll();
        if (!empty($retorno)){
            if($ctrl == 1){
                return preg_replace('/[^0-9]/', '', $retorno[0][0]);
            }
            else if($ctrl == 2){
                return $retorno[0][1];
            }
        }
    }

    private function cnpjProcesso($processo, $ctrl){
        $conn = ConnectionManager::get('sigad');      
        $sql = "SELECT psj.cnpj, ps.nome
                FROM processos as prcss
                INNER JOIN assistidos as ass ON ass.id = prcss.assistido_id
                INNER JOIN pessoas as ps ON ps.id = ass.pessoa_id
                INNER JOIN pessoa_juridicas as psj ON psj.pessoa_id = ps.id
                WHERE REPLACE(REPLACE(prcss.numeracao_unica,'-',''),'.','') = '$processo'";
        $retorno = $conn->query($sql)->fetchAll();
        if (!empty($retorno)){
            if($ctrl == 1){
                return preg_replace('/[^0-9]/', '', $retorno[0][0]);
            }
            else if($ctrl == 2){
                return $retorno[0][1];
            }
        }
    }

    private function existeAtuacao($processo){
        $conn = ConnectionManager::get('sigad');       
        $sql = "SELECT prcss.atuacao_id 
                FROM processos as prcss
                WHERE REPLACE(REPLACE(prcss.numeracao_unica,'-',''),'.','') = '$processo'";
        $retorno = $conn->query($sql)->fetchAll();
        if (!empty($retorno))
            return $retorno[0][0];
    }

    private function idAtuacao($codPje){
        $conn = ConnectionManager::get('sigad');        
        $sql = "SELECT atuac.id 
                FROM atuacoes as atuac
                WHERE atuac.cod_pje = '$codPje'";
        $retorno = $conn->query($sql)->fetchAll();
        if (!empty($retorno))
            return $retorno[0][0];
        else
            return -1;
    }

    private function idUnidDefensorial($codPje)
    {
        $conn = ConnectionManager::get('sigad');
        $sql = "SELECT atcud.unidade_defensorial_id
                FROM sigad.atuacoes_unidade_defensoriais as atcud
                INNER JOIN sigad.atuacoes as atc ON atc.id = atcud.atuacao_id
                WHERE atc.cod_pje = '$codPje'
                ORDER BY unidade_defensorial_id DESC
                LIMIT 1";
        $retorno = $conn->query($sql)->fetchAll();
        if (!empty($retorno))
            return $retorno[0][0];
        else
            return null;
    }

    private function existeCPF($cpf){
        $conn = ConnectionManager::get('sigad');      
        $sql = "SELECT ass.id 
                FROM pessoa_fisicas as pf
                inner join pessoas as ps on ps.id = pf.pessoa_id
                inner join assistidos as ass on ass.pessoa_id = ps.id
                WHERE REPLACE(REPLACE(pf.cpf,'.',''),'-','') = '$cpf'";
        $retorno = $conn->query($sql)->fetchAll();
        if (empty($retorno))
            return 0;
        else
            return $retorno[0][0];
    }

    private function existeCNPJ($cnpj){
        $conn = ConnectionManager::get('sigad');       
        $sql = "SELECT ass.id 
                FROM pessoa_juridicas as pj
                inner join pessoas as ps on ps.id = pj.pessoa_id
                inner join assistidos as ass on ass.pessoa_id = ps.id
                WHERE REPLACE(REPLACE(REPLACE(pj.cnpj,'.',''),'/',''),'-','') = '$cnpj'";
        $retorno = $conn->query($sql)->fetchAll();
        if (empty($retorno))
            return 0;
        else
            return $retorno[0][0];
    }

    private function existeAviso($idAviso){
        $conn = ConnectionManager::get('sigad');        
        $sql = "SELECT avs.id_aviso
                FROM pje_aviso_pendentes as avs
                WHERE avs.id_aviso = '$idAviso'";        
        $retorno = $conn->query($sql)->fetchAll();
        if (empty($retorno))
            return 0;
        else
            return 1;
    }
        
    private function mascara($entrada, $formato){
        $retorno = '';
        $pos = 0;
        for($i = 0; $i<=strlen($formato)-1; $i++) {
            if($formato[$i] == '#') {
                if(isset($entrada[$pos])) {
                    $retorno .= $entrada[$pos++];
                }
            }
            else{
                $retorno .= $formato[$i];
            }
        }
        return $retorno;
    }

    public function deleteAudio(){
        $this->viewBuilder()->className('Json');
        $this->loadModel('Solicitacoes');
        $Solicita = TableRegistry::get("Solicitacoes");
        $solicitacoes = $this->Solicitacoes->find()
        ->where(['caminho_audio <> ""'])->all();
        foreach($solicitacoes as $solicitacao){
            if($solicitacao->status != 1 && $solicitacao->status != 7){
                $path = "../webroot/".$solicitacao->caminho_audio;
                if (file_exists($path)) {
                    unlink($path);
                    $query = $Solicita->query();
                    $result = $query->update()->set(['caminho_audio' => null])->where(['id' => $solicitacao->id])->execute(); 
                    $this->set('success', 'Arquivo de áudio excluído com sucesso.');
                } else {
                    $this->set('success', 'O arquivo de áudio não existe.');
                }
            }
        }
        $this->set('_serialize', ['success', 'data', 'error_message']);
    }
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Integração Aviso Pendentes Plataforma PJE - (FIM)
}
