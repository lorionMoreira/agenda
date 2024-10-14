<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use FlyingLuscas\Correios\Client;
use Cake\Mailer\Email;
use Cake\Routing\Router;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    var $User;

    public function initialize()
    {
        parent::initialize();
        $this->User = $this->Users->newEntity();
        $this->Auth->allow(['cadastro', 'logout', 'recuperaSenha', 'reset', 'sendErrorNotification','email']);
    }

    public function isAuthorized($user)
    {
        #TODO Poder editar apenas o seu
        $action = $this->request->getParam('action');

        if (in_array($action, ['edit', 'add']))
        {
            $user_id = (int)$this->request->getParam('pass.0');
            return true;
        }

        return parent::isAuthorized($user);
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('login_senha_cadastro');
        if($this->request->is('post'))
        {
            $this->request->data(
                'username',
                $this->User->cpfSemtracoEPonto($this->request->data('username'))
            );

            $user = $this->Auth->identify();
            if($user)
            {
                $this->Auth->setUser($user);
                $session = $this->request->session();
                $session->write('User', $user);

                return $this->redirect('agendamentos');
            }
            $this->Flash->error(__('Usuário ou senha inválidos, tente novamente.'));
        }
    }

    public function logout()
    {
        $session = $this->request->session();
        $session->delete('User');
        $this->Flash->success('Usuário deslogado.');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addOnlyUser()
    {
        $this->viewBuilder()->setLayout('login_senha_cadastro');
        $user = $this->Users->newEntity();

        if ($this->request->is('post'))
        {
            $data = $this->request->getData();
            if (!empty($data))
    		{
                $user = TableRegistry::get('Users');
                $pessoa_f = TableRegistry::get('PessoaFisicas');
                $pessoa = TableRegistry::get('Pessoas');
                $assistido = TableRegistry::get('Assistidos');
                $contato = TableRegistry::get('Contatos');
                $cpf = $data['PessoaFisicas']['cpf'];

                $pessoa_f = $pessoa_f->findByCpf($cpf)->first();
                $assistido = $assistido->findByPessoaId($pessoa_f->pessoa_id)->first();

                if($assistido)
                {
                    $data['Users']['sigad_user'] = $assistido->id;
                }else{
                    $assistido = TableRegistry::get('Assistidos');
                    $assistido_e = $assistido->newEntity();
                    $assistido_e->pessoa_id = $pessoa_f->pessoa_id;
                    $assistido_e->numero_triagem = $assistido_e->montarTriagem($pessoa_f->pessoa_id);
                    $assistido->save($assistido_e);
                    $data['Users']['sigad_user'] = $assistido_e->id;
                }

                //Atualizar info de contato no SIGAD
                $pessoa_e = $pessoa->findById($pessoa_f->pessoa_id)->first();
                $contato_e = $contato->findById($pessoa_e->contato_id)->first();
                $contato_e->email = $data['Usuarios']['email'];
                $contato_e->celular = $data['Usuarios']['celular'];
                $contato_e->whatsapp = $data['Usuarios']['whatsapp'];
                $contato->save($contato_e);
                // FIM
                
                $data['Users']['username'] = $this->User->cpfSemtracoEPonto($cpf);
                $data['Users']['password'] = $data['Usuarios']['senha'];
                $data['Users']['email'] = $data['Usuarios']['email'];

                $user_e = $user->newEntity($data);

                if($user->save($user_e))
                {
                    $this->Flash->success(__('Cadastro efetuado com sucesso.'));
                    return $this->redirect(['action' => 'login']);
                }

                $this->Flash->error("Erro ao salvar Usuário");
            }
        }else{
            $this->Flash->success(__('Como já é cadastro, basta criar um usuário para o sistema de agendamento online.'));
        }

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    public function cadastro() {
        $this->viewBuilder()->setLayout('login_senha_cadastro');
        $user = $this->Users->newEntity();
        if ($this->request->is('post'))
        {
            $data = $this->request->getData();
            if (!empty($data))
            {
                $usuarioJaTemCadastroSigad = false;

                $user = TableRegistry::get('Users');

                $user_e = $user->newEntity($data);

                if(empty($data['sigad_user']) && !empty($data['pessoa_user'])){
                    $assistidoTabela = TableRegistry::get('Assistidos');                    
                    $assistidoModel = $assistidoTabela->newEntity();
                    $assistidoModel->dados_completos = 1;
                    $assistidoModel->dt_cadastro = date("Y-m-d H:i:s");
                    $assistidoModel->pessoa_id = $data['pessoa_user'];
                    $assistidoModel->numero_triagem = $assistidoModel->montarTriagem($assistidoModel->pessoa_id);
                    
                    if(!$assistidoTabela->save($assistidoModel)){
                        throw new \Exception("Erro ao salvar o assistido", 1);                        
                    } 
                    
                    $usuarioJaTemCadastroSigad = true;
                    $user_e->sigad_user = $assistidoModel->id;                    
                }

                if($user->save($user_e))
                {
                    $this->loadModel('Contatos');
                    
                    if(!empty($data['contato_ajax'])){
                        $contato = $this->Contatos->newEntity();
                        $contato->id = $data['contato_ajax'];
                        $contato->email = $data['Users']['email'];
                        if(!$this->Contatos->save($contato)){
                            throw new \Exception("Erro ao salvar contato", 12);                        
                        }
                        $usuarioJaTemCadastroSigad = true;
                    }

                    $this->request->data('username', $user_e->username);
                    $this->request->data('password', $this->request->getData()['Users']['password']);

                    $authUser = $this->Auth->identify();

                    if ($authUser) {
                        $this->Auth->setUser($authUser);
                        $session = $this->request->session();
                        $session->write('User', $authUser);                        
                    } else {
                        return $this->redirect(['action' => 'login']);
                    }

                    
                    if($usuarioJaTemCadastroSigad){
                        $this->Flash->success(__('Detectamos que você já tem cadastro na defensoria, por favor mantenha seus dados atualizados.'));
                        return $this->redirect('users/edit');    
                    }
                    $this->Flash->success(__('Cadastro inicial realizado com sucesso! Para realizar agendamentos conclua seu cadastro.'));
                    return $this->redirect('users/add');
//
                } else {
//                     var_dump($user_e->getErrors());
                    $this->Flash->error(__('Erro ao realizar cadastro. Tente novamente.'));
                }

            }
        }

        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }


    public function comboCidades()
    {
        $this->loadModel('Cidades');
        $cidades = $this->Cidades->find('List', [
            'conditions' => ['estado_id' => 5],
            'keyField' => 'id',
            'valueField' => 'nome'
        ]);
        $this->loadModel('Estados');
        $estados = $this->Estados->find('List', [
            
            'keyField' => 'id',
            'valueField' => 'nome'
        ]);

        $this->set(compact('user', 'cidades', 'estados'));
        $this->set('_serialize', ['user']);
    }

    public function add()
    {
        $this->viewBuilder()->setLayout('sistema');
        $this->set('title', "Meus Dados");

        $user = $this->Users->newEntity();
        
        $theUser = $this->Users->get($this->request->session()->read('User.id'));

        if($this->request->is('get')){
            $this->comboCidades();
            if($this->request->session()->check('User.sigad_user')){
                return $this->redirect("users/edit");
            }else{
                $cpf = $this->request->session()->read('User.username');
                $this->loadComponent('MascaraCPFSigad');
                $cpfComMascara = $this->MascaraCPFSigad->aplicar($cpf);                
                $this->loadComponent('PessoasFisicas');                
                $pessoaFisicaJaExistente = $this->PessoasFisicas->existente($cpfComMascara);

                $user = TableRegistry::get('Users');    
                $theUser = $user->get($this->request->session()->read('User.id'));

                if($pessoaFisicaJaExistente->resultado == true && \is_null($pessoaFisicaJaExistente->assistido)){
                    $assistidoTabela = TableRegistry::get('Assistidos');                    
                    $assistidoModel = $assistidoTabela->newEntity();
                    $assistidoModel->dados_completos = 1;
                    $assistidoModel->dt_cadastro = date("Y-m-d H:i:s");
                    $assistidoModel->pessoa_id = $pessoaFisicaJaExistente->pessoa->id;
                    $assistidoModel->numero_triagem = $assistidoModel->montarTriagem($assistidoModel->pessoa_id);
                    if(!$assistidoTabela->save($assistidoModel)){
                        throw new \Exception("Erro ao salvar o assistido", 1);                        
                    } 
                                        
                    $theUser->sigad_user = $assistidoModel->id;    
                    $user->save($theUser);
                }

                if($pessoaFisicaJaExistente->resultado == true && !\is_null($pessoaFisicaJaExistente->assistido)){
                    $theUser->sigad_user = $pessoaFisicaJaExistente->assistido->id;    
                    $user->save($theUser);   
                }
                
                if($pessoaFisicaJaExistente->resultado == true){
                    return $this->redirect("users/edit");
                }
            }            
        }
        
        if ($this->request->is('post')){        
            $data = $this->request->getData();
            if (!empty($data))
    		{   
                if ($data['PessoaFisicas']['nascimento'] == null || $data['PessoaFisicas']['nascimento'] == '') {
                    $this->Flash->error("Informe uma data de nascimento válida no formato dia/mês/ano");
                    
                    $this->comboCidades();
                    
                    return;
                } 
                
                if(!is_null($theUser->sigad_user)){
                    $return['ok'] = true;                
                }else {                    
                    $return = $this->salvarNovo($data);
                }    
                if($return['ok']){
                    $this->Flash->success(__('Cadastro efetuado com sucesso. Agora você pode realizar solicitações de agendamento.'));
                    return $this->redirect("agendamentos/add");
                }
                $this->Flash->error($return['msg']);
                
            }
        }

        
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $this->viewBuilder()->setLayout('sistema');
        $this->set('title', "Meus Dados");

        $id = $this->Auth->user('id');
        $this->loadModel('Assistidos');
        $this->loadModel('Pessoas');
        $this->loadModel('PessoaFisicas');
        $this->loadModel('Contatos');
        $this->loadModel('Enderecos');
        $this->loadModel('Cidades');
        $this->loadModel('Estados');

        $cidades = $this->Cidades->find('List', [
            'conditions' => ['estado_id' => 5],
            'keyField' => 'id',
            'valueField' => 'nome'
        ]);

        $estados = $this->Estados->find('List', [
            
            'keyField' => 'id',
            'valueField' => 'nome'
        ]);

        $user = $this->Users->get($id);
        $assistido = $this->Assistidos->get($user->sigad_user);
        
        $pessoa = $this->Pessoas->get($assistido->pessoa_id);
       // debug($pessoa);
        $pessoaFisica = $this->PessoaFisicas->findByPessoaId($pessoa->id)->first();
        $contato = $this->Contatos->get($pessoa->contato_id);
        $endereco = $this->Enderecos->get($pessoa->endereco_id);

        $cidade = $this->Cidades->get($endereco->cidade_id);
        if ($this->request->is(['patch', 'post', 'put'])) {                                    
            $data = $this->request->getData();
            //debug($data); die('dd');
            $user->email = $data['Contatos']['email'];
            $this->Users->save($user);


            $data['PessoaFisicas']['nascimento'] = Time::createFromFormat('d/m/Y', $data['PessoaFisicas']['nascimento']);
            $data['Pessoas']['nome'] = strtoupper($data['Pessoas']['nome']);
            $data['PessoaFisicas']['nome_mae'] = strtoupper($data['PessoaFisicas']['nome_mae']);
            $data['PessoaFisicas']['nome_pai'] = strtoupper($data['PessoaFisicas']['nome_pai']);
            $data['Pessoas']['nome_social'] = strtoupper($data['Pessoas']['nome_social']);
            $data['PessoaFisicas']['opcao_genero_id'] = $data['PessoaFisicas']['opcao_genero_id'];
            $data['PessoaFisicas']['orientacao_sexual_id'] = $data['PessoaFisicas']['orientacao_sexual_id'];
            $pessoa = $this->Pessoas->patchEntity($pessoa, $data);
            $pessoaFisica = $this->PessoaFisicas->patchEntity($pessoaFisica, $data);
            
            $contato = $this->Contatos->patchEntity($contato, $data);
            $endereco = $this->Enderecos->patchEntity($endereco, $data);
            $cidade = $this->Cidades->patchEntity($cidade, $data);
            $success = true;

            if(!$this->Pessoas->save($pessoa)){
                $success = false;
            }
            
            if(!$this->PessoaFisicas->save($pessoaFisica)){
                $success = false;
            }

            if (!$this->Contatos->save($contato)) {
//                debug($contato);
                $success = false;
            }
            if (!$this->Enderecos->save($endereco)) {
//                debug($endereco);
                $success = false;
            }

            if (!$this->Cidades->save($cidade)) {
//                debug($cidade);
                $success = false;
            }

            if ($success) {
                $this->Flash->success("Cadastro atualizado com sucesso!");
            } else {
                $this->Flash->error("Erro ao atualizar cadastro. Por favor, tente novamente.");
            }
//            $user = $this->Users->patchEntity($user, $this->request->getData());
//            if ($this->Users->save($user)) {
//                $this->Flash->success(__('The user has been saved.'));
//
//                return $this->redirect(['action' => 'index']);
//            }
//            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set(compact('user', 'cidades','estados', 'assistido', 'pessoa', 'pessoaFisica', 'contato', 'endereco','cidade'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function recuperaSenha()
    {
        $this->viewBuilder()->setLayout('login_senha_cadastro');

         $this->loadComponent('Recaptcha.Recaptcha', [
                'enable' => true,
                'sitekey' => '6LctT6kUAAAAANwIWvSARdxg9kK28OOvumWr5KoU', //
                'secret' => '6LctT6kUAAAAAAa1AT8fCi-WiG81KjEN6cYSHarP',
                'type' => 'image',  
                'theme' => 'light', 
                'lang' => 'pt-BR',  
                'size' => 'normal'
            ]);


        if($this->request->isPost())
        {
            
            //if($this->Recaptcha->verify()){

                $data = $this->request->getData();
                $cpfSemMascara = $this->User->cpfSemtracoEPonto($data['cpf']);

                $user = $this->Users->findByUsername($cpfSemMascara)->first();
                //dd($user);
                if($user) {

                    $nascimento = implode('-', array_reverse(explode('/', $data['PessoaFisicas']['nascimento'])));                    
                    $PF = TableRegistry::get('PessoaFisicas');

                    // Mascara pq eles salvam com máscara '-'
                    $cpf = $this->Mask("###.###.###-##", $data['cpf']);
                    $nome_mae = Trim($data['PessoaFisicas']['nome_mae']);
                    $email_cad = strtolower(Trim($data['email_cad']));                                 
                    //dd($data);
                    $pessoa = $PF->find()->where([
                        'PessoaFisicas.cpf' => $cpf,
                        'Trim(PessoaFisicas.nome_mae) like' => Trim($data['PessoaFisicas']['nome_mae']),
                        'PessoaFisicas.nascimento' => $nascimento
                    ])->first();

                    if(!$pessoa){ 
                        $pessoaSemMae = $PF->find()->where([
                        'PessoaFisicas.cpf' => $cpf,                        
                        'PessoaFisicas.nascimento' => $nascimento
                        ])->first();

                        if($pessoaSemMae){ //Testa se o CPF, NASCIMENTO E E-MAIL do assistido existem
                            if($email_cad == strtolower(Trim($user->email))){
                                $pessoa = true;
                            }
                        }else{
                            $pessoaSemNascimento = $PF->find()->where([
                            'PessoaFisicas.cpf' => $cpf,                        
                            'Trim(PessoaFisicas.nome_mae) like' => Trim($data['PessoaFisicas']['nome_mae'])
                            ])->first();
                            if($pessoaSemNascimento){ //Testa se o CPF, MAE E E-MAIL do assistido existem
                                if($email_cad == strtolower(Trim($user->email))){
                                    $pessoa = true;
                                }
                            }
                        }
                    }

                    if ($pessoa || (isset($data['email'])) && $data['email'] == $user->email) {
                    //dd($pessoa);
                        
                        $user->passkey = uniqid();
                        $user->timeout = time() + DAY;
                        $url = Router::Url(['controller' => 'users', 'action' => 'reset'], true) . '/' . $user->passkey;
                                            
                        if($this->Users->save($user)) {
                            
                            //$this->sendResetEmail($url, $user);
                            //$this->Flash->success(__('Email enviado com instruções para recuperação de senha.'));
                            return $this->redirect($url);
                        }
                        //dd('n salvou');
                    }

                }
                    /*
                    $this->envioEmailSenha('Assistido tentou resetar a senha, sem sucesso.', $cpf, $nome_mae, $data['PessoaFisicas']['nascimento'], $data['email'], $email_cad);
                */

                    $this->Flash->error(__('Informações incorretas. Tente novamente em 10 minutos. Se o problema continuar, entre em contato com o 129.'));
                    $url_resetar = Router::Url(['controller' => 'users', 'action' => 'recuperaSenha'], true);
                    return $this->redirect($url_resetar);
            /*}else{
                    $this->Flash->error(__('Captcha digitado errado, por favor corrija'));
            }*/
        }
    }

    private function envioEmailSenha($assunto, $cpf, $nome_mae, $nascimento, $email_info, $email_cad)
    {
        
        $email = new Email();
        $email->template('erro_reset_senha');
        $email->emailFormat('html');
        $email->from('suporte@defensoria.ba.def.br');
        $email->to("daniele.souza@defensoria.ba.def.br");
        $email->subject($assunto);
        $email->viewVars(['cpf' => $cpf,'nome_mae' => $nome_mae, 'data_nascimento' => $nascimento, 'email_informado' => $email_info, 'email_cadastrado' => $email_cad]);
        $email->send();        
    }


    function Mask($mask, $str){

        $str = str_replace(" ","",$str);

        for($i=0;$i<strlen($str);$i++){
            $mask[strpos($mask,"#")] = $str[$i];
        }

        return $mask;

    }


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

    public function reset($passkey = null)
    {
        if($passkey)
        {
            $user = $this->Users->find('All',[
                'conditions' => ['passkey' => $passkey, 'timeout >' => time()]
            ])->first();

            if($user)
            {
                if (!empty($this->request->data()))
                {
                    /*$this->request->data(
                        'username',
                        $this->User->cpfSemtracoEPonto($this->request->data('username'))
                    );*/
					
					//#4
					$this->request->data(
                        'username',
                        $this->User->cpfSemtracoEPonto($user['username'])
                    );
					
                    $this->request->data['passkey'] = null;
                    $this->request->data['timeout'] = null;
                    $user = $this->Users->patchEntity($user, $this->request->data());


                    if ($this->Users->save($user))
                    {
                        $this->Flash->success(__('Sua senha foi atualizada com sucesso.'));
                        return $this->redirect(array('action' => 'login'));
                    } else {
                        $this->Flash->error(__('A senha deve ter uma letra minúscula, uma maiúscula, um número e ter pelo menos 8 caracteres.'));
                    }
                }
            } else {
                $this->Flash->error('Solicitação expirada. Por favor, tente novamente.');
                $this->redirect(['action' => 'recuperaSenha']);
            }

            unset($user->password);
            $this->set(compact('user'));
        } else {
            $this->redirect(['action' => 'recuperaSenha']);
        }
    }

    public function cep()
    {
        $correios = new Client;
        $cep = $this->request->getQuery('cep');

        $this->set(['result' => $correios->zipcode()->find($cep)]);
        $this->render('/Element/ajaxReturn', 'ajax');
    }

    public function cpf()
    {
       // try{
            $user = TableRegistry::get('Users');
            //$assistido = TableRegistry::get('PessoaFisicas');
            $cpf = $this->request->getQuery('cpf');
            $result['cpf'] = $cpf;
            $user = $user->findByUsername($cpf)->first();            
            $result['ja_tem_usuario'] = $user != null;
            $result['concluiu_cadastro'] = $user != null && $user->sigad_user != null;

            $this->loadComponent('MascaraCPFSigad');
            $cpfComMascara = $this->MascaraCPFSigad->aplicar($cpf);
            
            $this->loadComponent('PessoasFisicas');
            $resultadoPesquisa = $this->PessoasFisicas->existente($cpfComMascara);
            $result['email'] =  null;
            $result['id_contato_email'] = null;
            
            if($resultadoPesquisa->resultado == true){
                $pessoa = $resultadoPesquisa->pessoa;
                
                $result['email'] = $pessoa->contato->email;
                $result['id_contato_email'] = $pessoa->contato->id;
            }
            
            $result['id_assistido_ajax'] = null;
            if(!is_null($resultadoPesquisa->assistido)){
                $result['id_assistido_ajax'] = $resultadoPesquisa->assistido->id;
            }

            $result['pessoa_user'] = null;
            if(!is_null($resultadoPesquisa->pessoa)){
                $result['pessoa_user'] = $resultadoPesquisa->pessoa->id;
            }

       /* }catch(\Exception $e){
            $result['cpf_invalido'] = true;            
        }*/

        
        $this->set(compact('result'));
        $this->render('/Element/ajaxReturn', 'ajax');
    }

    private function salvarNovo($data)
	{
        $ok = true; $msg = "Cadastro salvo com sucesso."; $id = 0;

        $data['Enderecos']['cep'] = str_replace(".", "", $data['Enderecos']['cep']);

        $contato = TableRegistry::get('Contatos');
		$contato_e = $contato->newEntity($data);
		$contato_e->email = $this->request->session()->read('User.email');

//        debug($data); die();
		if ($contato->save($contato_e))
		{
			$endereco = TableRegistry::get('Enderecos');
			$endereco_e = $endereco->newEntity($data);
			if ($endereco->save($endereco_e))
			{
//                debug($data); die();
				$data['Pessoas']['contato_id'] = $contato_e->id;
				$data['Pessoas']['endereco_id'] = $endereco_e->id;
				$data['Pessoas']['tipo'] = 'F';

				$pessoa = TableRegistry::get('Pessoas');
				$pessoa_e = $pessoa->newEntity($data);
                $pessoa_e->nome = strtoupper($pessoa_e->nome);
                $pessoa_e->nome_mae = strtoupper($pessoa_e->nome_mae);
                $pessoa_e->nome_pai = strtoupper($pessoa_e->nome_pai);
                $pessoa_e->nome_social = strtoupper($pessoa_e->nome_social);

				if ($pessoa->save($pessoa_e))
				{
					$data['PessoaFisicas']['pessoa_id'] = $pessoa_e->id;
					$data['PessoaFisicas']['nascimento'] = Time::createFromFormat('d/m/Y', $data['PessoaFisicas']['nascimento']);
					$data['PessoaFisicas']['tipo_documento_id'] = 102;				
					$data['PessoaFisicas']['opcao_genero_id'] = $data['PessoaFisicas']['opcao_genero_id'];
					$data['PessoaFisicas']['orientacao_sexual_id'] = $data['PessoaFisicas']['orientacao_sexual_id'];
					
                    $cpf = $this->request->session()->read('User.username');

                    $this->loadComponent('MascaraCPFSigad');

                    $cpfComMascara = $this->MascaraCPFSigad->aplicar($cpf);                    
                    $data['PessoaFisicas']['cpf'] = $cpfComMascara;

					$p_fisica = TableRegistry::get('PessoaFisicas');
					$p_fisica_e = $p_fisica->newEntity($data);
					if ($p_fisica->save($p_fisica_e))
					{
						$assistido = TableRegistry::get('Assistidos');
                        $assistido_e = $assistido->newEntity($data);
                        $assistido_e->dados_completos = 1;
                        $assistido_e->dt_cadastro = date("Y-m-d H:i:s");
                        $assistido_e->pessoa_id = $pessoa_e->id;
						$assistido_e->numero_triagem = $assistido_e->montarTriagem($pessoa_e->id);
                        $assistido_e->autorizacao_lgpd = $data['Users']['termos'] == 1 ? 1 : 0;

						if ($assistido->save($assistido_e))
						{
							$id = $assistido_e->id;
							//Salvar usuario
                            $user = TableRegistry::get('Users');
                            $theUser = $user->get($this->request->session()->read('User.id'));
                            $theUser->sigad_user = $id;

                            if (!$user->save($theUser)) {
                                $ok = false;
                                $msg = "Erro ao salvar Usuário.";
                                var_dump($user->getErros());
                                die();
                            } else {
                                $this->request->session()->write('User.sigad_user', $id);
                            }

						} else { //Nao salvou assistido
//                             var_dump($assistido_e->getErrors());
							$ok = false;
							$msg = "Erro ao salvar Assistido.";
						}
					} else { //Nao salvou Pessoa fisica
                         var_dump($p_fisica_e->getErrors());
						$ok = false;
						$msg = "Erro ao salvar Pessoa fisica.";
					}
				} else { //Nao salvou Pessoa
                     var_dump($pessoa_e->getErrors());
					$ok = false;
					$msg = "Erro ao salvar Pessoa.";
				}
			} else { //Nao salvou Endereco
                 var_dump($endereco_e->getErrors());
				$ok = false;
				$msg = "Erro ao salvar Assistido, aba de endereço.";
			}
		} else {//Nao salvou Contato
             var_dump($contato_e->getErrors());
			$ok = false;
			$msg = "Erro ao salvar Assistido, aba de contato.";
		}

		return ['ok' => $ok, 'msg' => $msg, 'id' => $id];
	}

    public function email()
    {
       // try{
            $user = TableRegistry::get('Users');
            //$assistido = TableRegistry::get('PessoaFisicas');
            $email = $this->request->getQuery('email');

            $result['email'] = $email;
            $user = $user->findByEmail($email)->first();            
            $result['ja_tem_email'] = $user != null;
            //$result['concluiu_cadastro'] = $user != null && $user->sigad_user != null;

           

        
        $this->set(compact('result'));
        $this->render('/Element/ajaxReturn', 'ajax');
    }
}
